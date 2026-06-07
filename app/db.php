<?php

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

    initialize_database($db);

    return $db;
}

function initialize_database(PDO $db): void
{
    $db->exec(
        "CREATE TABLE IF NOT EXISTS activities (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            activity_date TEXT NOT NULL,
            title TEXT NOT NULL,
            category TEXT NOT NULL DEFAULT 'other',
            stars INTEGER NOT NULL,
            note TEXT,
            image_path TEXT,
            status TEXT NOT NULL DEFAULT 'pending',
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $columns = $db->query('PRAGMA table_info(activities)')->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'name');
    if (!in_array('category', $columnNames, true)) {
        $db->exec("ALTER TABLE activities ADD COLUMN category TEXT NOT NULL DEFAULT 'other'");
    }
    if (!in_array('parent_liked', $columnNames, true)) {
        $db->exec("ALTER TABLE activities ADD COLUMN parent_liked INTEGER NOT NULL DEFAULT 0");
    }
    if (!in_array('parent_comment', $columnNames, true)) {
        $db->exec("ALTER TABLE activities ADD COLUMN parent_comment TEXT");
    }
    if (!in_array('created_at', $columnNames, true)) {
        $db->exec("ALTER TABLE activities ADD COLUMN created_at TEXT");
        $db->exec("UPDATE activities SET created_at = activity_date || ' 00:00:00' WHERE created_at IS NULL OR created_at = ''");
    }
    if (!in_array('status', $columnNames, true)) {
        $db->exec("ALTER TABLE activities ADD COLUMN status TEXT NOT NULL DEFAULT 'pending'");
    }
    $db->exec("UPDATE activities SET status = 'ok' WHERE status = 'approved'");

    $db->exec(
        "CREATE TABLE IF NOT EXISTS rewards (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            reward_date TEXT NOT NULL,
            title TEXT NOT NULL,
            cost INTEGER NOT NULL,
            note TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $db->exec(
        "CREATE TABLE IF NOT EXISTS audit_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            actor TEXT NOT NULL,
            action TEXT NOT NULL,
            entity_type TEXT NOT NULL,
            entity_id INTEGER,
            description TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $db->exec(
        "CREATE TABLE IF NOT EXISTS holidays (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            holiday_date TEXT NOT NULL UNIQUE,
            type TEXT NOT NULL,
            note TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $db->exec(
        "CREATE TABLE IF NOT EXISTS weekly_goals (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            week_start TEXT NOT NULL,
            title TEXT NOT NULL,
            daily_target INTEGER NOT NULL DEFAULT 0,
            target_amount INTEGER NOT NULL,
            unit_label TEXT NOT NULL,
            progress_amount INTEGER NOT NULL DEFAULT 0,
            note TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $db->exec(
        "CREATE TABLE IF NOT EXISTS child_profiles (
            id INTEGER PRIMARY KEY CHECK (id = 1),
            nickname TEXT,
            full_name TEXT,
            birthday TEXT,
            class_name TEXT,
            favorite_subject TEXT,
            hobby TEXT,
            profile_note TEXT,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );
}

function insert_activity(
    PDO $db,
    string $date,
    string $title,
    string $category,
    int $stars,
    string $note,
    ?string $imagePath
): int
{
    $stmt = $db->prepare(
        'INSERT INTO activities(activity_date, title, category, stars, note, image_path, status) VALUES(?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$date, $title, $category, $stars, $note, $imagePath, 'pending']);

    return (int) $db->lastInsertId();
}

function insert_reward(PDO $db, string $date, string $title, int $cost, string $note): int
{
    $stmt = $db->prepare('INSERT INTO rewards(reward_date, title, cost, note) VALUES(?, ?, ?, ?)');
    $stmt->execute([$date, $title, $cost, $note]);

    return (int) $db->lastInsertId();
}

function find_activity_image_path(PDO $db, int $id): ?string
{
    $stmt = $db->prepare('SELECT image_path FROM activities WHERE id = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['image_path'] ?? null;
}

function find_activity(PDO $db, int $id): ?array
{
    $stmt = $db->prepare('SELECT * FROM activities WHERE id = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function find_reward(PDO $db, int $id): ?array
{
    $stmt = $db->prepare('SELECT * FROM rewards WHERE id = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function delete_activity(PDO $db, int $id): void
{
    $stmt = $db->prepare('DELETE FROM activities WHERE id = ?');
    $stmt->execute([$id]);
}

function count_activities_by_date(PDO $db, string $date): int
{
    $stmt = $db->prepare('SELECT COUNT(*) FROM activities WHERE activity_date = ?');
    $stmt->execute([$date]);

    return (int) $stmt->fetchColumn();
}

function update_child_activity_content(PDO $db, int $id, string $title, string $note): void
{
    $stmt = $db->prepare('UPDATE activities SET title = ?, note = ? WHERE id = ?');
    $stmt->execute([$title, $note, $id]);
}

function update_activity_parent_feedback(PDO $db, int $id, bool $liked, string $comment, string $status): void
{
    $stmt = $db->prepare('UPDATE activities SET parent_liked = ?, parent_comment = ?, status = ? WHERE id = ?');
    $stmt->execute([$liked ? 1 : 0, $comment, $status, $id]);
}

function delete_reward(PDO $db, int $id): void
{
    $stmt = $db->prepare('DELETE FROM rewards WHERE id = ?');
    $stmt->execute([$id]);
}

function fetch_activity_totals(PDO $db): array
{
    $sql = "
        SELECT
            COALESCE(SUM(stars), 0) AS total_earned,
            COALESCE(SUM(CASE WHEN activity_date = date('now', 'localtime') THEN stars ELSE 0 END), 0) AS today_stars,
            COALESCE(SUM(CASE WHEN strftime('%Y-%W', activity_date) = strftime('%Y-%W', 'now', 'localtime') THEN stars ELSE 0 END), 0) AS week_stars,
            COALESCE(SUM(CASE WHEN substr(activity_date, 1, 7) = strftime('%Y-%m', 'now', 'localtime') THEN stars ELSE 0 END), 0) AS month_stars
        FROM activities
        WHERE status IN ('ok', 'good', 'excellent')
    ";

    $row = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

    return [
        'total_earned' => (int) ($row['total_earned'] ?? 0),
        'today_stars' => (int) ($row['today_stars'] ?? 0),
        'week_stars' => (int) ($row['week_stars'] ?? 0),
        'month_stars' => (int) ($row['month_stars'] ?? 0),
    ];
}

function fetch_total_spent(PDO $db): int
{
    return (int) $db->query('SELECT COALESCE(SUM(cost), 0) FROM rewards')->fetchColumn();
}

function fetch_activities(PDO $db, int $limit = 30): array
{
    $stmt = $db->prepare('SELECT * FROM activities ORDER BY activity_date DESC, created_at DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_today_activities(PDO $db): array
{
    $stmt = $db->prepare("SELECT * FROM activities WHERE activity_date = date('now', 'localtime') ORDER BY created_at DESC, id DESC");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_activities_between(PDO $db, string $startDate, string $endDate): array
{
    $stmt = $db->prepare(
        'SELECT * FROM activities WHERE activity_date BETWEEN ? AND ? ORDER BY activity_date DESC, created_at DESC, id DESC'
    );
    $stmt->execute([$startDate, $endDate]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function upsert_holiday(PDO $db, string $date, string $type, string $note): int
{
    $stmt = $db->prepare(
        'INSERT INTO holidays(holiday_date, type, note) VALUES(?, ?, ?)
         ON CONFLICT(holiday_date) DO UPDATE SET type = excluded.type, note = excluded.note'
    );
    $stmt->execute([$date, $type, $note]);

    $id = (int) $db->lastInsertId();
    if ($id > 0) {
        return $id;
    }

    $find = $db->prepare('SELECT id FROM holidays WHERE holiday_date = ?');
    $find->execute([$date]);

    return (int) $find->fetchColumn();
}

function update_holiday(PDO $db, int $id, string $type, string $note): void
{
    $stmt = $db->prepare('UPDATE holidays SET type = ?, note = ? WHERE id = ?');
    $stmt->execute([$type, $note, $id]);
}

function delete_holiday(PDO $db, int $id): void
{
    $stmt = $db->prepare('DELETE FROM holidays WHERE id = ?');
    $stmt->execute([$id]);
}

function find_holiday(PDO $db, int $id): ?array
{
    $stmt = $db->prepare('SELECT * FROM holidays WHERE id = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function find_holiday_by_date(PDO $db, string $date): ?array
{
    $stmt = $db->prepare('SELECT * FROM holidays WHERE holiday_date = ?');
    $stmt->execute([$date]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function fetch_holidays(PDO $db, int $limit = 60): array
{
    $stmt = $db->prepare('SELECT * FROM holidays ORDER BY holiday_date DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_holidays_between(PDO $db, string $startDate, string $endDate): array
{
    $stmt = $db->prepare('SELECT * FROM holidays WHERE holiday_date BETWEEN ? AND ? ORDER BY holiday_date DESC');
    $stmt->execute([$startDate, $endDate]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insert_weekly_goal(
    PDO $db,
    string $weekStart,
    string $title,
    int $dailyTarget,
    int $targetAmount,
    string $unitLabel,
    string $note
): int {
    $stmt = $db->prepare(
        'INSERT INTO weekly_goals(week_start, title, daily_target, target_amount, unit_label, note) VALUES(?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$weekStart, $title, $dailyTarget, $targetAmount, $unitLabel, $note]);

    return (int) $db->lastInsertId();
}

function find_weekly_goal(PDO $db, int $id): ?array
{
    $stmt = $db->prepare('SELECT * FROM weekly_goals WHERE id = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function update_weekly_goal_progress(PDO $db, int $id, int $progressAmount): void
{
    $stmt = $db->prepare('UPDATE weekly_goals SET progress_amount = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
    $stmt->execute([$progressAmount, $id]);
}

function delete_weekly_goal(PDO $db, int $id): void
{
    $stmt = $db->prepare('DELETE FROM weekly_goals WHERE id = ?');
    $stmt->execute([$id]);
}

function fetch_weekly_goals(PDO $db, string $weekStart): array
{
    $stmt = $db->prepare('SELECT * FROM weekly_goals WHERE week_start = ? ORDER BY id DESC');
    $stmt->execute([$weekStart]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_child_profile(PDO $db): array
{
    $row = $db->query('SELECT * FROM child_profiles WHERE id = 1')->fetch(PDO::FETCH_ASSOC);

    return $row ?: [];
}

function upsert_child_profile(
    PDO $db,
    string $nickname,
    string $fullName,
    string $birthday,
    string $className,
    string $favoriteSubject,
    string $hobby,
    string $profileNote
): void {
    $stmt = $db->prepare(
        'INSERT INTO child_profiles(id, nickname, full_name, birthday, class_name, favorite_subject, hobby, profile_note, updated_at)
         VALUES(1, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
         ON CONFLICT(id) DO UPDATE SET
            nickname = excluded.nickname,
            full_name = excluded.full_name,
            birthday = excluded.birthday,
            class_name = excluded.class_name,
            favorite_subject = excluded.favorite_subject,
            hobby = excluded.hobby,
            profile_note = excluded.profile_note,
            updated_at = CURRENT_TIMESTAMP'
    );
    $stmt->execute([$nickname, $fullName, $birthday, $className, $favoriteSubject, $hobby, $profileNote]);
}

function fetch_rewards(PDO $db, int $limit = 30): array
{
    $stmt = $db->prepare('SELECT * FROM rewards ORDER BY reward_date DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insert_audit_log(
    PDO $db,
    string $actor,
    string $action,
    string $entityType,
    ?int $entityId,
    string $description
): void
{
    $stmt = $db->prepare(
        'INSERT INTO audit_logs(actor, action, entity_type, entity_id, description) VALUES(?, ?, ?, ?, ?)'
    );
    $stmt->execute([$actor, $action, $entityType, $entityId, $description]);
}

function fetch_audit_logs(PDO $db, int $limit = 50): array
{
    $stmt = $db->prepare('SELECT * FROM audit_logs ORDER BY id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
