<?php

declare(strict_types=1);

function ensure_storage_paths(array $config): void
{
    $databaseDir = dirname($config['db_file']);
    if (!is_dir($databaseDir)) {
        mkdir($databaseDir, 0775, true);
    }

    if (!is_dir($config['upload_dir'])) {
        mkdir($config['upload_dir'], 0775, true);
    }
}

function connect_database(array $config): PDO
{
    ensure_storage_paths($config);

    $db = new PDO('sqlite:' . $config['db_file']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    initialize_learning_database($db);

    return $db;
}

function initialize_learning_database(PDO $db): void
{
    // Nhật ký học tập & tích lũy sao
    $db->exec(
        "CREATE TABLE IF NOT EXISTS study_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            language TEXT NOT NULL DEFAULT 'en',
            category TEXT NOT NULL DEFAULT 'listening',
            title TEXT NOT NULL,
            duration_minutes INTEGER NOT NULL DEFAULT 0,
            stars INTEGER NOT NULL DEFAULT 1,
            score INTEGER,
            max_score INTEGER,
            note TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    // Ghi chú chép chính tả & học bài (Dictation & Shadowing Notes)
    $db->exec(
        "CREATE TABLE IF NOT EXISTS study_notes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            target_key TEXT NOT NULL UNIQUE,
            language TEXT NOT NULL DEFAULT 'en',
            note_content TEXT,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    // Tiến độ học từ vựng Flashcards
    $db->exec(
        "CREATE TABLE IF NOT EXISTS flashcard_progress (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            card_key TEXT NOT NULL UNIQUE,
            language TEXT NOT NULL,
            state TEXT NOT NULL DEFAULT 'new',
            review_count INTEGER NOT NULL DEFAULT 0,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    // Kết quả làm bài thi thử A2 Key
    $db->exec(
        "CREATE TABLE IF NOT EXISTS test_results (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            book_key TEXT NOT NULL,
            test_number INTEGER NOT NULL,
            part_number INTEGER NOT NULL,
            score INTEGER NOT NULL,
            total_questions INTEGER NOT NULL,
            answers_json TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );
}

function log_study_activity(
    PDO $db,
    string $language,
    string $category,
    string $title,
    int $durationMinutes,
    int $stars,
    ?int $score = null,
    ?int $maxScore = null,
    ?string $note = null
): int {
    $stmt = $db->prepare(
        'INSERT INTO study_logs (language, category, title, duration_minutes, stars, score, max_score, note, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, datetime("now", "localtime"))'
    );
    $stmt->execute([$language, $category, $title, $durationMinutes, $stars, $score, $maxScore, $note]);

    return (int) $db->lastInsertId();
}

function get_study_stats(PDO $db): array
{
    $totalStars = (int) $db->query('SELECT COALESCE(SUM(stars), 0) FROM study_logs')->fetchColumn();
    $totalMinutes = (int) $db->query('SELECT COALESCE(SUM(duration_minutes), 0) FROM study_logs')->fetchColumn();
    $totalSessions = (int) $db->query('SELECT COUNT(*) FROM study_logs')->fetchColumn();
    $todayStars = (int) $db->query("SELECT COALESCE(SUM(stars), 0) FROM study_logs WHERE date(created_at) = date('now', 'localtime')")->fetchColumn();
    $todayMinutes = (int) $db->query("SELECT COALESCE(SUM(duration_minutes), 0) FROM study_logs WHERE date(created_at) = date('now', 'localtime')")->fetchColumn();

    $testsCount = (int) $db->query('SELECT COUNT(*) FROM test_results')->fetchColumn();
    $vocabMastered = (int) $db->query("SELECT COUNT(*) FROM flashcard_progress WHERE state = 'mastered'")->fetchColumn();

    return [
        'total_stars' => $totalStars,
        'total_minutes' => $totalMinutes,
        'total_sessions' => $totalSessions,
        'today_stars' => $todayStars,
        'today_minutes' => $todayMinutes,
        'tests_count' => $testsCount,
        'vocab_mastered' => $vocabMastered,
    ];
}

function fetch_recent_logs(PDO $db, int $limit = 20): array
{
    $stmt = $db->prepare('SELECT * FROM study_logs ORDER BY id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function save_study_note(PDO $db, string $targetKey, string $language, string $content): void
{
    $stmt = $db->prepare(
        'INSERT INTO study_notes (target_key, language, note_content, updated_at)
         VALUES (?, ?, ?, datetime("now", "localtime"))
         ON CONFLICT(target_key) DO UPDATE SET note_content = excluded.note_content, updated_at = excluded.updated_at'
    );
    $stmt->execute([$targetKey, $language, $content]);
}

function get_study_note(PDO $db, string $targetKey): string
{
    $stmt = $db->prepare('SELECT note_content FROM study_notes WHERE target_key = ?');
    $stmt->execute([$targetKey]);
    $res = $stmt->fetchColumn();

    return $res !== false ? (string) $res : '';
}

function save_flashcard_state(PDO $db, string $cardKey, string $language, string $state): void
{
    $stmt = $db->prepare(
        'INSERT INTO flashcard_progress (card_key, language, state, review_count, updated_at)
         VALUES (?, ?, ?, 1, datetime("now", "localtime"))
         ON CONFLICT(card_key) DO UPDATE SET state = excluded.state, review_count = review_count + 1, updated_at = excluded.updated_at'
    );
    $stmt->execute([$cardKey, $language, $state]);
}

function get_flashcard_progress_map(PDO $db, string $language): array
{
    $stmt = $db->prepare('SELECT card_key, state FROM flashcard_progress WHERE language = ?');
    $stmt->execute([$language]);
    $rows = $stmt->fetchAll();

    $map = [];
    foreach ($rows as $row) {
        $map[$row['card_key']] = $row['state'];
    }

    return $map;
}

function save_test_result(PDO $db, string $bookKey, int $testNumber, int $partNumber, int $score, int $total, array $answers): int
{
    $stmt = $db->prepare(
        'INSERT INTO test_results (book_key, test_number, part_number, score, total_questions, answers_json, created_at)
         VALUES (?, ?, ?, ?, ?, ?, datetime("now", "localtime"))'
    );
    $stmt->execute([$bookKey, $testNumber, $partNumber, $score, $total, json_encode($answers, JSON_UNESCAPED_UNICODE)]);

    // Cũng ghi vào study_logs
    $title = "A2 Key [{$bookKey}] Test {$testNumber}" . ($partNumber > 0 ? " Part {$partNumber}" : " Full");
    $stars = ($score >= $total) ? 5 : max(1, (int) round(($score / max(1, $total)) * 3));
    log_study_activity($db, 'en', 'test_practice', $title, 15, $stars, $score, $total);

    return (int) $db->lastInsertId();
}

function fetch_test_history(PDO $db, int $limit = 20): array
{
    $stmt = $db->prepare('SELECT * FROM test_results ORDER BY id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}
