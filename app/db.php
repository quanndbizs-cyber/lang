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
            status TEXT NOT NULL DEFAULT 'approved',
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $columns = $db->query('PRAGMA table_info(activities)')->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'name');
    if (!in_array('category', $columnNames, true)) {
        $db->exec("ALTER TABLE activities ADD COLUMN category TEXT NOT NULL DEFAULT 'other'");
    }

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
}

function insert_activity(
    PDO $db,
    string $date,
    string $title,
    string $category,
    int $stars,
    string $note,
    ?string $imagePath
): void
{
    $stmt = $db->prepare(
        'INSERT INTO activities(activity_date, title, category, stars, note, image_path, status) VALUES(?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$date, $title, $category, $stars, $note, $imagePath, 'approved']);
}

function insert_reward(PDO $db, string $date, string $title, int $cost, string $note): void
{
    $stmt = $db->prepare('INSERT INTO rewards(reward_date, title, cost, note) VALUES(?, ?, ?, ?)');
    $stmt->execute([$date, $title, $cost, $note]);
}

function find_activity_image_path(PDO $db, int $id): ?string
{
    $stmt = $db->prepare('SELECT image_path FROM activities WHERE id = ?');
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['image_path'] ?? null;
}

function delete_activity(PDO $db, int $id): void
{
    $stmt = $db->prepare('DELETE FROM activities WHERE id = ?');
    $stmt->execute([$id]);
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
        WHERE status = 'approved'
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

function fetch_activities(PDO $db, int $limit = 80): array
{
    $stmt = $db->prepare('SELECT * FROM activities ORDER BY activity_date DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_rewards(PDO $db, int $limit = 30): array
{
    $stmt = $db->prepare('SELECT * FROM rewards ORDER BY reward_date DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
