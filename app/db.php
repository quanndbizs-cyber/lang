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

    initialize_database($db, $config);

    return $db;
}

function initialize_database(PDO $db, array $config): void
{
    $db->exec(
        "CREATE TABLE IF NOT EXISTS families (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            family_name TEXT NOT NULL,
            parent_name TEXT,
            parent_username TEXT NOT NULL UNIQUE,
            parent_password TEXT NOT NULL,
            note TEXT,
            active INTEGER NOT NULL DEFAULT 1,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $db->exec(
        "CREATE TABLE IF NOT EXISTS children (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            family_id INTEGER NOT NULL,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            nickname TEXT,
            full_name TEXT,
            birthday TEXT,
            class_name TEXT,
            favorite_subject TEXT,
            hobby TEXT,
            profile_note TEXT,
            active INTEGER NOT NULL DEFAULT 1,
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

    ensure_default_family_and_child($db, $config);
    $defaultFamilyId = get_default_family_id($db);
    $defaultChildId = get_default_child_id($db, $defaultFamilyId);

    $db->exec(
        "CREATE TABLE IF NOT EXISTS activities (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            family_id INTEGER,
            child_id INTEGER,
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
    if (!in_array('family_id', $columnNames, true)) {
        $db->exec("ALTER TABLE activities ADD COLUMN family_id INTEGER");
    }
    if (!in_array('child_id', $columnNames, true)) {
        $db->exec("ALTER TABLE activities ADD COLUMN child_id INTEGER");
    }
    $db->exec("UPDATE activities SET family_id = {$defaultFamilyId} WHERE family_id IS NULL");
    $db->exec("UPDATE activities SET child_id = {$defaultChildId} WHERE child_id IS NULL");
    $db->exec("UPDATE activities SET status = 'ok' WHERE status = 'approved'");

    $db->exec(
        "CREATE TABLE IF NOT EXISTS rewards (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            family_id INTEGER,
            child_id INTEGER,
            reward_date TEXT NOT NULL,
            title TEXT NOT NULL,
            cost INTEGER NOT NULL,
            note TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );
    $rewardColumns = $db->query('PRAGMA table_info(rewards)')->fetchAll(PDO::FETCH_ASSOC);
    $rewardColumnNames = array_column($rewardColumns, 'name');
    if (!in_array('family_id', $rewardColumnNames, true)) {
        $db->exec("ALTER TABLE rewards ADD COLUMN family_id INTEGER");
    }
    if (!in_array('child_id', $rewardColumnNames, true)) {
        $db->exec("ALTER TABLE rewards ADD COLUMN child_id INTEGER");
    }
    $db->exec("UPDATE rewards SET family_id = {$defaultFamilyId} WHERE family_id IS NULL");
    $db->exec("UPDATE rewards SET child_id = {$defaultChildId} WHERE child_id IS NULL");

    $db->exec(
        "CREATE TABLE IF NOT EXISTS audit_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            family_id INTEGER,
            child_id INTEGER,
            actor TEXT NOT NULL,
            action TEXT NOT NULL,
            entity_type TEXT NOT NULL,
            entity_id INTEGER,
            description TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )"
    );
    $auditColumns = $db->query('PRAGMA table_info(audit_logs)')->fetchAll(PDO::FETCH_ASSOC);
    $auditColumnNames = array_column($auditColumns, 'name');
    if (!in_array('family_id', $auditColumnNames, true)) {
        $db->exec("ALTER TABLE audit_logs ADD COLUMN family_id INTEGER");
    }
    if (!in_array('child_id', $auditColumnNames, true)) {
        $db->exec("ALTER TABLE audit_logs ADD COLUMN child_id INTEGER");
    }
    $db->exec("UPDATE audit_logs SET family_id = {$defaultFamilyId} WHERE family_id IS NULL");
    $db->exec("UPDATE audit_logs SET child_id = {$defaultChildId} WHERE child_id IS NULL");

    $db->exec(
        "CREATE TABLE IF NOT EXISTS holidays (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            family_id INTEGER,
            holiday_date TEXT NOT NULL,
            type TEXT NOT NULL,
            note TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(family_id, holiday_date)
        )"
    );
    $holidayTableSql = (string) $db->query("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = 'holidays'")->fetchColumn();
    if (str_contains($holidayTableSql, 'holiday_date TEXT NOT NULL UNIQUE')) {
        $db->exec('ALTER TABLE holidays RENAME TO holidays_old_global_unique');
        $db->exec(
            "CREATE TABLE holidays (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                family_id INTEGER,
                holiday_date TEXT NOT NULL,
                type TEXT NOT NULL,
                note TEXT,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(family_id, holiday_date)
            )"
        );
        $db->exec(
            "INSERT INTO holidays(id, family_id, holiday_date, type, note, created_at)
             SELECT id, COALESCE(family_id, {$defaultFamilyId}), holiday_date, type, note, created_at
             FROM holidays_old_global_unique"
        );
        $db->exec('DROP TABLE holidays_old_global_unique');
    }
    $holidayColumns = $db->query('PRAGMA table_info(holidays)')->fetchAll(PDO::FETCH_ASSOC);
    $holidayColumnNames = array_column($holidayColumns, 'name');
    if (!in_array('family_id', $holidayColumnNames, true)) {
        $db->exec("ALTER TABLE holidays ADD COLUMN family_id INTEGER");
    }
    $db->exec("UPDATE holidays SET family_id = {$defaultFamilyId} WHERE family_id IS NULL");

    $db->exec(
        "CREATE TABLE IF NOT EXISTS weekly_goals (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            family_id INTEGER,
            child_id INTEGER,
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
    $goalColumns = $db->query('PRAGMA table_info(weekly_goals)')->fetchAll(PDO::FETCH_ASSOC);
    $goalColumnNames = array_column($goalColumns, 'name');
    if (!in_array('family_id', $goalColumnNames, true)) {
        $db->exec("ALTER TABLE weekly_goals ADD COLUMN family_id INTEGER");
    }
    if (!in_array('child_id', $goalColumnNames, true)) {
        $db->exec("ALTER TABLE weekly_goals ADD COLUMN child_id INTEGER");
    }
    $db->exec("UPDATE weekly_goals SET family_id = {$defaultFamilyId} WHERE family_id IS NULL");
    $db->exec("UPDATE weekly_goals SET child_id = {$defaultChildId} WHERE child_id IS NULL");

}

function ensure_default_family_and_child(PDO $db, array $config): void
{
    $familyCount = (int) $db->query('SELECT COUNT(*) FROM families')->fetchColumn();
    if ($familyCount === 0) {
        $stmt = $db->prepare(
            'INSERT INTO families(family_name, parent_name, parent_username, parent_password, note) VALUES(?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            'Gia đình mặc định',
            'Bố mẹ',
            'parent',
            (string) ($config['parent_password'] ?? '1234'),
            'Tài khoản mặc định được tạo từ cấu hình hiện có.',
        ]);
    }

    $defaultFamilyId = get_default_family_id($db);
    $childCount = (int) $db->query('SELECT COUNT(*) FROM children')->fetchColumn();
    if ($childCount === 0) {
        $profile = $db->query("SELECT * FROM child_profiles WHERE id = 1")->fetch(PDO::FETCH_ASSOC) ?: [];
        $stmt = $db->prepare(
            'INSERT INTO children(family_id, username, password, nickname, full_name, birthday, class_name, favorite_subject, hobby, profile_note)
             VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $defaultFamilyId,
            'child',
            (string) ($config['child_password'] ?? '1234'),
            (string) ($profile['nickname'] ?? ''),
            (string) ($profile['full_name'] ?? ''),
            (string) ($profile['birthday'] ?? ''),
            (string) ($profile['class_name'] ?? ''),
            (string) ($profile['favorite_subject'] ?? ''),
            (string) ($profile['hobby'] ?? ''),
            (string) ($profile['profile_note'] ?? ''),
        ]);
    }
}

function get_default_family_id(PDO $db): int
{
    return (int) $db->query('SELECT id FROM families ORDER BY id LIMIT 1')->fetchColumn();
}

function get_default_child_id(PDO $db, int $familyId): int
{
    $stmt = $db->prepare('SELECT id FROM children WHERE family_id = ? ORDER BY id LIMIT 1');
    $stmt->execute([$familyId]);

    return (int) $stmt->fetchColumn();
}

function find_family_by_parent_credentials(PDO $db, string $username, string $password): ?array
{
    $sql = 'SELECT * FROM families WHERE active = 1 AND parent_password = ?';
    $params = [$password];
    if ($username !== '') {
        $sql .= ' AND parent_username = ?';
        $params[] = $username;
    }
    $sql .= ' ORDER BY id LIMIT 1';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function find_child_by_credentials(PDO $db, string $username, string $password): ?array
{
    $sql = 'SELECT c.*, f.family_name FROM children c JOIN families f ON f.id = c.family_id WHERE c.active = 1 AND f.active = 1 AND c.password = ?';
    $params = [$password];
    if ($username !== '') {
        $sql .= ' AND c.username = ?';
        $params[] = $username;
    }
    $sql .= ' ORDER BY c.id LIMIT 1';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function find_family(PDO $db, int $id): ?array
{
    $stmt = $db->prepare('SELECT * FROM families WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function find_child(PDO $db, int $id): ?array
{
    $stmt = $db->prepare('SELECT c.*, f.family_name FROM children c JOIN families f ON f.id = c.family_id WHERE c.id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function fetch_families(PDO $db): array
{
    return $db->query('SELECT * FROM families ORDER BY active DESC, id DESC')->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_children(PDO $db, ?int $familyId = null): array
{
    if ($familyId !== null) {
        $stmt = $db->prepare('SELECT c.*, f.family_name FROM children c JOIN families f ON f.id = c.family_id WHERE c.family_id = ? ORDER BY c.active DESC, c.id DESC');
        $stmt->execute([$familyId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $db->query('SELECT c.*, f.family_name FROM children c JOIN families f ON f.id = c.family_id ORDER BY f.id DESC, c.active DESC, c.id DESC')->fetchAll(PDO::FETCH_ASSOC);
}

function insert_family(PDO $db, string $familyName, string $parentName, string $parentUsername, string $parentPassword, string $note): int
{
    $stmt = $db->prepare('INSERT INTO families(family_name, parent_name, parent_username, parent_password, note) VALUES(?, ?, ?, ?, ?)');
    $stmt->execute([$familyName, $parentName, $parentUsername, $parentPassword, $note]);

    return (int) $db->lastInsertId();
}

function insert_child_account(
    PDO $db,
    int $familyId,
    string $username,
    string $password,
    string $nickname,
    string $fullName
): int {
    $stmt = $db->prepare('INSERT INTO children(family_id, username, password, nickname, full_name) VALUES(?, ?, ?, ?, ?)');
    $stmt->execute([$familyId, $username, $password, $nickname, $fullName]);

    return (int) $db->lastInsertId();
}

function update_child_account_status(PDO $db, int $childId, bool $active): void
{
    $stmt = $db->prepare('UPDATE children SET active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
    $stmt->execute([$active ? 1 : 0, $childId]);
}

function insert_activity(
    PDO $db,
    int $familyId,
    int $childId,
    string $date,
    string $title,
    string $category,
    int $stars,
    string $note,
    ?string $imagePath
): int
{
    $stmt = $db->prepare(
        'INSERT INTO activities(family_id, child_id, activity_date, title, category, stars, note, image_path, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$familyId, $childId, $date, $title, $category, $stars, $note, $imagePath, 'pending']);

    return (int) $db->lastInsertId();
}

function insert_reward(PDO $db, int $familyId, int $childId, string $date, string $title, int $cost, string $note): int
{
    $stmt = $db->prepare('INSERT INTO rewards(family_id, child_id, reward_date, title, cost, note) VALUES(?, ?, ?, ?, ?, ?)');
    $stmt->execute([$familyId, $childId, $date, $title, $cost, $note]);

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

function count_activities_by_date(PDO $db, int $childId, string $date): int
{
    $stmt = $db->prepare('SELECT COUNT(*) FROM activities WHERE child_id = ? AND activity_date = ?');
    $stmt->execute([$childId, $date]);

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

function fetch_activity_totals(PDO $db, int $childId): array
{
    $sql = "
        SELECT
            COALESCE(SUM(stars), 0) AS total_earned,
            COALESCE(SUM(CASE WHEN activity_date = date('now', 'localtime') THEN stars ELSE 0 END), 0) AS today_stars,
            COALESCE(SUM(CASE WHEN strftime('%Y-%W', activity_date) = strftime('%Y-%W', 'now', 'localtime') THEN stars ELSE 0 END), 0) AS week_stars,
            COALESCE(SUM(CASE WHEN substr(activity_date, 1, 7) = strftime('%Y-%m', 'now', 'localtime') THEN stars ELSE 0 END), 0) AS month_stars
        FROM activities
        WHERE child_id = ? AND status IN ('ok', 'good', 'excellent')
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([$childId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'total_earned' => (int) ($row['total_earned'] ?? 0),
        'today_stars' => (int) ($row['today_stars'] ?? 0),
        'week_stars' => (int) ($row['week_stars'] ?? 0),
        'month_stars' => (int) ($row['month_stars'] ?? 0),
    ];
}

function fetch_total_spent(PDO $db, int $childId): int
{
    $stmt = $db->prepare('SELECT COALESCE(SUM(cost), 0) FROM rewards WHERE child_id = ?');
    $stmt->execute([$childId]);

    return (int) $stmt->fetchColumn();
}

function fetch_activities(PDO $db, int $childId, int $limit = 30): array
{
    $stmt = $db->prepare('SELECT * FROM activities WHERE child_id = ? ORDER BY activity_date DESC, created_at DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $childId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_today_activities(PDO $db, int $childId): array
{
    $stmt = $db->prepare("SELECT * FROM activities WHERE child_id = ? AND activity_date = date('now', 'localtime') ORDER BY created_at DESC, id DESC");
    $stmt->execute([$childId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_activities_between(PDO $db, int $childId, string $startDate, string $endDate): array
{
    $stmt = $db->prepare(
        'SELECT * FROM activities WHERE child_id = ? AND activity_date BETWEEN ? AND ? ORDER BY activity_date DESC, created_at DESC, id DESC'
    );
    $stmt->execute([$childId, $startDate, $endDate]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function upsert_holiday(PDO $db, int $familyId, string $date, string $type, string $note): int
{
    $stmt = $db->prepare(
        'INSERT INTO holidays(family_id, holiday_date, type, note) VALUES(?, ?, ?, ?)
         ON CONFLICT(family_id, holiday_date) DO UPDATE SET type = excluded.type, note = excluded.note'
    );
    $stmt->execute([$familyId, $date, $type, $note]);

    $id = (int) $db->lastInsertId();
    if ($id > 0) {
        return $id;
    }

    $find = $db->prepare('SELECT id FROM holidays WHERE family_id = ? AND holiday_date = ?');
    $find->execute([$familyId, $date]);

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

function find_holiday_by_date(PDO $db, int $familyId, string $date): ?array
{
    $stmt = $db->prepare('SELECT * FROM holidays WHERE family_id = ? AND holiday_date = ?');
    $stmt->execute([$familyId, $date]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

function fetch_holidays(PDO $db, int $familyId, int $limit = 60): array
{
    $stmt = $db->prepare('SELECT * FROM holidays WHERE family_id = ? ORDER BY holiday_date DESC LIMIT ?');
    $stmt->bindValue(1, $familyId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_holidays_between(PDO $db, int $familyId, string $startDate, string $endDate): array
{
    $stmt = $db->prepare('SELECT * FROM holidays WHERE family_id = ? AND holiday_date BETWEEN ? AND ? ORDER BY holiday_date DESC');
    $stmt->execute([$familyId, $startDate, $endDate]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insert_weekly_goal(
    PDO $db,
    int $familyId,
    int $childId,
    string $weekStart,
    string $title,
    int $dailyTarget,
    int $targetAmount,
    string $unitLabel,
    string $note
): int {
    $stmt = $db->prepare(
        'INSERT INTO weekly_goals(family_id, child_id, week_start, title, daily_target, target_amount, unit_label, note) VALUES(?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$familyId, $childId, $weekStart, $title, $dailyTarget, $targetAmount, $unitLabel, $note]);

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

function fetch_weekly_goals(PDO $db, int $childId, string $weekStart): array
{
    $stmt = $db->prepare('SELECT * FROM weekly_goals WHERE child_id = ? AND week_start = ? ORDER BY id DESC');
    $stmt->execute([$childId, $weekStart]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetch_child_profile(PDO $db, int $childId): array
{
    $stmt = $db->prepare('SELECT * FROM children WHERE id = ?');
    $stmt->execute([$childId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: [];
}

function upsert_child_profile(
    PDO $db,
    int $childId,
    string $nickname,
    string $fullName,
    string $birthday,
    string $className,
    string $favoriteSubject,
    string $hobby,
    string $profileNote
): void {
    $stmt = $db->prepare(
        'UPDATE children SET nickname = ?, full_name = ?, birthday = ?, class_name = ?, favorite_subject = ?, hobby = ?, profile_note = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?'
    );
    $stmt->execute([$nickname, $fullName, $birthday, $className, $favoriteSubject, $hobby, $profileNote, $childId]);
}

function fetch_rewards(PDO $db, int $childId, int $limit = 30): array
{
    $stmt = $db->prepare('SELECT * FROM rewards WHERE child_id = ? ORDER BY reward_date DESC, id DESC LIMIT ?');
    $stmt->bindValue(1, $childId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insert_audit_log(
    PDO $db,
    ?int $familyId,
    ?int $childId,
    string $actor,
    string $action,
    string $entityType,
    ?int $entityId,
    string $description
): void
{
    $stmt = $db->prepare(
        'INSERT INTO audit_logs(family_id, child_id, actor, action, entity_type, entity_id, description) VALUES(?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$familyId, $childId, $actor, $action, $entityType, $entityId, $description]);
}

function fetch_audit_logs(PDO $db, int $familyId, int $limit = 50): array
{
    $stmt = $db->prepare('SELECT * FROM audit_logs WHERE family_id = ? ORDER BY id DESC LIMIT ?');
    $stmt->bindValue(1, $familyId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
