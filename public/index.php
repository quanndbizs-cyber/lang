<?php
session_start();

$config = require __DIR__ . '/../app/config.php';

require __DIR__ . '/../app/db.php';
require __DIR__ . '/../app/functions.php';
require __DIR__ . '/../app/actions.php';

$db = connect_database($config);

handle_request($db, $config);

$activityOptions = $config['activity_options'];
$activityCategories = $config['activity_categories'];
$penaltyOptions = $config['penalty_options'];
$quickActions = $config['quick_actions'];
$rewardOptions = $config['reward_options'];

$dashboard = build_dashboard_stats(
    fetch_activity_totals($db),
    fetch_total_spent($db),
    $rewardOptions
);
$activities = fetch_activities($db);
$rewards = fetch_rewards($db);
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>⭐ Bảng sao mùa hè</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="wrap">
  <div class="hero">
    <h1>🌈 BẢNG SAO MÙA HÈ ⭐</h1>
    <div class="sub">Học tốt · Vui khỏe · Tự lập · Sáng tạo · Ít màn hình</div>
    <p>Mỗi ngày cố gắng hơn hôm qua một chút nhé!</p>
    <div class="stars">Hiện có: <?=h($dashboard['current_stars'])?>★</div>
    <div class="pill">Đã nhận: <?=h($dashboard['total_earned'])?>★</div><div class="pill">Đã đổi: <?=h($dashboard['total_spent'])?>★</div><div class="pill">Danh hiệu: <?=h($dashboard['level_name'])?></div>
    <div class="stat-grid">
      <div class="stat">Tổng sao<b><?=h($dashboard['current_stars'])?>★</b></div>
      <div class="stat">Hôm nay<b><?=h($dashboard['today_stars'])?>★</b></div>
      <div class="stat">Tuần này<b><?=h($dashboard['week_stars'])?>★</b></div>
      <div class="stat">Tháng này<b><?=h($dashboard['month_stars'])?>★</b></div>
    </div>
  </div>
  <?php if (!empty($_SESSION['msg'])): ?><div class="notice"><?=h($_SESSION['msg']); unset($_SESSION['msg']);?></div><?php endif; ?>
  <div class="card" style="margin-top:18px">
    <div class="two-col"><div><b>Phần thưởng gần đạt nhất: <?=h($dashboard['next_reward_title'])?> - <?=h($dashboard['next_reward_cost'])?>★</b></div><div style="text-align:right"><?=h($dashboard['current_stars'])?>/<?=h($dashboard['next_reward_cost'])?>★</div></div>
    <div class="progress" style="margin-top:8px"><div class="progress-inner" style="width: <?=$dashboard['progress_percent']?>%"></div></div>
    <div class="muted" style="margin-top:8px">Còn thiếu <?=h($dashboard['missing_stars'])?>★ để chạm mốc thưởng tiếp theo.</div>
  </div>

  <div class="card no-print quick-card" style="margin-top:18px">
    <div class="quick-head">
      <h2>⚡ Ghi nhanh trong 1 chạm</h2>
      <div class="quick-date">
        <label for="quickDate"><b>Ngày áp dụng</b></label>
        <input type="date" id="quickDate" value="<?=date('Y-m-d')?>">
      </div>
    </div>
    <div class="quick-grid">
      <?php foreach ($quickActions as $key => $quickAction): [$label, $stars] = $quickAction; ?>
      <form method="post">
        <input type="hidden" name="action" value="add_quick_action">
        <input type="hidden" name="quick_action" value="<?=h($key)?>">
        <input type="hidden" name="quick_date" value="<?=date('Y-m-d')?>" data-quick-date>
        <button class="quick-btn <?= $stars < 0 ? 'danger' : 'good' ?>" type="submit">
          <span><?=h($label)?></span>
          <strong><?=($stars > 0 ? '+' : '').h($stars)?>★</strong>
        </button>
      </form>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="grid">
    <div class="card no-print">
      <h2>✅ Ghi nhận thành tích hôm nay</h2>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_daily">
        <p><b>Ngày</b><input type="date" name="activity_date" value="<?=date('Y-m-d')?>"></p>
        <div class="activities">
          <?php foreach ($activityOptions as $key=>$item): [$text,$star] = $item; ?>
          <div class="rowline"><label><input type="checkbox" name="activities[]" value="<?=h($key)?>"><?=h($text)?></label><div class="star">+<?=h($star)?>★</div></div>
          <?php endforeach; ?>
        </div>
        <p><b>Thời gian màn hình</b>
          <select name="screen_minutes">
            <?php foreach ($penaltyOptions as $min=>$item): [$text,$star] = $item; ?>
              <option value="<?=h($min)?>"><?=h($text)?><?= $star ? ' ('.h($star).'★)' : '' ?></option>
            <?php endforeach; ?>
          </select>
        </p>
        <p><b>Ảnh minh chứng</b><input type="file" name="image" accept="image/*"><span class="muted">Ảnh sẽ gắn với mục đầu tiên được chọn.</span></p>
        <p><b>Ghi chú</b><textarea name="note" placeholder="Ví dụ: Đọc 20 trang, vẽ cây lan, giúp mẹ rửa bát..."></textarea></p>
        <button class="btn green">Lưu thành tích</button>
      </form>
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
      <div class="notice reward-balance">Bạn đang có <b><?=h($dashboard['current_stars'])?>★</b> để đổi thưởng.</div>
      <form method="post">
        <input type="hidden" name="action" value="add_reward">
        <p><b>Ngày đổi</b><input type="date" name="reward_date" value="<?=date('Y-m-d')?>"></p>
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
        <p><b>Ngày</b><input type="date" name="single_date" value="<?=date('Y-m-d')?>"></p>
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
    <div class="table-wrap"><table class="table"><tr><th>Ngày</th><th>Icon</th><th>Loại</th><th>Hoạt động</th><th>Sao</th><th>Ghi chú</th><th>Ảnh</th><th class="no-print"></th></tr>
      <?php foreach ($activities as $a): ?><tr><td><?=h($a['activity_date'])?></td><td><span class="history-icon"><?=h(get_activity_icon($a))?></span></td><td><span class="badge"><?=h($activityCategories[$a['category'] ?? 'other'] ?? 'Khác')?></span></td><td><?=h($a['title'])?></td><td><b class="<?= $a['stars']<0?'star minus':'positive' ?>"><?=($a['stars']>0?'+':'').h($a['stars'])?>★</b></td><td><?=h($a['note'])?></td><td><?php if ($a['image_path']): ?><a href="<?=h($a['image_path'])?>" target="_blank"><img class="photo" src="<?=h($a['image_path'])?>"></a><?php endif; ?></td><td class="no-print"><form method="post" onsubmit="return confirm('Xóa dòng này?')"><input type="hidden" name="action" value="delete_activity"><input type="hidden" name="id" value="<?=h($a['id'])?>"><button class="btn small red">Xóa</button></form></td></tr><?php endforeach; ?>
    </table></div>
  </div>

  <div class="card" style="margin-top:18px">
    <h2>🎁 Lịch sử đổi thưởng</h2>
    <div class="table-wrap"><table class="table"><tr><th>Ngày</th><th>Phần thưởng</th><th>Sao dùng</th><th>Ghi chú</th><th class="no-print"></th></tr>
      <?php foreach ($rewards as $r): ?><tr><td><?=h($r['reward_date'])?></td><td><?=h($r['title'])?></td><td><b class="danger">-<?=h($r['cost'])?>★</b></td><td><?=h($r['note'])?></td><td class="no-print"><form method="post" onsubmit="return confirm('Xóa phần thưởng này?')"><input type="hidden" name="action" value="delete_reward"><input type="hidden" name="id" value="<?=h($r['id'])?>"><button class="btn small red">Xóa</button></form></td></tr><?php endforeach; ?>
    </table></div>
  </div>
  <div class="footer">Con làm được! ⭐ Cố gắng mỗi ngày nhé! 🐰</div>
</div>
<script>
const rewardSelect=document.getElementById('rewardSelect'), costInput=document.getElementById('costInput');
if(rewardSelect){function syncReward(){costInput.value=rewardSelect.options[rewardSelect.selectedIndex].dataset.cost} rewardSelect.addEventListener('change',syncReward); syncReward();}
const quickDate=document.getElementById('quickDate');
if(quickDate){function syncQuickDates(){document.querySelectorAll('[data-quick-date]').forEach((input)=>{input.value=quickDate.value;});} quickDate.addEventListener('change',syncQuickDates); syncQuickDates();}
</script>
</body></html>
