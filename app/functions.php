<?php

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect_home(): void
{
    header('Location: index.php');
    exit;
}

function is_ajax_request(): bool
{
    return str_contains(strtolower($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json')
        || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
}

function json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function public_url(?string $path): string
{
    $path = trim((string) $path);
    if ($path === '') {
        return '';
    }

    if (preg_match('#^https?://#', $path) || str_starts_with($path, '/')) {
        return $path;
    }

    return '/' . ltrim($path, '/');
}

function is_parent_logged_in(): bool
{
    return !empty($_SESSION['parent_logged_in']);
}

function require_parent_login(): void
{
    if (is_parent_logged_in()) {
        return;
    }

    if (is_ajax_request()) {
        json_response([
            'ok' => false,
            'message' => 'Bố mẹ cần đăng nhập để thực hiện thao tác này.',
        ], 403);
    }

    $_SESSION['msg'] = 'Bố mẹ cần đăng nhập để thực hiện thao tác này.';
    redirect_home();
}

function verify_parent_password(string $password, array $config): bool
{
    return hash_equals((string) $config['parent_password'], $password);
}

function sanitize_activity_category(string $category, array $config): string
{
    return isset($config['activity_categories'][$category]) ? $category : 'other';
}

function parent_review_status_options(): array
{
    return [
        'pending' => 'Chờ duyệt',
        'ng' => 'NG',
        'ok' => 'OK',
        'good' => 'Good',
        'excellent' => 'Excellent',
    ];
}

function sanitize_parent_review_status(string $status): string
{
    $status = strtolower(trim($status));

    return array_key_exists($status, parent_review_status_options()) ? $status : 'pending';
}

function get_parent_review_status_label(?string $status): string
{
    $status = sanitize_parent_review_status((string) $status);
    $options = parent_review_status_options();

    return $options[$status];
}

function format_activity_datetime(?string $datetime): string
{
    if (!$datetime) {
        return '';
    }

    $timestamp = strtotime($datetime);
    if ($timestamp === false) {
        return $datetime;
    }

    return date('d/m/Y H:i', $timestamp);
}

function require_today_date(?string $date, string $fieldLabel = 'ngày chọn'): string
{
    $date = trim((string) $date);
    $today = date('Y-m-d');

    if ($date === '') {
        return $today;
    }

    $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    if (!$parsed || $parsed->format('Y-m-d') !== $date) {
        $_SESSION['msg'] = "Vui lòng chọn {$fieldLabel} hợp lệ.";
        redirect_home();
    }

    if ($date !== $today) {
        $_SESSION['msg'] = "Không thể lưu {$fieldLabel} trước hoặc sau ngày hiện tại.";
        redirect_home();
    }

    return $date;
}

function ensure_activity_daily_limit(PDO $db, string $date, int $newActivityCount, int $maxActivities = 12): void
{
    if ($newActivityCount <= 0) {
        return;
    }

    $currentCount = count_activities_by_date($db, $date);
    if ($currentCount + $newActivityCount > $maxActivities) {
        $remaining = max(0, $maxActivities - $currentCount);
        $_SESSION['msg'] = "Một ngày chỉ được ghi tối đa {$maxActivities} hoạt động. Hôm nay còn có thể thêm {$remaining} hoạt động.";
        redirect_home();
    }
}

function get_activity_icon(array $activity): string
{
    if ((int) ($activity['stars'] ?? 0) < 0) {
        return '⚠️';
    }

    return match ($activity['category'] ?? 'other') {
        'study' => '📚',
        'reading' => '📖',
        'writing' => '✍️',
        'exercise' => '🏃',
        'creative' => '🎨',
        'housework' => '🧹',
        'plant_fish' => '🌱',
        'bonus' => '⭐',
        default => '✨',
    };
}

function save_uploaded_image(string $field, array $config): ?string
{
    if (empty($_FILES[$field]['name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
        return null;
    }

    $mime = mime_content_type($_FILES[$field]['tmp_name']);
    $allowedTypes = $config['allowed_upload_types'];
    $maxUploadSize = $config['max_upload_size'];

    if (!isset($allowedTypes[$mime]) || $_FILES[$field]['size'] > $maxUploadSize) {
        $_SESSION['msg'] = 'Ảnh không hợp lệ hoặc lớn hơn 5MB.';
        redirect_home();
    }

    $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowedTypes[$mime];
    $targetPath = $config['upload_dir'] . '/' . $filename;

    move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath);

    return 'uploads/' . $filename;
}

function get_level_name(int $stars): string
{
    if ($stars >= 300) {
        return '👑 Công chúa mùa hè';
    }
    if ($stars >= 200) {
        return '🦄 Nhà sáng tạo';
    }
    if ($stars >= 100) {
        return '🌻 Siêu chăm chỉ';
    }
    if ($stars >= 50) {
        return '🌱 Mầm xanh';
    }

    return '🐣 Chim non';
}

function get_next_reward_target(int $currentStars, array $rewardOptions): int
{
    $targets = array_unique(array_values($rewardOptions));
    sort($targets);

    foreach ($targets as $target) {
        if ($currentStars < $target) {
            return $target;
        }
    }

    return max($targets) ?: 0;
}

function get_next_reward(int $currentStars, array $rewardOptions): array
{
    asort($rewardOptions);

    foreach ($rewardOptions as $title => $cost) {
        if ($currentStars < $cost) {
            return [
                'title' => $title,
                'cost' => (int) $cost,
            ];
        }
    }

    $lastTitle = array_key_last($rewardOptions);

    return [
        'title' => $lastTitle ? (string) $lastTitle : 'Chưa có phần thưởng',
        'cost' => $lastTitle ? (int) $rewardOptions[$lastTitle] : 0,
    ];
}

function build_dashboard_stats(array $activityTotals, int $totalSpent, array $rewardOptions): array
{
    $currentStars = $activityTotals['total_earned'] - $totalSpent;
    $nextReward = get_next_reward($currentStars, $rewardOptions);
    $nextRewardCost = $nextReward['cost'];
    $progressBase = max(1, $nextRewardCost);

    return [
        'total_earned' => $activityTotals['total_earned'],
        'total_spent' => $totalSpent,
        'current_stars' => $currentStars,
        'today_stars' => $activityTotals['today_stars'],
        'week_stars' => $activityTotals['week_stars'],
        'month_stars' => $activityTotals['month_stars'],
        'level_name' => get_level_name($currentStars),
        'next_reward_title' => $nextReward['title'],
        'next_reward_cost' => $nextRewardCost,
        'missing_stars' => max(0, $nextRewardCost - $currentStars),
        'progress_percent' => min(100, max(0, ($currentStars / $progressBase) * 100)),
    ];
}
