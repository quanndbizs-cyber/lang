<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa Học Ngôn Ngữ - Lộ Trình Theo Cấp Độ</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .header-bar { background: #0284c7; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .stats-box { font-weight: bold; background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; }
        .lang-tabs, .level-tabs { display: flex; gap: 10px; margin: 15px 0; flex-wrap: wrap; }
        .lang-tab { padding: 8px 16px; border-radius: 8px; background: #e2e8f0; text-decoration: none; color: #334155; font-weight: bold; }
        .lang-tab.active { background: #0284c7; color: white; }
        .level-btn { padding: 8px 14px; border-radius: 6px; background: #ffffff; border: 2px solid #cbd5e1; cursor: pointer; font-weight: bold; color: #475569; }
        .level-btn.active { border-color: #0284c7; background: #e0f2fe; color: #0369a1; }
        .lesson-node { background: white; border-radius: 12px; padding: 18px; margin-bottom: 12px; border-left: 5px solid #cbd5e1; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.04); }
        .lesson-node.unlocked { border-left-color: #0284c7; }
        .lesson-node.completed { border-left-color: #22c55e; background: #f0fdf4; }
        .btn-start { background: #0284c7; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-start.locked { background: #94a3b8; pointer-events: none; }
        .level-group { display: none; }
        .level-group.active { display: block; }
    </style>
</head>
<body>

<div class="header-bar">
    <h2><a href="index.php?action=home&lang=<?=$lang?>">📚</a> Hành Trình Học Tập</h2>
    <div class="stats-box">
        🔥 Streak: <?= $_SESSION['user_streak'] ?? 1 ?> ngày | 🪙 XP: <?= $_SESSION['user_xp'] ?? 0 ?>
    </div>
</div>

<div class="container" style="max-width: 800px; margin: 0 auto; padding: 20px;">

    <!-- 1. Thanh chọn Ngôn ngữ -->
    <div class="lang-tabs">
        <?php foreach ($languages as $code => $info): ?>
            <a href="index.php?action=courses&lang=<?= $code ?>" class="lang-tab<?= $lang === $code ? ' active"; style="display: block;" ' : '";  style="display: none;"' ?>>
                <?= $info['icon'] ?> <?= htmlspecialchars($info['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
<!-- 1. Dropdown chọn Cấp độ (Level) -->
<div class="level-selector" style="margin: 20px 0;">
    <label for="levelSelect" style="font-weight: bold; font-size: 1.1em;">📍 Chọn Cấp Độ Học Tập: </label>
    <select id="levelSelect" onchange="selectLevel(this.value)" style="padding: 8px 12px; font-size: 1em; border-radius: 6px; border: 2px solid #0284c7; font-weight: bold; color: #0284c7;">
        <?php 
            $firstLevelKey = '';
            foreach ($levels as $levelKey => $levelData): 
                if (empty($firstLevelKey)) $firstLevelKey = $levelKey;
        ?>
            <option value="<?= $levelKey ?>">📖 <?= htmlspecialchars($levelData['name'] ?? $levelKey) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- 2. Bản đồ danh sách bài học theo Level -->
<div class="lessons-container">
    <?php foreach ($levels as $levelKey => $levelData): ?>
        <div id="level-group-<?= $levelKey ?>" class="level-group" style="display: <?= $levelKey === $firstLevelKey ? 'block' : 'none' ?>;">
            <?php if (!empty($levelData['lessons'])): ?>
                <?php foreach ($levelData['lessons'] as $dayNum => $lessonItem): ?>
                    <?php 
                        $isUnlocked = $this->model->isLessonUnlocked($lang, $levelKey, $dayNum, $userProgress);
                        $isCompleted = !empty($userProgress[$lang][$levelKey][$dayNum]['completed']);
                        $statusClass = $isCompleted ? 'completed' : ($isUnlocked ? 'unlocked' : 'locked');
                    ?>
                    <div class="lesson-node <?= $statusClass ?>">
                        <div>
                            <strong><?= htmlspecialchars($lessonItem['title']) ?></strong>
                            <p style="margin: 4px 0 0; color: #64748b; font-size: 0.9em;"><?= htmlspecialchars($lessonItem['desc'] ?? '') ?></p>
                        </div>
                        <div>
                            <?php if ($isUnlocked): ?>
                                <a href="index.php?action=lesson&lang=<?= $lang ?>&level=<?= $levelKey ?>&day=<?= $dayNum ?>" class="btn-start">
                                    <?= $isCompleted ? 'Ôn lại 🔄' : 'Học ngay ▶' ?>
                                </a>
                            <?php else: ?>
                                <span class="btn-start locked">🔒 Chưa mở</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #64748b; font-style: italic;">Nội dung bài học cho cấp độ này đang được biên soạn...</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- 3. Đoạn mã JavaScript xử lý chuyển đổi Dropdown -->
<script>
function selectLevel(selectedLevelKey) {
    // Ẩn tất cả các nhóm bài học
    document.querySelectorAll('.level-group').forEach(group => {
        group.style.display = 'none';
    });
    
    // Hiển thị nhóm bài học tương ứng với Level được chọn trong Dropdown
    const activeGroup = document.getElementById('level-group-' + selectedLevelKey);
    if (activeGroup) {
        activeGroup.style.display = 'block';
    }
}
</script>


</body>
</html>