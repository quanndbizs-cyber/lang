<?php

function handle_request(PDO $db, array $config): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'parent_login') {
        handle_parent_login($db, $config);
    }
    if ($action === 'parent_logout') {
        handle_parent_logout();
    }
    if ($action === 'child_login') {
        handle_child_login($db, $config);
    }
    if ($action === 'child_logout') {
        handle_child_logout();
    }
    if ($action === 'admin_login') {
        handle_admin_login($config);
    }
    if ($action === 'admin_logout') {
        handle_admin_logout();
    }
    if ($action === 'switch_child_dashboard') {
        require_parent_login();
        handle_switch_child_dashboard($db);
    }
    if ($action === 'add_family') {
        require_admin_login();
        handle_add_family($db);
    }
    if ($action === 'add_child_account') {
        require_admin_login();
        handle_add_child_account($db);
    }
    if ($action === 'update_child_account_status') {
        require_admin_login();
        handle_update_child_account_status($db);
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
    if ($action === 'update_child_profile') {
        require_child_or_parent_login();
        handle_update_child_profile($db);
    }
    if ($action === 'add_weekly_goal') {
        require_child_or_parent_login();
        handle_add_weekly_goal($db);
    }
    if ($action === 'update_weekly_goal_progress') {
        require_child_or_parent_login();
        handle_update_weekly_goal_progress($db);
    }
    if ($action === 'delete_weekly_goal') {
        require_parent_login();
        handle_delete_weekly_goal($db);
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
    if ($action === 'add_holiday') {
        require_parent_login();
        handle_add_holiday($db, $config);
    }
    if ($action === 'update_holiday') {
        require_parent_login();
        handle_update_holiday($db, $config);
    }
    if ($action === 'delete_holiday') {
        require_parent_login();
        handle_delete_holiday($db);
    }
    if ($action === 'delete_reward') {
        require_parent_login();
        handle_delete_reward($db);
    }
}

function handle_parent_login(PDO $db, array $config): void
{
    $username = trim((string) ($_POST['parent_username'] ?? ''));
    $password = (string) ($_POST['parent_password'] ?? '');
    $family = find_family_by_parent_credentials($db, $username, $password);

    if ($family || verify_parent_password($password, $config)) {
        $familyId = $family ? (int) $family['id'] : get_default_family_id($db);
        $childId = get_default_child_id($db, $familyId);
        session_regenerate_id(true);
        $_SESSION['parent_logged_in'] = true;
        $_SESSION['family_id'] = $familyId;
        $_SESSION['child_id'] = $childId;
        $_SESSION['msg'] = 'Bố mẹ đã đăng nhập.';
    } else {
        $_SESSION['msg'] = 'Tài khoản hoặc mật khẩu bố mẹ chưa đúng.';
    }

    redirect_home();
}

function handle_parent_logout(): void
{
    unset($_SESSION['parent_logged_in'], $_SESSION['family_id'], $_SESSION['child_id']);
    $_SESSION['msg'] = 'Bố mẹ đã đăng xuất.';

    redirect_home();
}

function handle_child_login(PDO $db, array $config): void
{
    $username = trim((string) ($_POST['child_username'] ?? ''));
    $password = (string) ($_POST['child_password'] ?? '');
    $child = find_child_by_credentials($db, $username, $password);

    if ($child || verify_child_password($password, $config)) {
        session_regenerate_id(true);
        $_SESSION['child_logged_in'] = true;
        if ($child) {
            set_active_child_session($child);
        } else {
            $_SESSION['family_id'] = get_default_family_id($db);
            $_SESSION['child_id'] = get_default_child_id($db, (int) $_SESSION['family_id']);
        }
        $_SESSION['msg'] = 'Con đã đăng nhập.';
    } else {
        $_SESSION['msg'] = 'Tài khoản hoặc mật khẩu của con chưa đúng.';
    }

    redirect_home();
}

function handle_child_logout(): void
{
    unset($_SESSION['child_logged_in'], $_SESSION['family_id'], $_SESSION['child_id']);
    $_SESSION['msg'] = 'Con đã đăng xuất.';

    redirect_home();
}

function handle_admin_login(array $config): void
{
    $password = (string) ($_POST['admin_password'] ?? '');

    if (verify_admin_password($password, $config)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['msg'] = 'Người quản trị đã đăng nhập.';
    } else {
        $_SESSION['msg'] = 'Mật khẩu quản trị chưa đúng.';
    }

    redirect_home();
}

function handle_admin_logout(): void
{
    unset($_SESSION['admin_logged_in']);
    $_SESSION['msg'] = 'Người quản trị đã đăng xuất.';

    redirect_home();
}

function handle_switch_child_dashboard(PDO $db): void
{
    $childId = (int) ($_POST['child_id'] ?? 0);
    $child = find_child($db, $childId);

    if (!$child) {
        $_SESSION['msg'] = 'Không tìm thấy tài khoản con để chuyển dashboard.';
        redirect_home();
    }

    if (!is_admin_logged_in() && (int) $child['family_id'] !== get_current_family_id($db)) {
        $_SESSION['msg'] = 'Không thể chuyển sang dashboard của gia đình khác.';
        redirect_home();
    }

    set_active_child_session($child);
    $_SESSION['msg'] = 'Đã chuyển dashboard sang ' . get_child_display_name($child) . '.';
    redirect_home();
}

function handle_add_family(PDO $db): void
{
    $familyName = trim((string) ($_POST['family_name'] ?? ''));
    $parentName = trim((string) ($_POST['parent_name'] ?? ''));
    $parentUsername = trim((string) ($_POST['parent_username'] ?? ''));
    $parentPassword = (string) ($_POST['parent_password_new'] ?? '');
    $note = trim((string) ($_POST['family_note'] ?? ''));

    if ($familyName === '' || $parentUsername === '' || $parentPassword === '') {
        $_SESSION['msg'] = 'Vui lòng nhập đủ tên gia đình, tài khoản và mật khẩu bố mẹ.';
        redirect_home();
    }

    try {
        $familyId = insert_family($db, $familyName, $parentName, $parentUsername, $parentPassword, $note);
        insert_audit_log($db, $familyId, null, 'Quản trị', 'created', 'family', $familyId, "Tạo đăng ký gia đình {$familyName}.");
        $_SESSION['msg'] = 'Đã tạo đăng ký gia đình.';
    } catch (Throwable $error) {
        $_SESSION['msg'] = 'Không tạo được gia đình. Tài khoản bố mẹ có thể đã tồn tại.';
    }

    redirect_home();
}

function handle_add_child_account(PDO $db): void
{
    $familyId = (int) ($_POST['child_family_id'] ?? 0);
    $username = trim((string) ($_POST['child_username_new'] ?? ''));
    $password = (string) ($_POST['child_password_new'] ?? '');
    $nickname = trim((string) ($_POST['child_nickname_new'] ?? ''));
    $fullName = trim((string) ($_POST['child_full_name_new'] ?? ''));
    $family = find_family($db, $familyId);

    if (!$family || $username === '' || $password === '') {
        $_SESSION['msg'] = 'Vui lòng chọn gia đình và nhập tài khoản/mật khẩu cho con.';
        redirect_home();
    }

    try {
        $childId = insert_child_account($db, $familyId, $username, $password, $nickname, $fullName);
        insert_audit_log($db, $familyId, $childId, 'Quản trị', 'created', 'child_account', $childId, "Tạo tài khoản con {$username} cho {$family['family_name']}.");
        $_SESSION['msg'] = 'Đã tạo tài khoản con.';
    } catch (Throwable $error) {
        $_SESSION['msg'] = 'Không tạo được tài khoản con. Username có thể đã tồn tại.';
    }

    redirect_home();
}

function handle_update_child_account_status(PDO $db): void
{
    $childId = (int) ($_POST['child_id'] ?? 0);
    $active = (int) ($_POST['child_active'] ?? 0) === 1;
    $child = find_child($db, $childId);

    if (!$child) {
        $_SESSION['msg'] = 'Không tìm thấy tài khoản con.';
        redirect_home();
    }

    update_child_account_status($db, $childId, $active);
    insert_audit_log($db, (int) $child['family_id'], $childId, 'Quản trị', 'updated', 'child_account', $childId, ($active ? 'Mở' : 'Khóa') . " tài khoản con {$child['username']}.");
    $_SESSION['msg'] = 'Đã cập nhật trạng thái tài khoản con.';

    redirect_home();
}

function handle_add_daily(PDO $db, array $config): void
{
    $familyId = get_current_family_id($db);
    $childId = get_current_child_id($db);
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
    ensure_activity_daily_limit($db, $childId, $date, $newActivityCount);

    $imagePath = save_uploaded_image('image', $config);
    $count = 0;
    $total = 0;

    foreach ($selectedActivities as $key) {
        [$title, $stars, $category] = $config['activity_options'][$key] + [null, null, 'other'];
        $activityId = insert_activity($db, $familyId, $childId, $date, $title, $category, $stars, $note, $imagePath);
        insert_audit_log($db, $familyId, $childId, 'Gia đình', 'created', 'activity', $activityId, "Thêm hoạt động {$title} ({$stars}★) ngày {$date}.");
        $count++;
        $total += $stars;
        $imagePath = null;
    }

    if (isset($config['penalty_options'][$screen]) && $config['penalty_options'][$screen][1] !== 0) {
        [$title, $stars, $category] = $config['penalty_options'][$screen] + [null, null, 'screen_penalty'];
        $activityId = insert_activity($db, $familyId, $childId, $date, $title, $category, $stars, $note, $imagePath);
        insert_audit_log($db, $familyId, $childId, 'Gia đình', 'created', 'activity', $activityId, "Thêm hoạt động {$title} ({$stars}★) ngày {$date}.");
        $count++;
        $total += $stars;
    }

    $_SESSION['msg'] = $count > 0 ? "Đã ghi nhận $count mục, tổng {$total}★." : 'Chưa chọn hoạt động nào.';
    redirect_home();
}

function handle_add_single(PDO $db, array $config): void
{
    $familyId = get_current_family_id($db);
    $childId = get_current_child_id($db);
    $date = require_today_date($_POST['single_date'] ?? '', 'ngày hoạt động');
    $title = trim($_POST['single_title'] ?? '');
    $category = sanitize_activity_category($_POST['single_category'] ?? 'other', $config);
    $stars = (int) ($_POST['single_stars'] ?? 0);
    $note = trim($_POST['single_note'] ?? '');

    if ($title !== '') {
        ensure_activity_daily_limit($db, $childId, $date, 1);
        $imagePath = save_uploaded_image('single_image', $config);
        $activityId = insert_activity($db, $familyId, $childId, $date, $title, $category, $stars, $note, $imagePath);
        insert_audit_log($db, $familyId, $childId, 'Gia đình', 'created', 'activity', $activityId, "Thêm hoạt động {$title} ({$stars}★) ngày {$date}.");
        $_SESSION['msg'] = 'Đã ghi nhận mục bổ sung.';
    } else {
        $_SESSION['msg'] = 'Vui lòng nhập tên hoạt động.';
    }

    redirect_home();
}

function handle_add_reward(PDO $db, array $config): void
{
    $familyId = get_current_family_id($db);
    $childId = get_current_child_id($db);
    $date = require_today_date($_POST['reward_date'] ?? '', 'ngày đổi thưởng');
    $title = trim($_POST['reward_title'] ?? '');
    $rewardOptions = $config['reward_options'];
    $cost = (int) ($rewardOptions[$title] ?? 0);
    $note = trim($_POST['reward_note'] ?? '');

    if ($title === '' || $cost <= 0) {
        $_SESSION['msg'] = 'Vui lòng chọn phần thưởng hợp lệ.';
        redirect_home();
    }

    $activityTotals = fetch_activity_totals($db, $childId);
    $currentStars = $activityTotals['total_earned'] - fetch_total_spent($db, $childId);

    if ($currentStars < $cost) {
        $missingStars = $cost - $currentStars;
        $_SESSION['msg'] = "Chưa đủ sao để đổi {$title}. Cần thêm {$missingStars}★ nữa.";
        redirect_home();
    }

    $rewardId = insert_reward($db, $familyId, $childId, $date, $title, $cost, $note);
    insert_audit_log($db, $familyId, $childId, 'Gia đình', 'created', 'reward', $rewardId, "Đổi thưởng {$title} (-{$cost}★) ngày {$date}.");
    $_SESSION['msg'] = "Đã đổi {$title} và trừ {$cost}★.";

    redirect_home();
}

function handle_add_quick_action(PDO $db, array $config): void
{
    $familyId = get_current_family_id($db);
    $childId = get_current_child_id($db);
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

    ensure_activity_daily_limit($db, $childId, $date, $newActivityCount);

    if (empty($_FILES['quick_image']['name']) || !is_uploaded_file($_FILES['quick_image']['tmp_name'])) {
        $_SESSION['msg'] = 'Ghi nhanh cần có ảnh minh chứng. Vui lòng upload ảnh trước khi lưu.';
        redirect_home();
    }

    $imagePath = save_uploaded_image('quick_image', $config);
    $count = 0;
    $total = 0;

    if ($activityKey !== '') {
        [$title, $stars, $optionCategory] = $config['activity_options'][$activityKey] + [null, null, 'other'];
        $activityId = insert_activity($db, $familyId, $childId, $date, $title, $optionCategory, $stars, $note, $imagePath);
        insert_audit_log($db, $familyId, $childId, 'Gia đình', 'created', 'activity', $activityId, "Ghi nhanh {$title} ({$stars}★) ngày {$date}.");
        $count++;
        $total += $stars;
        $imagePath = null;
    }

    if ($penaltyKey !== 0) {
        [$title, $stars, $penaltyCategory] = $config['penalty_options'][$penaltyKey] + [null, null, 'screen_penalty'];
        $activityId = insert_activity($db, $familyId, $childId, $date, $title, $penaltyCategory, $stars, $note, $imagePath);
        insert_audit_log($db, $familyId, $childId, 'Gia đình', 'created', 'activity', $activityId, "Ghi nhanh {$title} ({$stars}★) ngày {$date}.");
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
    insert_audit_log($db, (int) $activity['family_id'], (int) $activity['child_id'], 'Con', 'updated', 'activity', $id, "Sửa nội dung task {$title} ngày {$activity['activity_date']}.");
    $_SESSION['msg'] = 'Đã cập nhật nội dung task.';

    redirect_home();
}

function handle_update_child_profile(PDO $db): void
{
    $familyId = get_current_family_id($db);
    $childId = get_current_child_id($db);
    $nickname = trim((string) ($_POST['profile_nickname'] ?? ''));
    $fullName = trim((string) ($_POST['profile_full_name'] ?? ''));
    $birthday = trim((string) ($_POST['profile_birthday'] ?? ''));
    $className = trim((string) ($_POST['profile_class_name'] ?? ''));
    $favoriteSubject = trim((string) ($_POST['profile_favorite_subject'] ?? ''));
    $hobby = trim((string) ($_POST['profile_hobby'] ?? ''));
    $profileNote = trim((string) ($_POST['profile_note'] ?? ''));

    if ($birthday !== '') {
        $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $birthday);
        if (!$parsed || $parsed->format('Y-m-d') !== $birthday) {
            $_SESSION['msg'] = 'Vui lòng nhập ngày sinh hợp lệ.';
            redirect_home();
        }
    }

    if ($nickname === '' && $fullName === '') {
        $_SESSION['msg'] = 'Vui lòng nhập nickname hoặc họ tên.';
        redirect_home();
    }

    upsert_child_profile($db, $childId, $nickname, $fullName, $birthday, $className, $favoriteSubject, $hobby, $profileNote);
    insert_audit_log($db, $familyId, $childId, is_child_logged_in() ? 'Con' : 'Bố mẹ', 'updated', 'child_profile', $childId, 'Cập nhật profile cá nhân của con.');
    $_SESSION['msg'] = 'Đã cập nhật profile cá nhân.';

    redirect_home();
}

function handle_add_weekly_goal(PDO $db): void
{
    $familyId = get_current_family_id($db);
    $childId = get_current_child_id($db);
    $title = trim((string) ($_POST['goal_title'] ?? ''));
    $dailyTarget = max(0, (int) ($_POST['goal_daily_target'] ?? 0));
    $targetAmount = max(0, (int) ($_POST['goal_target_amount'] ?? 0));
    $unitLabel = trim((string) ($_POST['goal_unit_label'] ?? ''));
    $note = trim((string) ($_POST['goal_note'] ?? ''));
    $weekStart = get_week_start();

    if ($title === '') {
        $_SESSION['msg'] = 'Vui lòng nhập tên mục tiêu tuần.';
        redirect_home();
    }

    if ($targetAmount <= 0) {
        $_SESSION['msg'] = 'Mục tiêu tuần cần lớn hơn 0.';
        redirect_home();
    }

    if ($unitLabel === '') {
        $_SESSION['msg'] = 'Vui lòng nhập đơn vị đo mục tiêu.';
        redirect_home();
    }

    $goalId = insert_weekly_goal($db, $familyId, $childId, $weekStart, $title, $dailyTarget, $targetAmount, $unitLabel, $note);
    insert_audit_log($db, $familyId, $childId, is_child_logged_in() ? 'Con' : 'Bố mẹ', 'created', 'weekly_goal', $goalId, "Tạo mục tiêu tuần {$title}: {$targetAmount} {$unitLabel}.");
    $_SESSION['msg'] = 'Đã thêm mục tiêu tuần.';

    redirect_home();
}

function handle_update_weekly_goal_progress(PDO $db): void
{
    $id = (int) ($_POST['goal_id'] ?? 0);
    $progressAmount = max(0, (int) ($_POST['goal_progress_amount'] ?? 0));
    $goal = find_weekly_goal($db, $id);

    if (!$goal) {
        $_SESSION['msg'] = 'Không tìm thấy mục tiêu tuần.';
        redirect_home();
    }

    update_weekly_goal_progress($db, $id, $progressAmount);
    insert_audit_log($db, (int) $goal['family_id'], (int) $goal['child_id'], is_child_logged_in() ? 'Con' : 'Bố mẹ', 'updated', 'weekly_goal', $id, "Cập nhật mục tiêu {$goal['title']}: đã làm {$progressAmount} {$goal['unit_label']}.");
    $_SESSION['msg'] = 'Đã cập nhật tiến độ mục tiêu tuần.';

    redirect_home();
}

function handle_delete_weekly_goal(PDO $db): void
{
    $id = (int) ($_POST['goal_id'] ?? 0);
    $goal = find_weekly_goal($db, $id);

    if (!$goal) {
        $_SESSION['msg'] = 'Không tìm thấy mục tiêu tuần để xóa.';
        redirect_home();
    }

    delete_weekly_goal($db, $id);
    insert_audit_log($db, (int) $goal['family_id'], (int) $goal['child_id'], 'Bố mẹ', 'deleted', 'weekly_goal', $id, "Xóa mục tiêu tuần {$goal['title']}.");
    $_SESSION['msg'] = 'Đã xóa mục tiêu tuần.';

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
        insert_audit_log($db, (int) $activity['family_id'], (int) $activity['child_id'], 'Gia đình', 'deleted', 'activity', $id, "Xóa hoạt động {$activity['title']} ({$activity['stars']}★) ngày {$activity['activity_date']}.");
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
    insert_audit_log($db, (int) $activity['family_id'], (int) $activity['child_id'], 'Bố mẹ', 'updated', 'activity', $id, "Cập nhật phản hồi và trạng thái " . get_parent_review_status_label($status) . " cho hoạt động {$activity['title']}.");

    if (is_ajax_request()) {
        $dashboard = build_dashboard_stats(
            fetch_activity_totals($db, (int) $activity['child_id']),
            fetch_total_spent($db, (int) $activity['child_id']),
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
    insert_audit_log($db, (int) $activity['family_id'], (int) $activity['child_id'], 'Bố mẹ', 'updated', 'activity', $id, "Sửa hoạt động {$title} ngày {$activity['activity_date']}.");
    $_SESSION['msg'] = 'Bố mẹ đã cập nhật hoạt động.';

    redirect_home();
}

function handle_add_holiday(PDO $db, array $config): void
{
    $familyId = get_current_family_id($db);
    $childId = get_current_child_id($db);
    $startDate = parse_form_date($_POST['holiday_start_date'] ?? '', 'ngày bắt đầu holiday');
    $endDate = parse_form_date($_POST['holiday_end_date'] ?? $startDate, 'ngày kết thúc holiday');
    $type = sanitize_holiday_type((string) ($_POST['holiday_type'] ?? ''), $config);
    $note = trim((string) ($_POST['holiday_note'] ?? ''));
    $start = new DateTimeImmutable($startDate);
    $end = new DateTimeImmutable($endDate);

    if ($end < $start) {
        $_SESSION['msg'] = 'Ngày kết thúc holiday phải sau hoặc bằng ngày bắt đầu.';
        redirect_home();
    }

    $count = 0;
    for ($cursor = $start; $cursor <= $end; $cursor = $cursor->modify('+1 day')) {
        $date = $cursor->format('Y-m-d');
        $holidayId = upsert_holiday($db, $familyId, $date, $type, $note);
        insert_audit_log($db, $familyId, $childId, 'Bố mẹ', 'upserted', 'holiday', $holidayId, "Đặt holiday {$date}: " . get_holiday_type_label(['type' => $type], $config) . '.');
        $count++;
    }

    $_SESSION['msg'] = "Đã lưu {$count} ngày holiday.";
    redirect_home();
}

function handle_update_holiday(PDO $db, array $config): void
{
    $id = (int) ($_POST['holiday_id'] ?? 0);
    $type = sanitize_holiday_type((string) ($_POST['holiday_type'] ?? ''), $config);
    $note = trim((string) ($_POST['holiday_note'] ?? ''));
    $holiday = find_holiday($db, $id);

    if (!$holiday) {
        $_SESSION['msg'] = 'Không tìm thấy ngày holiday để sửa.';
        redirect_home();
    }

    update_holiday($db, $id, $type, $note);
    insert_audit_log($db, (int) $holiday['family_id'], get_current_child_id($db), 'Bố mẹ', 'updated', 'holiday', $id, "Sửa holiday {$holiday['holiday_date']}: " . get_holiday_type_label(['type' => $type], $config) . '.');
    $_SESSION['msg'] = 'Đã cập nhật holiday.';
    redirect_home();
}

function handle_delete_holiday(PDO $db): void
{
    $id = (int) ($_POST['holiday_id'] ?? 0);
    $holiday = find_holiday($db, $id);

    if (!$holiday) {
        $_SESSION['msg'] = 'Không tìm thấy ngày holiday để xóa.';
        redirect_home();
    }

    delete_holiday($db, $id);
    insert_audit_log($db, (int) $holiday['family_id'], get_current_child_id($db), 'Bố mẹ', 'deleted', 'holiday', $id, "Xóa holiday {$holiday['holiday_date']}.");
    $_SESSION['msg'] = 'Đã xóa holiday.';
    redirect_home();
}

function handle_delete_reward(PDO $db): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $reward = find_reward($db, $id);
    delete_reward($db, $id);
    if ($reward) {
        insert_audit_log($db, (int) $reward['family_id'], (int) $reward['child_id'], 'Gia đình', 'deleted', 'reward', $id, "Xóa đổi thưởng {$reward['title']} (-{$reward['cost']}★) ngày {$reward['reward_date']}.");
    }
    redirect_home();
}
