<?php

declare(strict_types=1);

$config = require __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$db = connect_database($config);
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'save_note') {
    $targetKey = trim((string) ($_POST['target_key'] ?? ''));
    $language = trim((string) ($_POST['language'] ?? 'en'));
    $content = (string) ($_POST['content'] ?? '');

    if ($targetKey === '') {
        json_response(['ok' => false, 'message' => 'Target key is required.'], 400);
    }

    save_study_note($db, $targetKey, $language, $content);
    json_response(['ok' => true, 'message' => 'Đã lưu ghi chú / bài chép thành công!']);
}

if ($action === 'get_note') {
    $targetKey = trim((string) ($_GET['target_key'] ?? ''));
    $content = get_study_note($db, $targetKey);
    json_response(['ok' => true, 'content' => $content]);
}

if ($action === 'save_flashcard') {
    $cardKey = trim((string) ($_POST['card_key'] ?? ''));
    $language = trim((string) ($_POST['language'] ?? 'en'));
    $state = trim((string) ($_POST['state'] ?? 'learning')); // 'learning' | 'mastered'

    if ($cardKey === '') {
        json_response(['ok' => false, 'message' => 'Card key is required.'], 400);
    }

    save_flashcard_state($db, $cardKey, $language, $state);
    json_response(['ok' => true, 'message' => 'Cập nhật tiến độ từ vựng thành công!']);
}

if ($action === 'submit_test') {
    $bookKey = trim((string) ($_POST['book_key'] ?? ''));
    $testNumber = (int) ($_POST['test_number'] ?? 1);
    $partNumber = (int) ($_POST['part_number'] ?? 0);
    $score = (int) ($_POST['score'] ?? 0);
    $total = (int) ($_POST['total'] ?? 25);
    $answers = json_decode((string) ($_POST['answers'] ?? '[]'), true) ?: [];

    $id = save_test_result($db, $bookKey, $testNumber, $partNumber, $score, $total, $answers);
    $cambridge = get_a2_key_cambridge_scale($score, $total);
    $stats = get_study_stats($db);

    json_response([
        'ok' => true,
        'message' => 'Đã lưu kết quả bài làm và cộng sao thưởng!',
        'cambridge' => $cambridge,
        'stats' => $stats,
    ]);
}

if ($action === 'log_study') {
    $language = trim((string) ($_POST['language'] ?? 'en'));
    $category = trim((string) ($_POST['category'] ?? 'listening'));
    $title = trim((string) ($_POST['title'] ?? 'Luyện tập ngoại ngữ'));
    $duration = (int) ($_POST['duration_minutes'] ?? 10);
    $stars = max(1, (int) ($_POST['stars'] ?? 1));
    $note = trim((string) ($_POST['note'] ?? ''));

    log_study_activity($db, $language, $category, $title, $duration, $stars, null, null, $note);
    $stats = get_study_stats($db);

    json_response([
        'ok' => true,
        'message' => "Tuyệt vời! Bạn nhận được +{$stars} ⭐ sao học tập!",
        'stats' => $stats,
    ]);
}

if ($action === 'get_stats') {
    $stats = get_study_stats($db);
    $recentLogs = fetch_recent_logs($db, 10);
    $testHistory = fetch_test_history($db, 10);

    json_response([
        'ok' => true,
        'stats' => $stats,
        'recent_logs' => $recentLogs,
        'test_history' => $testHistory,
    ]);
}

json_response(['ok' => false, 'message' => 'Invalid action.'], 400);
