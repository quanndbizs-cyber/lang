<?php

function handle_request(PDO $db, array $config): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'parent_login') {
        handle_parent_login($config);
    }
    if ($action === 'parent_logout') {
        handle_parent_logout();
    }
    if ($action === 'add_daily') {
        handle_add_daily($db, $config);
    }
    if ($action === 'add_single') {
        handle_add_single($db, $config);
    }
    if ($action === 'add_reward') {
        handle_add_reward($db, $config);
    }
    if ($action === 'add_quick_action') {
        handle_add_quick_action($db, $config);
    }
    if ($action === 'delete_activity') {
        require_parent_login();
        handle_delete_activity($db);
    }
    if ($action === 'update_activity_parent_feedback') {
        require_parent_login();
        handle_update_activity_parent_feedback($db);
    }
    if ($action === 'delete_reward') {
        require_parent_login();
        handle_delete_reward($db);
    }
}

function handle_parent_login(array $config): void
{
    $password = (string) ($_POST['parent_password'] ?? '');

    if (verify_parent_password($password, $config)) {
        session_regenerate_id(true);
        $_SESSION['parent_logged_in'] = true;
        $_SESSION['msg'] = 'Bố mẹ đã đăng nhập.';
    } else {
        $_SESSION['msg'] = 'Mật khẩu bố mẹ chưa đúng.';
    }

    redirect_home();
}

function handle_parent_logout(): void
{
    unset($_SESSION['parent_logged_in']);
    $_SESSION['msg'] = 'Bố mẹ đã đăng xuất.';

    redirect_home();
}

function handle_add_daily(PDO $db, array $config): void
{
    $date = $_POST['activity_date'] ?: date('Y-m-d');
    $note = trim($_POST['note'] ?? '');
    $imagePath = save_uploaded_image('image', $config);
    $count = 0;
    $total = 0;

    foreach (($_POST['activities'] ?? []) as $key) {
        if (!isset($config['activity_options'][$key])) {
            continue;
        }

        [$title, $stars, $category] = $config['activity_options'][$key] + [null, null, 'other'];
        $activityId = insert_activity($db, $date, $title, $category, $stars, $note, $imagePath);
        insert_audit_log($db, 'Gia đình', 'created', 'activity', $activityId, "Thêm hoạt động {$title} ({$stars}★) ngày {$date}.");
        $count++;
        $total += $stars;
        $imagePath = null;
    }

    $screen = (int) ($_POST['screen_minutes'] ?? 0);
    if (isset($config['penalty_options'][$screen]) && $config['penalty_options'][$screen][1] !== 0) {
        [$title, $stars, $category] = $config['penalty_options'][$screen] + [null, null, 'screen_penalty'];
        $activityId = insert_activity($db, $date, $title, $category, $stars, $note, $imagePath);
        insert_audit_log($db, 'Gia đình', 'created', 'activity', $activityId, "Thêm hoạt động {$title} ({$stars}★) ngày {$date}.");
        $count++;
        $total += $stars;
    }

    $_SESSION['msg'] = $count > 0 ? "Đã ghi nhận $count mục, tổng {$total}★." : 'Chưa chọn hoạt động nào.';
    redirect_home();
}

function handle_add_single(PDO $db, array $config): void
{
    $date = $_POST['single_date'] ?: date('Y-m-d');
    $title = trim($_POST['single_title'] ?? '');
    $category = sanitize_activity_category($_POST['single_category'] ?? 'other', $config);
    $stars = (int) ($_POST['single_stars'] ?? 0);
    $note = trim($_POST['single_note'] ?? '');
    $imagePath = save_uploaded_image('single_image', $config);

    if ($title !== '') {
        $activityId = insert_activity($db, $date, $title, $category, $stars, $note, $imagePath);
        insert_audit_log($db, 'Gia đình', 'created', 'activity', $activityId, "Thêm hoạt động {$title} ({$stars}★) ngày {$date}.");
        $_SESSION['msg'] = 'Đã ghi nhận mục bổ sung.';
    } else {
        $_SESSION['msg'] = 'Vui lòng nhập tên hoạt động.';
    }

    redirect_home();
}

function handle_add_reward(PDO $db, array $config): void
{
    $date = $_POST['reward_date'] ?: date('Y-m-d');
    $title = trim($_POST['reward_title'] ?? '');
    $rewardOptions = $config['reward_options'];
    $cost = (int) ($rewardOptions[$title] ?? 0);
    $note = trim($_POST['reward_note'] ?? '');

    if ($title === '' || $cost <= 0) {
        $_SESSION['msg'] = 'Vui lòng chọn phần thưởng hợp lệ.';
        redirect_home();
    }

    $activityTotals = fetch_activity_totals($db);
    $currentStars = $activityTotals['total_earned'] - fetch_total_spent($db);

    if ($currentStars < $cost) {
        $missingStars = $cost - $currentStars;
        $_SESSION['msg'] = "Chưa đủ sao để đổi {$title}. Cần thêm {$missingStars}★ nữa.";
        redirect_home();
    }

    $rewardId = insert_reward($db, $date, $title, $cost, $note);
    insert_audit_log($db, 'Gia đình', 'created', 'reward', $rewardId, "Đổi thưởng {$title} (-{$cost}★) ngày {$date}.");
    $_SESSION['msg'] = "Đã đổi {$title} và trừ {$cost}★.";

    redirect_home();
}

function handle_add_quick_action(PDO $db, array $config): void
{
    $quickActionKey = $_POST['quick_action'] ?? '';
    $date = $_POST['quick_date'] ?: date('Y-m-d');
    $imagePath = save_uploaded_image('quick_image', $config);

    if (!isset($config['quick_actions'][$quickActionKey])) {
        $_SESSION['msg'] = 'Không tìm thấy hành động nhanh.';
        redirect_home();
    }

    [$title, $stars, $category] = $config['quick_actions'][$quickActionKey] + [null, null, 'other'];
    $activityId = insert_activity($db, $date, $title, $category, $stars, '', $imagePath);
    insert_audit_log($db, 'Gia đình', 'created', 'activity', $activityId, "Ghi nhanh {$title} ({$stars}★) ngày {$date}.");

    $sign = $stars > 0 ? '+' : '';
    $_SESSION['msg'] = "Đã ghi nhanh {$title} ({$sign}{$stars}★).";
    if ($imagePath === null) {
        $_SESSION['msg'] .= ' Lưu ý: chưa upload ảnh minh chứng.';
    }

    redirect_home();
}

function handle_delete_activity(PDO $db): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $activity = find_activity($db, $id);
    $imagePath = $activity['image_path'] ?? null;

    if ($imagePath) {
        @unlink(__DIR__ . '/../public/' . $imagePath);
    }

    delete_activity($db, $id);
    if ($activity) {
        insert_audit_log($db, 'Gia đình', 'deleted', 'activity', $id, "Xóa hoạt động {$activity['title']} ({$activity['stars']}★) ngày {$activity['activity_date']}.");
    }
    redirect_home();
}

function handle_update_activity_parent_feedback(PDO $db): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $liked = isset($_POST['parent_liked']);
    $comment = trim($_POST['parent_comment'] ?? '');
    $activity = find_activity($db, $id);

    if (!$activity) {
        if (is_ajax_request()) {
            json_response([
                'ok' => false,
                'message' => 'Không tìm thấy hoạt động để phản hồi.',
            ], 404);
        }

        $_SESSION['msg'] = 'Không tìm thấy hoạt động để phản hồi.';
        redirect_home();
    }

    update_activity_parent_feedback($db, $id, $liked, $comment);
    insert_audit_log($db, 'Bố mẹ', 'updated', 'activity', $id, "Cập nhật phản hồi cho hoạt động {$activity['title']}.");

    if (is_ajax_request()) {
        json_response([
            'ok' => true,
            'message' => 'Đã lưu phản hồi của bố mẹ.',
            'id' => $id,
            'liked' => $liked,
            'comment' => $comment,
            'display_text' => ($liked ? '❤️ ' : '') . $comment,
        ]);
    }

    $_SESSION['msg'] = 'Đã lưu phản hồi của bố mẹ.';
    redirect_home();
}

function handle_delete_reward(PDO $db): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $reward = find_reward($db, $id);
    delete_reward($db, $id);
    if ($reward) {
        insert_audit_log($db, 'Gia đình', 'deleted', 'reward', $id, "Xóa đổi thưởng {$reward['title']} (-{$reward['cost']}★) ngày {$reward['reward_date']}.");
    }
    redirect_home();
}
