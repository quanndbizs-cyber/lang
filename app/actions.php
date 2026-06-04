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
    if ($action === 'child_login') {
        handle_child_login($config);
    }
    if ($action === 'child_logout') {
        handle_child_logout();
    }
    if ($action === 'add_daily') {
        require_child_or_parent_login();
        handle_add_daily($db, $config);
    }
    if ($action === 'add_single') {
        require_child_or_parent_login();
        handle_add_single($db, $config);
    }
    if ($action === 'add_reward') {
        require_child_or_parent_login();
        handle_add_reward($db, $config);
    }
    if ($action === 'add_quick_action') {
        require_child_or_parent_login();
        handle_add_quick_action($db, $config);
    }
    if ($action === 'update_child_activity') {
        require_child_or_parent_login();
        handle_update_child_activity($db);
    }
    if ($action === 'delete_activity') {
        require_parent_login();
        handle_delete_activity($db);
    }
    if ($action === 'update_activity_parent_feedback') {
        require_parent_login();
        handle_update_activity_parent_feedback($db, $config);
    }
    if ($action === 'update_activity_parent_edit') {
        require_parent_login();
        handle_update_activity_parent_edit($db);
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

function handle_child_login(array $config): void
{
    $password = (string) ($_POST['child_password'] ?? '');

    if (verify_child_password($password, $config)) {
        session_regenerate_id(true);
        $_SESSION['child_logged_in'] = true;
        $_SESSION['msg'] = 'Con đã đăng nhập.';
    } else {
        $_SESSION['msg'] = 'Mật khẩu của con chưa đúng.';
    }

    redirect_home();
}

function handle_child_logout(): void
{
    unset($_SESSION['child_logged_in']);
    $_SESSION['msg'] = 'Con đã đăng xuất.';

    redirect_home();
}

function handle_add_daily(PDO $db, array $config): void
{
    $date = require_today_date($_POST['activity_date'] ?? '', 'ngày ghi nhận');
    $note = trim($_POST['note'] ?? '');
    $selectedActivities = [];
    foreach (($_POST['activities'] ?? []) as $key) {
        if (isset($config['activity_options'][$key])) {
            $selectedActivities[] = $key;
        }
    }

    $screen = (int) ($_POST['screen_minutes'] ?? 0);
    $newActivityCount = count($selectedActivities);
    if (isset($config['penalty_options'][$screen]) && $config['penalty_options'][$screen][1] !== 0) {
        $newActivityCount++;
    }
    ensure_activity_daily_limit($db, $date, $newActivityCount);

    $imagePath = save_uploaded_image('image', $config);
    $count = 0;
    $total = 0;

    foreach ($selectedActivities as $key) {
        [$title, $stars, $category] = $config['activity_options'][$key] + [null, null, 'other'];
        $activityId = insert_activity($db, $date, $title, $category, $stars, $note, $imagePath);
        insert_audit_log($db, 'Gia đình', 'created', 'activity', $activityId, "Thêm hoạt động {$title} ({$stars}★) ngày {$date}.");
        $count++;
        $total += $stars;
        $imagePath = null;
    }

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
    $date = require_today_date($_POST['single_date'] ?? '', 'ngày hoạt động');
    $title = trim($_POST['single_title'] ?? '');
    $category = sanitize_activity_category($_POST['single_category'] ?? 'other', $config);
    $stars = (int) ($_POST['single_stars'] ?? 0);
    $note = trim($_POST['single_note'] ?? '');

    if ($title !== '') {
        ensure_activity_daily_limit($db, $date, 1);
        $imagePath = save_uploaded_image('single_image', $config);
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
    $date = require_today_date($_POST['reward_date'] ?? '', 'ngày đổi thưởng');
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
    $activityKey = $_POST['quick_activity_option'] ?? '';
    $penaltyKey = (int) ($_POST['penalty_activity'] ?? 0);
    $category = sanitize_activity_category($_POST['quick_activity_category'] ?? 'other', $config);
    $date = require_today_date($_POST['quick_date'] ?? '', 'ngày ghi nhanh');
    $note = trim($_POST['quick_note'] ?? '');
    $newActivityCount = ($activityKey !== '') ? 1 : 0;
    if ($penaltyKey !== 0) {
        $newActivityCount++;
    }

    if ($activityKey !== '') {
        if (!isset($config['activity_options'][$activityKey])) {
            $_SESSION['msg'] = 'Không tìm thấy hoạt động đã chọn.';
            redirect_home();
        }

        [, , $optionCategory] = $config['activity_options'][$activityKey] + [null, null, 'other'];
        if ($optionCategory !== $category) {
            $_SESSION['msg'] = 'Hoạt động không thuộc loại đã chọn.';
            redirect_home();
        }
    }

    if ($penaltyKey !== 0 && !isset($config['penalty_options'][$penaltyKey])) {
        $_SESSION['msg'] = 'Không tìm thấy mục trừ sao đã chọn.';
        redirect_home();
    }

    ensure_activity_daily_limit($db, $date, $newActivityCount);

    if (empty($_FILES['quick_image']['name']) || !is_uploaded_file($_FILES['quick_image']['tmp_name'])) {
        $_SESSION['msg'] = 'Ghi nhanh cần có ảnh minh chứng. Vui lòng upload ảnh trước khi lưu.';
        redirect_home();
    }

    $imagePath = save_uploaded_image('quick_image', $config);
    $count = 0;
    $total = 0;

    if ($activityKey !== '') {
        [$title, $stars, $optionCategory] = $config['activity_options'][$activityKey] + [null, null, 'other'];
        $activityId = insert_activity($db, $date, $title, $optionCategory, $stars, $note, $imagePath);
        insert_audit_log($db, 'Gia đình', 'created', 'activity', $activityId, "Ghi nhanh {$title} ({$stars}★) ngày {$date}.");
        $count++;
        $total += $stars;
        $imagePath = null;
    }

    if ($penaltyKey !== 0) {
        [$title, $stars, $penaltyCategory] = $config['penalty_options'][$penaltyKey] + [null, null, 'screen_penalty'];
        $activityId = insert_activity($db, $date, $title, $penaltyCategory, $stars, $note, $imagePath);
        insert_audit_log($db, 'Gia đình', 'created', 'activity', $activityId, "Ghi nhanh {$title} ({$stars}★) ngày {$date}.");
        $count++;
        $total += $stars;
    }

    if ($count === 0) {
        $_SESSION['msg'] = 'Vui lòng chọn hoạt động hoặc mục trừ sao.';
        redirect_home();
    }

    $sign = $total > 0 ? '+' : '';
    $_SESSION['msg'] = "Đã ghi nhanh {$count} mục ({$sign}{$total}★).";

    redirect_home();
}

function handle_update_child_activity(PDO $db): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $title = trim($_POST['child_title'] ?? '');
    $note = trim($_POST['child_note'] ?? '');
    $activity = find_activity($db, $id);

    if (!$activity) {
        $_SESSION['msg'] = 'Không tìm thấy hoạt động để sửa.';
        redirect_home();
    }

    if (($activity['activity_date'] ?? '') !== date('Y-m-d')) {
        $_SESSION['msg'] = 'Chỉ được sửa task đã nhập trong ngày hiện tại.';
        redirect_home();
    }

    if ($title === '') {
        $_SESSION['msg'] = 'Vui lòng nhập nội dung task.';
        redirect_home();
    }

    update_child_activity_content($db, $id, $title, $note);
    insert_audit_log($db, 'Con', 'updated', 'activity', $id, "Sửa nội dung task {$title} ngày {$activity['activity_date']}.");
    $_SESSION['msg'] = 'Đã cập nhật nội dung task.';

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

function handle_update_activity_parent_feedback(PDO $db, array $config): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $liked = isset($_POST['parent_liked']);
    $comment = trim($_POST['parent_comment'] ?? '');
    $status = sanitize_parent_review_status($_POST['parent_status'] ?? 'pending');
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

    update_activity_parent_feedback($db, $id, $liked, $comment, $status);
    insert_audit_log($db, 'Bố mẹ', 'updated', 'activity', $id, "Cập nhật phản hồi và trạng thái " . get_parent_review_status_label($status) . " cho hoạt động {$activity['title']}.");

    if (is_ajax_request()) {
        $dashboard = build_dashboard_stats(
            fetch_activity_totals($db),
            fetch_total_spent($db),
            $config['reward_options']
        );

        json_response([
            'ok' => true,
            'message' => 'Đã lưu phản hồi của bố mẹ.',
            'id' => $id,
            'liked' => $liked,
            'comment' => $comment,
            'status' => $status,
            'status_label' => get_parent_review_status_label($status),
            'display_text' => ($liked ? '❤️ ' : '') . $comment,
            'dashboard' => $dashboard,
        ]);
    }

    $_SESSION['msg'] = 'Đã lưu phản hồi của bố mẹ.';
    redirect_home();
}

function handle_update_activity_parent_edit(PDO $db): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $title = trim($_POST['parent_edit_title'] ?? '');
    $note = trim($_POST['parent_edit_note'] ?? '');
    $activity = find_activity($db, $id);

    if (!$activity) {
        $_SESSION['msg'] = 'Không tìm thấy hoạt động để sửa.';
        redirect_home();
    }

    if ($title === '') {
        $_SESSION['msg'] = 'Vui lòng nhập nội dung hoạt động.';
        redirect_home();
    }

    update_child_activity_content($db, $id, $title, $note);
    insert_audit_log($db, 'Bố mẹ', 'updated', 'activity', $id, "Sửa hoạt động {$title} ngày {$activity['activity_date']}.");
    $_SESSION['msg'] = 'Bố mẹ đã cập nhật hoạt động.';

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
