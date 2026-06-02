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

function build_dashboard_stats(array $activityTotals, int $totalSpent, array $rewardOptions): array
{
    $currentStars = $activityTotals['total_earned'] - $totalSpent;
    $nextRewardCost = get_next_reward_target($currentStars, $rewardOptions);
    $progressBase = max(1, $nextRewardCost);

    return [
        'total_earned' => $activityTotals['total_earned'],
        'total_spent' => $totalSpent,
        'current_stars' => $currentStars,
        'today_stars' => $activityTotals['today_stars'],
        'month_stars' => $activityTotals['month_stars'],
        'level_name' => get_level_name($currentStars),
        'next_reward_cost' => $nextRewardCost,
        'missing_stars' => max(0, $nextRewardCost - $currentStars),
        'progress_percent' => min(100, max(0, ($currentStars / $progressBase) * 100)),
    ];
}
