<?php

function handle_request(PDO $db, array $config): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add_daily') {
        handle_add_daily($db, $config);
    }
    if ($action === 'add_single') {
        handle_add_single($db, $config);
    }
    if ($action === 'add_reward') {
        handle_add_reward($db);
    }
    if ($action === 'add_quick_action') {
        handle_add_quick_action($db, $config);
    }
    if ($action === 'delete_activity') {
        handle_delete_activity($db);
    }
    if ($action === 'delete_reward') {
        handle_delete_reward($db);
    }
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
        insert_activity($db, $date, $title, $category, $stars, $note, $imagePath);
        $count++;
        $total += $stars;
        $imagePath = null;
    }

    $screen = (int) ($_POST['screen_minutes'] ?? 0);
    if (isset($config['penalty_options'][$screen]) && $config['penalty_options'][$screen][1] !== 0) {
        [$title, $stars, $category] = $config['penalty_options'][$screen] + [null, null, 'screen_penalty'];
        insert_activity($db, $date, $title, $category, $stars, $note, $imagePath);
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
        insert_activity($db, $date, $title, $category, $stars, $note, $imagePath);
        $_SESSION['msg'] = 'Đã ghi nhận mục bổ sung.';
    } else {
        $_SESSION['msg'] = 'Vui lòng nhập tên hoạt động.';
    }

    redirect_home();
}

function handle_add_reward(PDO $db): void
{
    $date = $_POST['reward_date'] ?: date('Y-m-d');
    $title = trim($_POST['reward_title'] ?? '');
    $cost = (int) ($_POST['cost'] ?? 0);
    $note = trim($_POST['reward_note'] ?? '');

    if ($title !== '' && $cost > 0) {
        insert_reward($db, $date, $title, $cost, $note);
        $_SESSION['msg'] = 'Đã đổi thưởng.';
    }

    redirect_home();
}

function handle_add_quick_action(PDO $db, array $config): void
{
    $quickActionKey = $_POST['quick_action'] ?? '';
    $date = $_POST['quick_date'] ?: date('Y-m-d');

    if (!isset($config['quick_actions'][$quickActionKey])) {
        $_SESSION['msg'] = 'Không tìm thấy hành động nhanh.';
        redirect_home();
    }

    [$title, $stars, $category] = $config['quick_actions'][$quickActionKey] + [null, null, 'other'];
    insert_activity($db, $date, $title, $category, $stars, '', null);

    $sign = $stars > 0 ? '+' : '';
    $_SESSION['msg'] = "Đã ghi nhanh {$title} ({$sign}{$stars}★).";

    redirect_home();
}

function handle_delete_activity(PDO $db): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $imagePath = find_activity_image_path($db, $id);

    if ($imagePath) {
        @unlink(__DIR__ . '/../public/' . $imagePath);
    }

    delete_activity($db, $id);
    redirect_home();
}

function handle_delete_reward(PDO $db): void
{
    $id = (int) ($_POST['id'] ?? 0);
    delete_reward($db, $id);
    redirect_home();
}
