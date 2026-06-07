<?php
session_start();

$config = require __DIR__ . '/../app/config.php';

require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/functions.php';
require __DIR__ . '/../app/actions.php';

$db = connect_database($config);

handle_request($db, $config);

$parentLoggedIn = is_parent_logged_in();
$childLoggedIn = is_child_logged_in();
$activityOptions = $config['activity_options'];
$activityCategories = $config['activity_categories'];
$penaltyOptions = $config['penalty_options'];
$rewardOptions = $config['reward_options'];
$holidayTypes = $config['holiday_types'];
$parentReviewStatusOptions = parent_review_status_options();
$appVersion = $config['app_version'] ?? '1.0.0';
$publicBasePath = $config['public_base_path'] ?? '';

if (!is_app_logged_in()): ?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Đăng nhập - Bảng sao mùa hè</title>
<link rel="stylesheet" href="<?=h(public_url('assets/style.css', $publicBasePath))?>">
<link rel="icon" type="image/png" href="<?=h(public_url('favicon.ico', $publicBasePath))?>">
</head>
<body>
<div class="wrap auth-wrap">
  <div class="hero auth-hero">
    <h1>🌈 BẢNG SAO MÙA HÈ ⭐</h1>
    <div class="sub">Đăng nhập để xem và nhập thông tin.</div>
  </div>
  <?php if (!empty($_SESSION['msg'])): ?><div class="notice"><?=h($_SESSION['msg']); unset($_SESSION['msg']);?></div><?php endif; ?>
  <div class="auth-grid">
    <div class="card">
      <h2>👧 Đăng nhập cho con</h2>
      <form class="parent-login-form" method="post">
        <input type="hidden" name="action" value="child_login">
        <input type="password" name="child_password" placeholder="Mật khẩu của con" required autofocus>
        <button class="btn green">Vào bảng sao</button>
      </form>
    </div>
    <div class="card">
      <h2>🔐 Đăng nhập bố mẹ</h2>
      <form class="parent-login-form" method="post">
        <input type="hidden" name="action" value="parent_login">
        <input type="password" name="parent_password" placeholder="Mật khẩu bố mẹ" required>
        <button class="btn blue">Đăng nhập</button>
      </form>
    </div>
  </div>
  <div class="footer">v<?=h($appVersion)?></div>
</div>
</body></html>
<?php exit; endif;

$dashboard = build_dashboard_stats(
    fetch_activity_totals($db),
    fetch_total_spent($db),
    $rewardOptions
);
$childProfile = fetch_child_profile($db);
$childDisplayName = get_child_display_name($childProfile);
$activities = fetch_activities($db);
$todayActivities = fetch_today_activities($db);
$recentActivities = fetch_activities_between($db, date('Y-m-d', strtotime('-30 days')), date('Y-m-d'));
$currentWeekStart = get_week_start();
$currentWeekEnd = get_week_end($currentWeekStart);
$weeklyGoals = fetch_weekly_goals($db, $currentWeekStart);
$todayHoliday = find_holiday_by_date($db, date('Y-m-d'));
$recentHolidays = fetch_holidays_between($db, date('Y-m-d', strtotime('-30 days')), date('Y-m-d'));
$holidaysByDate = group_holidays_by_date(fetch_holidays_between($db, date('Y-m-d', strtotime('-90 days')), date('Y-m-d', strtotime('+90 days'))));
$dashboardCoach = build_dashboard_coach($todayActivities, $recentActivities, $config, $todayHoliday, $recentHolidays);
$rewards = fetch_rewards($db);
$holidays = $parentLoggedIn ? fetch_holidays($db) : [];
$auditLogs = $parentLoggedIn ? fetch_audit_logs($db) : [];
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>⭐ Bảng sao mùa hè</title>
<link rel="stylesheet" href="<?=h(public_url('assets/style.css', $publicBasePath))?>">
<link rel="icon" type="image/png" href="<?=h(public_url('favicon.ico', $publicBasePath))?>">
</head>
<body>
<div class="wrap">
  <div class="hero">
    <h1>🌈 BẢNG SAO MÙA HÈ ⭐</h1>
    <div class="profile-hello">Xin chào, <?=h($childDisplayName)?>!</div>
    <div class="sub">Học tốt · Vui khỏe · Tự lập · Sáng tạo · Ít màn hình</div>
    <p>Mỗi ngày cố gắng hơn hôm qua một chút nhé!</p>
    <div class="stars">Hiện có: <span data-dashboard-value="current_stars" data-suffix="★"><?=h($dashboard['current_stars'])?>★</span></div>
    <div class="pill">Đã nhận: <span data-dashboard-value="total_earned" data-suffix="★"><?=h($dashboard['total_earned'])?>★</span></div><div class="pill">Đã đổi: <span data-dashboard-value="total_spent" data-suffix="★"><?=h($dashboard['total_spent'])?>★</span></div><div class="pill">Danh hiệu: <span data-dashboard-value="level_name"><?=h($dashboard['level_name'])?></span></div>
    <div class="stat-grid">
      <div class="stat">Tổng sao<b data-dashboard-value="current_stars" data-suffix="★"><?=h($dashboard['current_stars'])?>★</b></div>
      <div class="stat">Hôm nay<b data-dashboard-value="today_stars" data-suffix="★"><?=h($dashboard['today_stars'])?>★</b></div>
      <div class="stat">Tuần này<b data-dashboard-value="week_stars" data-suffix="★"><?=h($dashboard['week_stars'])?>★</b></div>
      <div class="stat">Tháng này<b data-dashboard-value="month_stars" data-suffix="★"><?=h($dashboard['month_stars'])?>★</b></div>
    </div>
  </div>
  <?php if (!empty($_SESSION['msg'])): ?><div class="notice"><?=h($_SESSION['msg']); unset($_SESSION['msg']);?></div><?php endif; ?>
  <div class="coach-card" style="margin-top:18px">
    <div class="coach-main">
      <div class="coach-greeting"><?=h($dashboardCoach['greeting'])?></div>
      <?php if ($dashboardCoach['holiday_label']): ?>
        <div class="holiday-banner">
          <b>Hôm nay là holiday: <?=h($dashboardCoach['holiday_label'])?></b>
          <span><?=h($dashboardCoach['holiday_description'])?></span>
          <?php if ($dashboardCoach['holiday_note'] !== ''): ?><em><?=h($dashboardCoach['holiday_note'])?></em><?php endif; ?>
        </div>
      <?php endif; ?>
      <h2><?=h($dashboardCoach['primary_message'])?></h2>
      <div class="coach-summary"><?=h($dashboardCoach['daily_summary'])?></div>
      <?php if ($dashboardCoach['progress']['is_complete']): ?>
        <div class="coach-praise">Lời khen hôm nay: con rất tự giác và biết giữ lời hứa với chính mình.</div>
      <?php elseif (!empty($dashboardCoach['progress']['missing_labels'])): ?>
        <div class="coach-reminder">Nhắc nhẹ: mình còn thiếu các việc này.</div>
        <ul class="missing-list">
          <?php foreach ($dashboardCoach['progress']['missing_labels'] as $missingLabel): ?>
            <li><?=h($missingLabel)?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="coach-praise">Hôm nay không có việc bắt buộc. Con nghỉ ngơi vui vẻ và vẫn giữ giờ màn hình nhé.</div>
      <?php endif; ?>
      <?php if ($dashboardCoach['streak'] >= 3): ?>
        <div class="streak-badge">Chuỗi hoàn thành: <?=h($dashboardCoach['streak'])?> ngày liên tiếp</div>
      <?php endif; ?>
    </div>
    <div class="screen-status <?= $dashboardCoach['screen_needs_rest'] ? 'screen-rest' : '' ?>">
      <b><?=h($dashboardCoach['screen_title'])?></b>
      <span><?=h($dashboardCoach['screen_message'])?></span>
    </div>
  </div>
  <div class="card no-print parent-login-card" style="margin-top:18px">
    <h2>🔐 Khu vực bố mẹ</h2>
    <?php if ($parentLoggedIn): ?>
      <form class="parent-login-form" method="post">
        <input type="hidden" name="action" value="parent_logout">
        <span class="notice inline-notice">Đã đăng nhập quyền bố mẹ.</span>
        <button class="btn small blue">Đăng xuất</button>
      </form>
    <?php else: ?>
      <form class="parent-login-form" method="post">
        <input type="hidden" name="action" value="parent_login">
        <input type="password" name="parent_password" placeholder="Mật khẩu bố mẹ" required>
        <button class="btn small blue">Đăng nhập</button>
      </form>
    <?php endif; ?>
  </div>
  <div class="card no-print parent-login-card" style="margin-top:18px">
    <h2>👧 Khu vực con</h2>
    <?php if ($childLoggedIn): ?>
      <form class="parent-login-form" method="post">
        <input type="hidden" name="action" value="child_logout">
        <span class="notice inline-notice">Con đã đăng nhập.</span>
        <button class="btn small green">Đăng xuất</button>
      </form>
    <?php else: ?>
      <div class="muted">Con chưa đăng nhập. Bố mẹ đang xem bằng quyền bố mẹ.</div>
    <?php endif; ?>
  </div>
  <div class="card profile-card" style="margin-top:18px">
    <div class="section-head">
      <div>
        <h2>👤 Profile cá nhân</h2>
        <div class="muted">Con có thể tự cập nhật nickname và thông tin riêng.</div>
      </div>
      <div class="profile-name"><?=h($childDisplayName)?></div>
    </div>
    <div class="profile-summary">
      <div><b>Họ tên</b><span><?=h($childProfile['full_name'] ?? 'Chưa nhập')?></span></div>
      <div><b>Ngày sinh</b><span><?=h($childProfile['birthday'] ?? 'Chưa nhập')?></span></div>
      <div><b>Lớp</b><span><?=h($childProfile['class_name'] ?? 'Chưa nhập')?></span></div>
      <div><b>Môn thích</b><span><?=h($childProfile['favorite_subject'] ?? 'Chưa nhập')?></span></div>
      <div><b>Sở thích</b><span><?=h($childProfile['hobby'] ?? 'Chưa nhập')?></span></div>
    </div>
    <?php if (!empty($childProfile['profile_note'])): ?>
      <div class="profile-note"><?=h($childProfile['profile_note'])?></div>
    <?php endif; ?>
    <details class="profile-edit no-print">
      <summary>Cập nhật profile</summary>
      <form method="post" class="profile-form">
        <input type="hidden" name="action" value="update_child_profile">
        <p><b>Nickname</b><input name="profile_nickname" value="<?=h($childProfile['nickname'] ?? '')?>" placeholder="Ví dụ: Bống" required></p>
        <p><b>Họ tên</b><input name="profile_full_name" value="<?=h($childProfile['full_name'] ?? '')?>" placeholder="Họ tên đầy đủ"></p>
        <p><b>Ngày sinh</b><input type="date" name="profile_birthday" value="<?=h($childProfile['birthday'] ?? '')?>"></p>
        <p><b>Lớp</b><input name="profile_class_name" value="<?=h($childProfile['class_name'] ?? '')?>" placeholder="Ví dụ: 3A"></p>
        <p><b>Môn thích</b><input name="profile_favorite_subject" value="<?=h($childProfile['favorite_subject'] ?? '')?>" placeholder="Ví dụ: Toán, Mỹ thuật"></p>
        <p><b>Sở thích</b><input name="profile_hobby" value="<?=h($childProfile['hobby'] ?? '')?>" placeholder="Ví dụ: đọc truyện, vẽ tranh"></p>
        <p class="wide"><b>Thông tin khác</b><textarea name="profile_note" placeholder="Con muốn ghi thêm gì về bản thân..."><?=h($childProfile['profile_note'] ?? '')?></textarea></p>
        <button class="btn blue">Lưu profile</button>
      </form>
    </details>
  </div>
  <div class="card weekly-goal-card" style="margin-top:18px">
    <div class="section-head">
      <div>
        <h2>🎯 Mục tiêu tuần</h2>
        <div class="muted">Tuần <?=h(date('d/m', strtotime($currentWeekStart)))?> - <?=h(date('d/m/Y', strtotime($currentWeekEnd)))?></div>
      </div>
    </div>
    <form class="weekly-goal-form no-print" method="post">
      <input type="hidden" name="action" value="add_weekly_goal">
      <p><b>Mục tiêu</b><input name="goal_title" placeholder="Ví dụ: Đọc sách mỗi ngày" required></p>
      <p><b>Mỗi ngày</b><input type="number" name="goal_daily_target" value="10" min="0"></p>
      <p><b>Cả tuần</b><input type="number" name="goal_target_amount" value="70" min="1" required></p>
      <p><b>Đơn vị</b><input name="goal_unit_label" value="trang sách" required></p>
      <p class="wide"><b>Ghi chú</b><input name="goal_note" placeholder="Ví dụ: đọc trước giờ đi ngủ"></p>
      <button class="btn green">Thêm mục tiêu</button>
    </form>
    <?php if ($weeklyGoals): ?>
      <div class="weekly-goal-list">
        <?php foreach ($weeklyGoals as $goal): ?>
          <?php $goalView = build_weekly_goal_view($goal); ?>
          <div class="weekly-goal-item">
            <div>
              <b><?=h($goal['title'])?></b>
              <div class="muted">
                Mục tiêu tuần: <?=h($goal['target_amount'])?> <?=h($goal['unit_label'])?>
                <?php if ((int) ($goal['daily_target'] ?? 0) > 0): ?>
                  · mỗi ngày <?=h($goal['daily_target'])?> <?=h($goal['unit_label'])?>
                <?php endif; ?>
              </div>
              <?php if (!empty($goal['note'])): ?><div class="today-note"><?=h($goal['note'])?></div><?php endif; ?>
              <div class="progress mini-progress"><div class="progress-inner" style="width: <?=$goalView['progress_percent']?>%"></div></div>
              <div class="muted">
                Đã làm <?=h($goal['progress_amount'])?>/<?=h($goal['target_amount'])?> <?=h($goal['unit_label'])?>
                <?php if ($goalView['is_complete']): ?>
                  · hoàn thành
                <?php else: ?>
                  · còn <?=h($goalView['remaining_amount'])?> <?=h($goal['unit_label'])?>
                <?php endif; ?>
              </div>
            </div>
            <form method="post" class="weekly-goal-progress no-print">
              <input type="hidden" name="action" value="update_weekly_goal_progress">
              <input type="hidden" name="goal_id" value="<?=h($goal['id'])?>">
              <label><b>Đã làm</b><input type="number" name="goal_progress_amount" value="<?=h($goal['progress_amount'])?>" min="0"></label>
              <button class="btn small blue">Cập nhật</button>
            </form>
            <?php if ($parentLoggedIn): ?>
              <form method="post" class="no-print" onsubmit="return confirm('Xóa mục tiêu tuần này?')">
                <input type="hidden" name="action" value="delete_weekly_goal">
                <input type="hidden" name="goal_id" value="<?=h($goal['id'])?>">
                <button class="btn small red">Xóa</button>
              </form>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">Tuần này chưa có mục tiêu nào. Con có thể đặt mục tiêu như đọc 10 trang sách mỗi ngày.</div>
    <?php endif; ?>
  </div>
  <?php if ($parentLoggedIn): ?>
    <div class="card no-print holiday-card" style="margin-top:18px">
      <h2>🏖️ Ngày holiday</h2>
      <form method="post" class="holiday-form">
        <input type="hidden" name="action" value="add_holiday">
        <p><b>Từ ngày</b><input type="date" name="holiday_start_date" value="<?=date('Y-m-d')?>" required></p>
        <p><b>Đến ngày</b><input type="date" name="holiday_end_date" value="<?=date('Y-m-d')?>" required></p>
        <p><b>Loại holiday</b><select name="holiday_type">
          <?php foreach ($holidayTypes as $type => $holidayType): ?>
            <option value="<?=h($type)?>"><?=h($holidayType['label'])?></option>
          <?php endforeach; ?>
        </select></p>
        <p><b>Ghi chú</b><input name="holiday_note" placeholder="Ví dụ: đi chơi cùng gia đình"></p>
        <button class="btn blue">Lưu holiday</button>
      </form>
      <?php if ($holidays): ?>
        <div class="table-wrap holiday-table"><table class="table"><tr><th>Ngày</th><th>Loại</th><th>Ghi chú</th><th>Thao tác</th></tr>
          <?php foreach ($holidays as $holiday): ?>
            <?php $holidayFormId = 'holidayUpdate' . (int) $holiday['id']; ?>
            <tr>
              <td><span class="holiday-badge"><?=h($holiday['holiday_date'])?></span></td>
              <td>
                <select name="holiday_type" form="<?=h($holidayFormId)?>">
                  <?php foreach ($holidayTypes as $type => $holidayType): ?>
                    <option value="<?=h($type)?>" <?=sanitize_holiday_type($holiday['type'] ?? '', $config) === $type ? 'selected' : ''?>><?=h($holidayType['label'])?></option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td><input name="holiday_note" value="<?=h($holiday['note'] ?? '')?>" placeholder="Ghi chú" form="<?=h($holidayFormId)?>"></td>
              <td class="holiday-actions">
                <form method="post" id="<?=h($holidayFormId)?>" class="holiday-row-form">
                  <input type="hidden" name="action" value="update_holiday">
                  <input type="hidden" name="holiday_id" value="<?=h($holiday['id'])?>">
                  <button class="btn small blue">Sửa</button>
                </form>
                <form method="post" onsubmit="return confirm('Xóa ngày holiday này?')">
                  <input type="hidden" name="action" value="delete_holiday">
                  <input type="hidden" name="holiday_id" value="<?=h($holiday['id'])?>">
                  <button class="btn small red">Xóa</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </table></div>
      <?php else: ?>
        <div class="empty-state">Chưa có ngày holiday nào.</div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <div class="card" style="margin-top:18px">
    <div class="two-col"><div><b>Phần thưởng gần đạt nhất: <span data-dashboard-value="next_reward_title"><?=h($dashboard['next_reward_title'])?></span> - <span data-dashboard-value="next_reward_cost" data-suffix="★"><?=h($dashboard['next_reward_cost'])?>★</span></b></div><div style="text-align:right"><span data-dashboard-value="current_stars"><?=h($dashboard['current_stars'])?></span>/<span data-dashboard-value="next_reward_cost" data-suffix="★"><?=h($dashboard['next_reward_cost'])?>★</span></div></div>
    <div class="progress" style="margin-top:8px"><div class="progress-inner" data-dashboard-progress style="width: <?=$dashboard['progress_percent']?>%"></div></div>
    <div class="muted" style="margin-top:8px">Còn thiếu <span data-dashboard-value="missing_stars" data-suffix="★"><?=h($dashboard['missing_stars'])?>★</span> để chạm mốc thưởng tiếp theo.</div>
  </div>

  <form class="card no-print quick-card" style="margin-top:18px" method="post" enctype="multipart/form-data" data-quick-form>
    <input type="hidden" name="action" value="add_quick_action">
    <div class="quick-head">
      <h2>⚡ Ghi nhanh trong 1 chạm</h2>
      <div class="quick-date">
        <label for="quickDate"><b>Ngày áp dụng</b></label>
        <input type="date" id="quickDate" name="quick_date" value="<?=date('Y-m-d')?>" min="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>">
      </div>
    </div>
    <div class="quick-fields">
      <p>
        <b>Loại hoạt động</b>
        <select name="quick_activity_category" data-quick-category>
          <?php foreach ($activityCategories as $key => $label): ?>
            <?php if ($key === 'screen_penalty'): continue; endif; ?>
            <option value="<?=h($key)?>"><?=h($label)?></option>
          <?php endforeach; ?>
        </select>
      </p>
      <p>
        <b>Hoạt động cụ thể</b>
        <select name="quick_activity_option" data-quick-activity>
          <option value="">Chọn hoạt động</option>
          <?php foreach ($activityOptions as $key => $option): [$label, $stars, $category] = $option + [null, null, 'other']; ?>
            <option value="<?=h($key)?>" data-category="<?=h($category)?>"><?=h($label)?> (<?=($stars > 0 ? '+' : '').h($stars)?>★)</option>
          <?php endforeach; ?>
        </select>
      </p>
      <p>
        <b>Trừ sao nếu có</b>
        <select name="penalty_activity">
          <?php foreach ($penaltyOptions as $minutes => $option): [$label, $stars] = $option + [null, 0]; ?>
            <option value="<?=h($minutes)?>"><?=h($label)?><?= $stars !== 0 ? ' (' . ($stars > 0 ? '+' : '') . h($stars) . '★)' : '' ?></option>
          <?php endforeach; ?>
        </select>
      </p>
    </div>
    <div class="quick-proof">
      <label for="quickImage"><b>Ảnh minh chứng</b></label>
      <input type="file" id="quickImage" name="quick_image" accept="image/jpeg,image/png,image/webp" required>
      <span class="muted">Bắt buộc upload ảnh minh chứng trước khi lưu ghi nhanh.</span>
    </div>
    <p><b>Ghi chú</b><textarea name="quick_note" placeholder="Ví dụ: con tự hoàn thành trước giờ ăn tối..."></textarea></p>
    <button class="btn green">Lưu ghi nhanh</button>
  </form>

  <div class="grid">
    <div class="card today-card">
      <h2>✅ Ghi nhận thành tích hôm nay</h2>
      <div class="today-summary">
        <div>Hôm nay <b><?=h(date('d/m/Y'))?></b></div>
        <strong data-dashboard-value="today_stars" data-suffix="★"><?=h($dashboard['today_stars'])?>★</strong>
      </div>
      <?php if ($todayActivities): ?>
        <div class="today-list">
          <?php foreach ($todayActivities as $activity): ?>
            <?php $countedStars = get_counted_activity_stars($activity); ?>
            <div class="today-item">
              <span class="history-icon"><?=h(get_activity_icon($activity))?></span>
              <div>
                <b><?=h($activity['title'])?></b>
                <div class="muted"><?=h($activityCategories[$activity['category'] ?? 'other'] ?? 'Khác')?> · <?=h(format_activity_datetime($activity['created_at'] ?? ''))?></div>
                <span class="review-status status-<?=h(sanitize_parent_review_status($activity['status'] ?? 'pending'))?>"><?=h(get_parent_review_status_label($activity['status'] ?? 'pending'))?></span>
                <?php if (!empty($activity['note'])): ?><div class="today-note"><?=h($activity['note'])?></div><?php endif; ?>
              </div>
              <div class="today-stars <?= $countedStars<0?'danger':'positive' ?>">
                <span data-activity-star-display data-activity-id="<?=h($activity['id'])?>" data-original-stars="<?=h($activity['stars'])?>"><?=h(format_star_delta($countedStars))?></span>
                <div class="muted" data-activity-star-note data-activity-id="<?=h($activity['id'])?>"><?=($countedStars !== (int) $activity['stars']) ? 'NG: không tính ' . h(format_star_delta((int) $activity['stars'])) : ''?></div>
              </div>
              <?php if ($activity['image_path']): ?><a href="<?=h(public_url($activity['image_path'], $publicBasePath))?>" data-image-preview><img class="photo small-photo" src="<?=h(public_url($activity['image_path'], $publicBasePath))?>" alt="Ảnh minh chứng"></a><?php endif; ?>
              <form class="child-edit-form no-print" method="post">
                <input type="hidden" name="action" value="update_child_activity">
                <input type="hidden" name="id" value="<?=h($activity['id'])?>">
                <input name="child_title" value="<?=h($activity['title'])?>" required>
                <textarea name="child_note" placeholder="Ghi chú"><?=h($activity['note'] ?? '')?></textarea>
                <button class="btn small blue">Sửa task</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">Chưa có thành tích nào được ghi nhận hôm nay.</div>
      <?php endif; ?>
    </div>

    <div class="card">
      <h2>⭐ Bảng nhận sao</h2>
      <div class="reward-list">
        <?php foreach ($activityOptions as $item): [$text,$star] = $item; ?>
          <div><?=h($text)?></div><div><b>+<?=h($star)?>★</b></div>
        <?php endforeach; ?>
        <div>Chơi 1 giờ YouTube/TV/Game</div><div><b class="danger">-3★</b></div>
        <div>Chơi 2 giờ YouTube/TV/Game</div><div><b class="danger">-10★</b></div>
      </div>
      <h2 style="margin-top:18px">🎁 Bảng đổi thưởng</h2>
      <div class="reward-list">
        <?php foreach ($rewardOptions as $name=>$cost): ?><div><?=h($name)?></div><div><b><?=h($cost)?>★</b></div><?php endforeach; ?>
      </div>
      <ul class="rules">
        <li>Thứ 2 → Thứ 6: học ban ngày 2 giờ, học sáng hoặc chiều đều được.</li>
        <li>Thứ 7, Chủ nhật: không bắt buộc học 2 giờ, ưu tiên gia đình, đọc sách, vận động, sáng tạo.</li>
        <li>Làm xong việc trước, giải trí sau.</li>
      </ul>
    </div>

    <div class="card no-print">
      <h2>🎁 Đổi thưởng</h2>
      <div class="notice reward-balance">Bạn đang có <b data-dashboard-value="current_stars" data-suffix="★"><?=h($dashboard['current_stars'])?>★</b> để đổi thưởng.</div>
      <form method="post">
        <input type="hidden" name="action" value="add_reward">
        <p><b>Ngày đổi</b><input type="date" name="reward_date" value="<?=date('Y-m-d')?>" min="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>"></p>
        <p><b>Phần thưởng</b><select name="reward_title" id="rewardSelect">
          <?php foreach ($rewardOptions as $name=>$cost): ?><option value="<?=h($name)?>" data-cost="<?=h($cost)?>"><?=h($name)?> (<?=h($cost)?>★)</option><?php endforeach; ?>
        </select></p>
        <p><b>Số sao dùng</b><input type="number" name="cost" id="costInput" value="20" readonly></p>
        <p><b>Ghi chú</b><textarea name="reward_note" placeholder="Ví dụ: đổi truyện Doraemon..."></textarea></p>
        <button class="btn blue">Đổi thưởng</button>
      </form>
    </div>

    <div class="card no-print">
      <h2>➕ Thêm hoạt động nâng cao</h2>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_single">
        <p><b>Ngày</b><input type="date" name="single_date" value="<?=date('Y-m-d')?>" min="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>"></p>
        <p><b>Hoạt động</b><input name="single_title" placeholder="Ví dụ: Đọc xong 1 cuốn sách" required></p>
        <div class="form-row">
          <p><b>Loại hoạt động</b><select name="single_category">
            <?php foreach ($activityCategories as $key => $label): ?>
              <option value="<?=h($key)?>"><?=h($label)?></option>
            <?php endforeach; ?>
          </select></p>
          <p><b>Sao</b><input type="number" name="single_stars" value="5"></p>
        </div>
        <p><b>Ảnh minh chứng</b><input type="file" name="single_image" accept="image/jpeg,image/png,image/webp"></p>
        <p><b>Ghi chú</b><textarea name="single_note"></textarea></p>
        <button class="btn">Lưu hoạt động</button>
      </form>
    </div>
  </div>

  <div class="card" style="margin-top:18px">
    <h2>📅 Lịch sử thành tích</h2>
    <div class="table-wrap"><table class="table history-table"><tr><th>Ngày áp dụng</th><th class="mobile-hide">Thời gian ghi nhận</th><th class="mobile-hide">Icon</th><th class="mobile-hide">Loại</th><th>Hoạt động</th><th>Sao</th><th class="mobile-hide">Ghi chú</th><th>Ảnh</th><th class="mobile-hide history-parent-feedback-col">Phản hồi bố mẹ</th><th class="no-print mobile-hide history-actions-col">Thao tác</th></tr>
      <?php foreach ($activities as $a): ?>
        <?php $feedbackText = ((int) ($a['parent_liked'] ?? 0) === 1 ? '❤️ ' : '') . ($a['parent_comment'] ?? ''); ?>
        <?php $countedStars = get_counted_activity_stars($a); ?>
        <?php $activityHoliday = $holidaysByDate[$a['activity_date']] ?? null; ?>
        <tr>
          <td>
            <?=h($a['activity_date'])?>
            <?php if ($activityHoliday): ?>
              <div class="holiday-badge"><?=h(get_holiday_type_label($activityHoliday, $config))?></div>
            <?php endif; ?>
          </td>
          <td class="mobile-hide"><?=h(format_activity_datetime($a['created_at'] ?? ''))?></td>
          <td class="mobile-hide"><span class="history-icon"><?=h(get_activity_icon($a))?></span></td>
          <td class="mobile-hide"><span class="badge"><?=h($activityCategories[$a['category'] ?? 'other'] ?? 'Khác')?></span></td>
          <td>
            <b><?=h($a['title'])?></b>
            <div class="history-mobile-meta">
              <?=h(format_activity_datetime($a['created_at'] ?? ''))?> · <?=h($activityCategories[$a['category'] ?? 'other'] ?? 'Khác')?>
              <?php if (!empty($a['note'])): ?><br><?=h($a['note'])?><?php endif; ?>
            </div>
            <span class="review-status status-<?=h(sanitize_parent_review_status($a['status'] ?? 'pending'))?>" data-status-display><?=h(get_parent_review_status_label($a['status'] ?? 'pending'))?></span>
          </td>
          <td>
            <b class="<?= $countedStars<0?'star minus':'positive' ?>" data-activity-star-display data-activity-id="<?=h($a['id'])?>" data-original-stars="<?=h($a['stars'])?>"><?=h(format_star_delta($countedStars))?></b>
            <div class="muted" data-activity-star-note data-activity-id="<?=h($a['id'])?>"><?=($countedStars !== (int) $a['stars']) ? 'NG: không tính ' . h(format_star_delta((int) $a['stars'])) : ''?></div>
          </td>
          <td class="mobile-hide"><?=h($a['note'])?></td>
          <td><?php if ($a['image_path']): ?><a href="<?=h(public_url($a['image_path'], $publicBasePath))?>" data-image-preview><img class="photo" src="<?=h(public_url($a['image_path'], $publicBasePath))?>" alt="Ảnh minh chứng"></a><?php endif; ?></td>
          <td class="mobile-hide history-parent-feedback-col">
            <?php if ($parentLoggedIn): ?>
              <form class="parent-feedback no-print" method="post" data-parent-feedback>
                <input type="hidden" name="action" value="update_activity_parent_feedback">
                <input type="hidden" name="id" value="<?=h($a['id'])?>">
                <select name="parent_status">
                  <?php foreach ($parentReviewStatusOptions as $status => $label): ?>
                    <option value="<?=h($status)?>" <?=sanitize_parent_review_status($a['status'] ?? 'pending') === $status ? 'selected' : ''?>><?=h($label)?></option>
                  <?php endforeach; ?>
                </select>
                <label class="like-toggle"><input type="checkbox" name="parent_liked" value="1" <?=((int) ($a['parent_liked'] ?? 0) === 1) ? 'checked' : ''?>> ❤️ Like</label>
                <textarea name="parent_comment" placeholder="Bố mẹ nhận xét..."><?=h($a['parent_comment'] ?? '')?></textarea>
                <div class="parent-feedback-actions"><button class="btn small blue">Lưu</button><span class="muted" data-feedback-status></span></div>
              </form>
            <?php else: ?>
              <div class="muted no-print">Bố mẹ đăng nhập để phản hồi.</div>
            <?php endif; ?>
            <div class="print-feedback parent-feedback-text" data-feedback-print><?=h($feedbackText)?></div>
            <div class="parent-feedback-text no-print" data-feedback-display><?=h($feedbackText)?></div>
          </td>
          <td class="no-print mobile-hide history-actions-col">
            <?php if ($parentLoggedIn): ?>
              <div class="history-actions">
                <details class="history-edit">
                  <summary>Sửa</summary>
                  <form method="post">
                    <input type="hidden" name="action" value="update_activity_parent_edit">
                    <input type="hidden" name="id" value="<?=h($a['id'])?>">
                    <input name="parent_edit_title" value="<?=h($a['title'])?>" required>
                    <textarea name="parent_edit_note" placeholder="Ghi chú"><?=h($a['note'] ?? '')?></textarea>
                    <button class="btn small blue">Lưu sửa</button>
                  </form>
                </details>
                <form method="post" onsubmit="return confirm('Xóa dòng này?')"><input type="hidden" name="action" value="delete_activity"><input type="hidden" name="id" value="<?=h($a['id'])?>"><button class="btn small red">Xóa</button></form>
              </div>
            <?php else: ?><span class="muted">Cần login</span><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table></div>
  </div>

  <div class="card" style="margin-top:18px">
    <h2>🎁 Lịch sử đổi thưởng</h2>
    <div class="table-wrap"><table class="table"><tr><th>Ngày</th><th>Phần thưởng</th><th>Sao dùng</th><th>Ghi chú</th><th class="no-print"></th></tr>
      <?php foreach ($rewards as $r): ?><tr><td><?=h($r['reward_date'])?></td><td><?=h($r['title'])?></td><td><b class="danger">-<?=h($r['cost'])?>★</b></td><td><?=h($r['note'])?></td><td class="no-print"><?php if ($parentLoggedIn): ?><form method="post" onsubmit="return confirm('Xóa phần thưởng này?')"><input type="hidden" name="action" value="delete_reward"><input type="hidden" name="id" value="<?=h($r['id'])?>"><button class="btn small red">Xóa</button></form><?php else: ?><span class="muted">Cần login</span><?php endif; ?></td></tr><?php endforeach; ?>
    </table></div>
  </div>

  <?php if ($parentLoggedIn): ?><div class="card parent-audit" style="margin-top:18px">
    <details class="audit-details">
      <summary>🧾 Nhật ký audit cho bố mẹ <span class="muted">(<?=h(count($auditLogs))?> dòng gần nhất)</span></summary>
      <div class="table-wrap"><table class="table"><tr><th>Thời gian</th><th>Người thực hiện</th><th>Hành động</th><th>Loại</th><th>Nội dung</th></tr>
        <?php foreach ($auditLogs as $log): ?><tr><td><?=h($log['created_at'])?></td><td><?=h($log['actor'])?></td><td><span class="badge"><?=h($log['action'])?></span></td><td><?=h($log['entity_type'])?></td><td><?=h($log['description'])?></td></tr><?php endforeach; ?>
      </table></div>
    </details>
  </div><?php endif; ?>
  <div class="footer">Con làm được! ⭐ Cố gắng mỗi ngày nhé! 🐰 <span class="app-version">v<?=h($appVersion)?></span></div>
</div>
<div class="image-modal" data-image-modal hidden>
  <button class="image-modal-close" type="button" data-image-modal-close aria-label="Đóng preview ảnh">×</button>
  <img src="" alt="Ảnh minh chứng phóng to" data-image-modal-img>
</div>
<div class="quick-scroll no-print" aria-label="Di chuyển nhanh">
  <button type="button" class="quick-scroll-btn" data-scroll-target="top" aria-label="Lên đầu trang" title="Lên đầu trang">↑</button>
  <button type="button" class="quick-scroll-btn" data-scroll-target="bottom" aria-label="Xuống cuối trang" title="Xuống cuối trang">↓</button>
</div>
<script>
const rewardSelect=document.getElementById('rewardSelect'), costInput=document.getElementById('costInput');
if(rewardSelect){function syncReward(){costInput.value=rewardSelect.options[rewardSelect.selectedIndex].dataset.cost} rewardSelect.addEventListener('change',syncReward); syncReward();}
const quickForm=document.querySelector('[data-quick-form]');
if(quickForm){
  const categorySelect=quickForm.querySelector('[data-quick-category]');
  const activitySelect=quickForm.querySelector('[data-quick-activity]');
  const activityOptions=activitySelect?Array.from(activitySelect.options):[];

  function syncQuickActivities(){
    if(!categorySelect||!activitySelect){return;}
    const selectedCategory=categorySelect.value;
    let firstVisible='';
    activityOptions.forEach((option)=>{
      const category=option.dataset.category||'';
      const visible=option.value===''||category===selectedCategory;
      option.hidden=!visible;
      option.disabled=!visible;
      if(visible&&option.value!==''&&firstVisible===''){firstVisible=option.value;}
    });
    if(activitySelect.selectedOptions.length&&activitySelect.selectedOptions[0].disabled){
      activitySelect.value=firstVisible;
    }
  }

  if(categorySelect){categorySelect.addEventListener('change',syncQuickActivities);}
  syncQuickActivities();

  quickForm.addEventListener('submit',(event)=>{
    const image=quickForm.querySelector('input[name="quick_image"]');
    if(image&&image.files.length===0){
      event.preventDefault();
      alert('Ghi nhanh cần có ảnh minh chứng. Vui lòng upload ảnh trước khi lưu.');
    }
  });
}
document.querySelectorAll('[data-parent-feedback]').forEach((form)=>{
  const checkbox=form.querySelector('input[name="parent_liked"]');
  const status=form.querySelector('[data-feedback-status]');
  const display=form.parentElement.querySelector('[data-feedback-display]');
  const printDisplay=form.parentElement.querySelector('[data-feedback-print]');
  const statusDisplay=form.closest('tr')?.querySelector('[data-status-display]');
  let saveSeq=0;

  function updateDashboard(dashboard){
    if(!dashboard){return;}
    document.querySelectorAll('[data-dashboard-value]').forEach((element)=>{
      const key=element.dataset.dashboardValue;
      if(!Object.prototype.hasOwnProperty.call(dashboard,key)){return;}
      element.textContent=String(dashboard[key])+(element.dataset.suffix||'');
    });
    document.querySelectorAll('[data-dashboard-progress]').forEach((element)=>{
      element.style.width=dashboard.progress_percent+'%';
    });
  }

  function formatStars(stars){
    return (stars>0?'+':'')+stars+'★';
  }

  function updateActivityStars(id,status){
    document.querySelectorAll('[data-activity-star-display][data-activity-id="'+id+'"]').forEach((element)=>{
      const originalStars=parseInt(element.dataset.originalStars||'0',10);
      const countedStars=(status==='ng'&&originalStars>0)?0:originalStars;
      element.textContent=formatStars(countedStars);
      element.classList.toggle('star',countedStars<0);
      element.classList.toggle('minus',countedStars<0);
      element.classList.toggle('danger',countedStars<0);
      element.classList.toggle('positive',countedStars>=0);
    });
    document.querySelectorAll('[data-activity-star-note][data-activity-id="'+id+'"]').forEach((element)=>{
      const starDisplay=document.querySelector('[data-activity-star-display][data-activity-id="'+id+'"]');
      const originalStars=parseInt(starDisplay?.dataset.originalStars||'0',10);
      element.textContent=(status==='ng'&&originalStars>0)?'NG: không tính '+formatStars(originalStars):'';
    });
  }

  async function saveFeedback(){
    const currentSeq=++saveSeq;
    form.classList.add('is-saving');
    if(status){status.textContent='Đang lưu...';}

    try{
      const response=await fetch(window.location.pathname,{
        method:'POST',
        body:new FormData(form),
        headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}
      });
      const payload=await response.json();
      if(currentSeq!==saveSeq){return;}
      if(!response.ok||!payload.ok){throw new Error(payload.message||'Không lưu được phản hồi.');}
      if(display){display.textContent=payload.display_text;}
      if(printDisplay){printDisplay.textContent=payload.display_text;}
      if(statusDisplay){
        statusDisplay.textContent=payload.status_label;
        statusDisplay.className='review-status status-'+payload.status;
      }
      updateDashboard(payload.dashboard);
      updateActivityStars(payload.id,payload.status);
      if(status){status.textContent='Đã lưu';}
    }catch(error){
      if(currentSeq!==saveSeq){return;}
      if(status){status.textContent=error.message;}
    }finally{
      if(currentSeq===saveSeq){form.classList.remove('is-saving');}
    }
  }

  form.addEventListener('submit',(event)=>{
    event.preventDefault();
    saveFeedback();
  });
  if(checkbox){checkbox.addEventListener('change',saveFeedback);}
  const parentStatus=form.querySelector('select[name="parent_status"]');
  if(parentStatus){parentStatus.addEventListener('change',saveFeedback);}
});
const imageModal=document.querySelector('[data-image-modal]');
const imageModalImg=document.querySelector('[data-image-modal-img]');
const imageModalClose=document.querySelector('[data-image-modal-close]');
function closeImageModal(){
  if(!imageModal||!imageModalImg){return;}
  imageModal.hidden=true;
  imageModalImg.src='';
}
document.querySelectorAll('[data-image-preview]').forEach((link)=>{
  link.addEventListener('click',(event)=>{
    event.preventDefault();
    if(!imageModal||!imageModalImg){return;}
    imageModalImg.src=link.href;
    imageModal.hidden=false;
  });
});
if(imageModalClose){imageModalClose.addEventListener('click',closeImageModal);}
if(imageModal){
  imageModal.addEventListener('click',(event)=>{if(event.target===imageModal){closeImageModal();}});
  document.addEventListener('keydown',(event)=>{if(event.key==='Escape'&&!imageModal.hidden){closeImageModal();}});
}
document.addEventListener('keydown',(event)=>{
  if(event.defaultPrevented||event.altKey||event.ctrlKey||event.metaKey||event.shiftKey){return;}
  const target=event.target;
  const isTypingTarget=target&&(
    target.isContentEditable||
    ['INPUT','TEXTAREA','SELECT'].includes(target.tagName)
  );
  if(isTypingTarget){return;}
  if(event.key==='Home'){
    event.preventDefault();
    window.scrollTo({top:0,behavior:'smooth'});
  }
  if(event.key==='End'){
    event.preventDefault();
    window.scrollTo({top:document.documentElement.scrollHeight,behavior:'smooth'});
  }
});
document.querySelectorAll('[data-scroll-target]').forEach((button)=>{
  button.addEventListener('click',()=>{
    const top=button.dataset.scrollTarget==='top'?0:document.documentElement.scrollHeight;
    window.scrollTo({top,behavior:'smooth'});
  });
});
</script>
</body></html>
