<?php
session_start();
$dbFile = __DIR__ . '/../database/summer.db';
$uploadDir = __DIR__ . '/uploads';
if (!is_dir(dirname($dbFile))) mkdir(dirname($dbFile), 0775, true);
if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS activities (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    activity_date TEXT NOT NULL,
    title TEXT NOT NULL,
    stars INTEGER NOT NULL,
    note TEXT,
    image_path TEXT,
    status TEXT NOT NULL DEFAULT 'approved',
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
)");
$db->exec("CREATE TABLE IF NOT EXISTS rewards (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reward_date TEXT NOT NULL,
    title TEXT NOT NULL,
    cost INTEGER NOT NULL,
    note TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
)");

$activityOptions = [
    'study_2h' => ['Học ban ngày 2 giờ (T2-T6)', 2],
    'read_book' => ['Đọc sách', 1],
    'copy_sutra' => ['Chép kinh', 1],
    'write_story' => ['Viết truyện', 1],
    'journal' => ['Viết nhật ký', 1],
    'exercise' => ['Tập thể dục / vận động', 1],
    'creative' => ['Vẽ tranh / sáng tạo', 1],
    'housework' => ['Việc nhà được giao', 1],
    'water_plant' => ['Tưới cây / chăm cây', 1],
    'feed_fish' => ['Cho cá ăn / chăm bể cá', 1],
    'clean_room' => ['Dọn bàn học, phòng ngủ', 1],
    'screen_ok' => ['Không vượt thời gian màn hình', 1],
];
$penaltyOptions = [
    0 => ['Không vượt giờ / không chơi quá quy định', 0],
    60 => ['Chơi 1 giờ YouTube/TV/Game', -3],
    120 => ['Chơi 2 giờ YouTube/TV/Game', -10],
];
$rewardOptions = [
    'Hoạt động gia đình' => 20,
    '1 quyển truyện' => 25,
    '1 cây nhỏ' => 25,
    'Phần thưởng tự chọn bất kỳ' => 35,
    'Về ngủ chơi nhà bà nội' => 50,
];
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function redirect_home() { header('Location: index.php'); exit; }
function save_uploaded_image($field, $uploadDir) {
    if (empty($_FILES[$field]['name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) return null;
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
    $mime = mime_content_type($_FILES[$field]['tmp_name']);
    if (!isset($allowed[$mime]) || $_FILES[$field]['size'] > 5 * 1024 * 1024) {
        $_SESSION['msg'] = 'Ảnh không hợp lệ hoặc lớn hơn 5MB.';
        redirect_home();
    }
    $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . '/' . $filename);
    return 'uploads/' . $filename;
}
function insert_activity($db, $date, $title, $stars, $note, $imagePath) {
    $stmt = $db->prepare('INSERT INTO activities(activity_date,title,stars,note,image_path,status) VALUES(?,?,?,?,?,?)');
    $stmt->execute([$date, $title, $stars, $note, $imagePath, 'approved']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add_daily') {
        $date = $_POST['activity_date'] ?: date('Y-m-d');
        $note = trim($_POST['note'] ?? '');
        $imagePath = save_uploaded_image('image', $uploadDir);
        $count = 0; $total = 0;
        foreach (($_POST['activities'] ?? []) as $key) {
            if (isset($activityOptions[$key])) {
                [$title, $stars] = $activityOptions[$key];
                insert_activity($db, $date, $title, $stars, $note, $imagePath);
                $count++; $total += $stars;
                $imagePath = null; // gắn ảnh cho dòng đầu tiên để tránh lặp ảnh nhiều lần
            }
        }
        $screen = (int)($_POST['screen_minutes'] ?? 0);
        if (isset($penaltyOptions[$screen]) && $penaltyOptions[$screen][1] !== 0) {
            [$title, $stars] = $penaltyOptions[$screen];
            insert_activity($db, $date, $title, $stars, $note, $imagePath);
            $count++; $total += $stars;
        }
        $_SESSION['msg'] = $count > 0 ? "Đã ghi nhận $count mục, tổng {$total}★." : 'Chưa chọn hoạt động nào.';
        redirect_home();
    }
    if ($action === 'add_single') {
        $date = $_POST['single_date'] ?: date('Y-m-d');
        $title = trim($_POST['single_title'] ?? '');
        $stars = (int)($_POST['single_stars'] ?? 0);
        $note = trim($_POST['single_note'] ?? '');
        $imagePath = save_uploaded_image('single_image', $uploadDir);
        if ($title !== '') {
            insert_activity($db, $date, $title, $stars, $note, $imagePath);
            $_SESSION['msg'] = 'Đã ghi nhận mục bổ sung.';
        }
        redirect_home();
    }
    if ($action === 'add_reward') {
        $date = $_POST['reward_date'] ?: date('Y-m-d');
        $title = trim($_POST['reward_title'] ?? '');
        $cost = (int)($_POST['cost'] ?? 0);
        $note = trim($_POST['reward_note'] ?? '');
        if ($title !== '' && $cost > 0) {
            $stmt = $db->prepare('INSERT INTO rewards(reward_date,title,cost,note) VALUES(?,?,?,?)');
            $stmt->execute([$date, $title, $cost, $note]);
            $_SESSION['msg'] = 'Đã đổi thưởng.';
        }
        redirect_home();
    }
    if ($action === 'delete_activity') {
        $id = (int)$_POST['id'];
        $row = $db->query('SELECT image_path FROM activities WHERE id=' . $id)->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['image_path']) @unlink(__DIR__ . '/' . $row['image_path']);
        $db->prepare('DELETE FROM activities WHERE id=?')->execute([$id]);
        redirect_home();
    }
    if ($action === 'delete_reward') {
        $db->prepare('DELETE FROM rewards WHERE id=?')->execute([(int)$_POST['id']]);
        redirect_home();
    }
}

$totalEarned = (int)$db->query("SELECT COALESCE(SUM(stars),0) FROM activities WHERE status='approved'")->fetchColumn();
$totalSpent = (int)$db->query("SELECT COALESCE(SUM(cost),0) FROM rewards")->fetchColumn();
$currentStars = $totalEarned - $totalSpent;
$todayStars = (int)$db->query("SELECT COALESCE(SUM(stars),0) FROM activities WHERE activity_date=date('now','localtime') AND status='approved'")->fetchColumn();
$monthStars = (int)$db->query("SELECT COALESCE(SUM(stars),0) FROM activities WHERE substr(activity_date,1,7)=strftime('%Y-%m','now','localtime') AND status='approved'")->fetchColumn();
$activities = $db->query('SELECT * FROM activities ORDER BY activity_date DESC, id DESC LIMIT 80')->fetchAll(PDO::FETCH_ASSOC);
$rewards = $db->query('SELECT * FROM rewards ORDER BY reward_date DESC, id DESC LIMIT 30')->fetchAll(PDO::FETCH_ASSOC);
function levelName($stars) {
    if ($stars >= 300) return '👑 Công chúa mùa hè';
    if ($stars >= 200) return '🦄 Nhà sáng tạo';
    if ($stars >= 100) return '🌻 Siêu chăm chỉ';
    if ($stars >= 50) return '🌱 Mầm xanh';
    return '🐣 Chim non';
}
$nextRewardCost = 25;
foreach ([20,25,35,50,100,200,300] as $cost) { if ($currentStars < $cost) { $nextRewardCost = $cost; break; } }
$progress = min(100, max(0, $currentStars / max(1,$nextRewardCost) * 100));
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
    <div class="stars">Hiện có: <?=h($currentStars)?>★</div>
    <div class="pill">Đã nhận: <?=h($totalEarned)?>★</div><div class="pill">Đã đổi: <?=h($totalSpent)?>★</div><div class="pill">Danh hiệu: <?=h(levelName($currentStars))?></div>
    <div class="stat-grid">
      <div class="stat">Hôm nay<b><?=h($todayStars)?>★</b></div>
      <div class="stat">Tháng này<b><?=h($monthStars)?>★</b></div>
      <div class="stat">Mốc tiếp theo<b><?=h($nextRewardCost)?>★</b></div>
      <div class="stat">Còn thiếu<b><?=h(max(0,$nextRewardCost-$currentStars))?>★</b></div>
    </div>
  </div>
  <?php if (!empty($_SESSION['msg'])): ?><div class="notice"><?=h($_SESSION['msg']); unset($_SESSION['msg']);?></div><?php endif; ?>
  <div class="card" style="margin-top:18px">
    <div class="two-col"><div><b>Tiến độ tới mốc <?=h($nextRewardCost)?>★</b></div><div style="text-align:right"><?=h($currentStars)?>/<?=h($nextRewardCost)?>★</div></div>
    <div class="progress" style="margin-top:8px"><div class="progress-inner" style="width: <?=$progress?>%"></div></div>
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
      <form method="post">
        <input type="hidden" name="action" value="add_reward">
        <p><b>Ngày đổi</b><input type="date" name="reward_date" value="<?=date('Y-m-d')?>"></p>
        <p><b>Phần thưởng</b><select name="reward_title" id="rewardSelect">
          <?php foreach ($rewardOptions as $name=>$cost): ?><option value="<?=h($name)?>" data-cost="<?=h($cost)?>"><?=h($name)?> (<?=h($cost)?>★)</option><?php endforeach; ?>
        </select></p>
        <p><b>Số sao dùng</b><input type="number" name="cost" id="costInput" value="20"></p>
        <p><b>Ghi chú</b><textarea name="reward_note" placeholder="Ví dụ: đổi truyện Doraemon..."></textarea></p>
        <button class="btn blue">Đổi thưởng</button>
      </form>
    </div>

    <div class="card no-print">
      <h2>➕ Ghi mục đặc biệt</h2>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_single">
        <p><b>Ngày</b><input type="date" name="single_date" value="<?=date('Y-m-d')?>"></p>
        <p><b>Tên hoạt động</b><input name="single_title" placeholder="Ví dụ: Đọc xong 1 cuốn sách"></p>
        <p><b>Sao</b><input type="number" name="single_stars" value="5"></p>
        <p><b>Ảnh</b><input type="file" name="single_image" accept="image/*"></p>
        <p><b>Ghi chú</b><textarea name="single_note"></textarea></p>
        <button class="btn">Lưu mục đặc biệt</button>
      </form>
    </div>
  </div>

  <div class="card" style="margin-top:18px">
    <h2>📅 Lịch sử thành tích</h2>
    <div class="table-wrap"><table class="table"><tr><th>Ngày</th><th>Hoạt động</th><th>Sao</th><th>Ghi chú</th><th>Ảnh</th><th class="no-print"></th></tr>
      <?php foreach ($activities as $a): ?><tr><td><?=h($a['activity_date'])?></td><td><?=h($a['title'])?></td><td><b class="<?= $a['stars']<0?'star minus':'positive' ?>"><?=($a['stars']>0?'+':'').h($a['stars'])?>★</b></td><td><?=h($a['note'])?></td><td><?php if ($a['image_path']): ?><a href="<?=h($a['image_path'])?>" target="_blank"><img class="photo" src="<?=h($a['image_path'])?>"></a><?php endif; ?></td><td class="no-print"><form method="post" onsubmit="return confirm('Xóa dòng này?')"><input type="hidden" name="action" value="delete_activity"><input type="hidden" name="id" value="<?=h($a['id'])?>"><button class="btn small red">Xóa</button></form></td></tr><?php endforeach; ?>
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
</script>
</body></html>
