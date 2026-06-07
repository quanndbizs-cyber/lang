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

function public_url(?string $path, string $basePath = ''): string
{
    $path = trim((string) $path);
    if ($path === '') {
        return '';
    }

    if (preg_match('#^https?://#', $path)) {
        return $path;
    }

    $basePath = trim($basePath, '/');
    $path = ltrim($path, '/');

    if ($basePath === '') {
        return '/' . $path;
    }

    return '/' . $basePath . '/' . $path;
}

function is_parent_logged_in(): bool
{
    return !empty($_SESSION['parent_logged_in']);
}

function is_child_logged_in(): bool
{
    return !empty($_SESSION['child_logged_in']);
}

function is_app_logged_in(): bool
{
    return is_parent_logged_in() || is_child_logged_in();
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

function require_child_or_parent_login(): void
{
    if (is_app_logged_in()) {
        return;
    }

    if (is_ajax_request()) {
        json_response([
            'ok' => false,
            'message' => 'Cần đăng nhập để thực hiện thao tác này.',
        ], 403);
    }

    $_SESSION['msg'] = 'Cần đăng nhập để thực hiện thao tác này.';
    redirect_home();
}

function verify_parent_password(string $password, array $config): bool
{
    return hash_equals((string) $config['parent_password'], $password);
}

function verify_child_password(string $password, array $config): bool
{
    return hash_equals((string) ($config['child_password'] ?? ''), $password);
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

function get_counted_activity_stars(array $activity): int
{
    $stars = (int) ($activity['stars'] ?? 0);
    $status = sanitize_parent_review_status($activity['status'] ?? 'pending');

    if ($status === 'ng' && $stars > 0) {
        return 0;
    }

    return $stars;
}

function format_star_delta(int $stars): string
{
    return ($stars > 0 ? '+' : '') . $stars . '★';
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

function parse_form_date(?string $date, string $fieldLabel): string
{
    $date = trim((string) $date);
    $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);

    if ($date === '' || !$parsed || $parsed->format('Y-m-d') !== $date) {
        $_SESSION['msg'] = "Vui lòng chọn {$fieldLabel} hợp lệ.";
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

function get_day_part_greeting(?int $hour = null): string
{
    $hour = $hour ?? (int) date('G');

    if ($hour < 11) {
        return 'Chào buổi sáng! Hôm nay mình bắt đầu nhẹ nhàng nhé.';
    }
    if ($hour < 18) {
        return 'Chào buổi chiều! Mình xem còn việc nào cần hoàn thành nha.';
    }

    return 'Chào buổi tối! Mình cùng tổng kết ngày hôm nay nhé.';
}

function sanitize_holiday_type(string $type, array $config): string
{
    return isset($config['holiday_types'][$type]) ? $type : 'skip_all';
}

function get_holiday_type_label(?array $holiday, array $config): string
{
    if (!$holiday) {
        return '';
    }

    $type = sanitize_holiday_type((string) ($holiday['type'] ?? ''), $config);

    return (string) ($config['holiday_types'][$type]['label'] ?? $type);
}

function group_holidays_by_date(array $holidays): array
{
    $grouped = [];

    foreach ($holidays as $holiday) {
        $date = (string) ($holiday['holiday_date'] ?? '');
        if ($date !== '') {
            $grouped[$date] = $holiday;
        }
    }

    return $grouped;
}

function get_required_activity_keys_for_date(array $config, string $date, ?array $holiday = null): array
{
    if ($holiday) {
        $type = sanitize_holiday_type((string) ($holiday['type'] ?? ''), $config);
        return array_values($config['holiday_types'][$type]['required_activity_keys'] ?? []);
    }

    $required = $config['daily_required_activity_keys'] ?? [];
    $dayType = ((int) date('N', strtotime($date)) >= 6) ? 'weekend' : 'weekday';

    return array_values(array_filter($required[$dayType] ?? []));
}

function get_screen_time_config_for_day(array $config, ?array $holiday = null): array
{
    if ($holiday) {
        $type = sanitize_holiday_type((string) ($holiday['type'] ?? ''), $config);
        $holidayConfig = $config['holiday_types'][$type] ?? [];

        return [
            'daily_limit_minutes' => (int) ($holidayConfig['screen_limit_minutes'] ?? ($config['screen_time']['daily_limit_minutes'] ?? 60)),
            'rest_after_hour' => (int) ($holidayConfig['rest_after_hour'] ?? ($config['screen_time']['rest_after_hour'] ?? 21)),
        ];
    }

    return [
        'daily_limit_minutes' => (int) ($config['screen_time']['daily_limit_minutes'] ?? 60),
        'rest_after_hour' => (int) ($config['screen_time']['rest_after_hour'] ?? 21),
    ];
}

function get_activity_option_label(array $config, string $key): string
{
    return (string) (($config['activity_options'][$key][0] ?? '') ?: $key);
}

function activity_matches_option(array $activity, array $option): bool
{
    [$title, , $category] = $option + ['', 0, 'other'];

    return trim((string) ($activity['title'] ?? '')) === (string) $title
        && (string) ($activity['category'] ?? 'other') === (string) $category;
}

function analyze_daily_progress(array $activities, array $config, string $date, ?array $holiday = null): array
{
    $requiredKeys = get_required_activity_keys_for_date($config, $date, $holiday);
    $completedKeys = [];
    $screenPenaltyCount = 0;
    $completedActivityCount = 0;
    $unfinishedActivityCount = 0;

    foreach ($activities as $activity) {
        $status = sanitize_parent_review_status($activity['status'] ?? 'pending');
        $stars = get_counted_activity_stars($activity);

        if ($status === 'ng') {
            continue;
        }

        if (($activity['category'] ?? '') === 'screen_penalty' || (int) ($activity['stars'] ?? 0) < 0) {
            $screenPenaltyCount++;
            continue;
        }

        if ($stars > 0) {
            $completedActivityCount++;
        } else {
            $unfinishedActivityCount++;
        }

        foreach ($requiredKeys as $key) {
            if (isset($completedKeys[$key]) || !isset($config['activity_options'][$key])) {
                continue;
            }
            if (activity_matches_option($activity, $config['activity_options'][$key])) {
                $completedKeys[$key] = true;
            }
        }
    }

    $missingKeys = array_values(array_filter($requiredKeys, fn($key) => empty($completedKeys[$key])));
    $missingLabels = array_map(fn($key) => get_activity_option_label($config, $key), $missingKeys);
    $completedRequiredCount = count($requiredKeys) - count($missingKeys);

    return [
        'date' => $date,
        'required_keys' => $requiredKeys,
        'missing_keys' => $missingKeys,
        'missing_labels' => $missingLabels,
        'completed_required_count' => $completedRequiredCount,
        'required_count' => count($requiredKeys),
        'completed_activity_count' => $completedActivityCount,
        'unfinished_activity_count' => $unfinishedActivityCount + count($missingKeys),
        'screen_penalty_count' => $screenPenaltyCount,
        'is_holiday' => $holiday !== null,
        'holiday' => $holiday,
        'is_complete' => count($requiredKeys) > 0 && count($missingKeys) === 0,
        'is_exempt' => $holiday !== null,
    ];
}

function count_completion_streak(array $activitiesByDate, array $config, string $endDate, array $holidaysByDate = []): int
{
    $streak = 0;
    $cursor = new DateTimeImmutable($endDate);

    for ($i = 0; $i < 30; $i++) {
        $date = $cursor->format('Y-m-d');
        if (isset($holidaysByDate[$date])) {
            $cursor = $cursor->modify('-1 day');
            continue;
        }

        $progress = analyze_daily_progress($activitiesByDate[$date] ?? [], $config, $date);
        if (!$progress['is_complete']) {
            break;
        }

        $streak++;
        $cursor = $cursor->modify('-1 day');
    }

    return $streak;
}

function group_activities_by_date(array $activities): array
{
    $grouped = [];

    foreach ($activities as $activity) {
        $date = (string) ($activity['activity_date'] ?? '');
        if ($date === '') {
            continue;
        }
        $grouped[$date][] = $activity;
    }

    return $grouped;
}

function build_dashboard_coach(array $todayActivities, array $recentActivities, array $config, ?array $todayHoliday = null, array $recentHolidays = []): array
{
    $today = date('Y-m-d');
    $progress = analyze_daily_progress($todayActivities, $config, $today, $todayHoliday);
    $activitiesByDate = group_activities_by_date($recentActivities);
    $activitiesByDate[$today] = $todayActivities;
    $holidaysByDate = group_holidays_by_date($recentHolidays);
    if ($todayHoliday) {
        $holidaysByDate[$today] = $todayHoliday;
    }
    $streak = count_completion_streak($activitiesByDate, $config, $today, $holidaysByDate);
    $hour = (int) date('G');
    $screenConfig = get_screen_time_config_for_day($config, $todayHoliday);
    $restAfterHour = $screenConfig['rest_after_hour'];
    $screenLimit = $screenConfig['daily_limit_minutes'];
    $screenNeedsRest = $progress['screen_penalty_count'] > 0 || $hour >= $restAfterHour;
    $screenTitle = 'Chưa nên chơi màn hình';
    $screenMessage = 'Mình làm xong các việc chính trước rồi hãy giải trí nhé.';

    if ($screenNeedsRest) {
        $screenTitle = 'Nghỉ màn hình thôi';
        $screenMessage = $progress['screen_penalty_count'] > 0
            ? 'Hôm nay màn hình đã quá giờ. Mắt và đầu cần nghỉ một chút nhé.'
            : 'Đã đến giờ nghỉ màn hình. Mình chuẩn bị ngủ ngon để mai có sức.';
    } elseif ($progress['is_complete']) {
        $screenTitle = 'Có thể giải trí có chừng mực';
        $screenMessage = "Con đã xong việc chính. Nếu bố mẹ đồng ý, mình chơi tối đa {$screenLimit} phút nhé.";
    }

    $primaryMessage = $progress['is_complete']
        ? 'Tuyệt lắm, hôm nay con đã hoàn thành đủ việc cần làm.'
        : 'Mình còn vài việc nhỏ. Làm từng việc một là sẽ xong thôi.';

    if ($streak >= 3) {
        $primaryMessage = "Quá tốt! Con đã giữ chuỗi {$streak} ngày hoàn thành đủ việc.";
    }

    if ($todayHoliday) {
        $holidayLabel = get_holiday_type_label($todayHoliday, $config);
        $holidayDescription = (string) ($config['holiday_types'][sanitize_holiday_type((string) $todayHoliday['type'], $config)]['description'] ?? '');
        $primaryMessage = "Hôm nay là holiday: {$holidayLabel}.";
        if ($progress['required_count'] === 0) {
            $screenTitle = 'Ngày nghỉ thoải mái';
            $screenMessage = "Hôm nay không tính thiếu việc. Màn hình vẫn giữ tối đa {$screenLimit} phút và nghỉ sau {$restAfterHour}h nhé.";
        }
    }

    return [
        'greeting' => get_day_part_greeting($hour),
        'primary_message' => $primaryMessage,
        'holiday_label' => $todayHoliday ? get_holiday_type_label($todayHoliday, $config) : '',
        'holiday_note' => $todayHoliday ? (string) ($todayHoliday['note'] ?? '') : '',
        'holiday_description' => $todayHoliday ? $holidayDescription : '',
        'progress' => $progress,
        'streak' => $streak,
        'screen_title' => $screenTitle,
        'screen_message' => $screenMessage,
        'screen_needs_rest' => $screenNeedsRest,
        'daily_summary' => "Hôm nay đã xong {$progress['completed_activity_count']} activity, còn {$progress['unfinished_activity_count']} mục cần chú ý.",
    ];
}
