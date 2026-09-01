<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chương Trình Học Ngôn Ngữ - KET Lớp 6</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; margin: 0; padding: 0; color: #1e293b; }
        .header-bar { background: #0284c7; color: white; padding: 15px 20px; display: flex; justify-space-between; align-items: center; }
        .stats-box { font-weight: bold; background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px; }
        .lang-tabs { display: flex; gap: 10px; margin: 20px 0; }
        .lang-tab { padding: 10px 18px; border-radius: 8px; background: #e2e8f0; text-decoration: none; color: #334155; font-weight: bold; }
        .lang-tab.active { background: #0284c7; color: white; }
        .lesson-node { background: white; border-radius: 12px; padding: 18px; margin-bottom: 12px; border-left: 5px solid #cbd5e1; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.04); }
        .lesson-node.unlocked { border-left-color: #0284c7; }
        .lesson-node.completed { border-left-color: #22c55e; background: #f0fdf4; }
        .btn-start { background: #0284c7; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .btn-start.locked { background: #94a3b8; pointer-events: none; }
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

    <!-- Thanh chọn Ngôn ngữ -->
    <div class="lang-tabs">
        <?php foreach ($languages as $code => $info): ?>
            <a href="index.php?action=courses&lang=<?= $code ?>" class="lang-tab<?= $lang === $code ? ' active"; style="display: block;" ' : '";  style="display: none;"' ?>>
                <?= $info['icon'] ?> <?= htmlspecialchars($info['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <h3>📍 Bản đồ bài học (<?= htmlspecialchars($languages[$lang]['name'] ?? '') ?>)</h3>

    <!-- Danh sách bài học theo lộ trình -->
    <div class="lesson-path">
        <?php if (!empty($lessons)): ?>
            <?php foreach ($lessons as $dayNum => $lessonItem): ?>
                <?php 
                    $isUnlocked = $this->model->isLessonUnlocked($lang, $dayNum, $userProgress);
                    $isCompleted = !empty($userProgress[$lang][$dayNum]['completed']);
                    $statusClass = $isCompleted ? 'completed' : ($isUnlocked ? 'unlocked' : 'locked');
                ?>
                <div class="lesson-node <?= $statusClass ?>">
                    <div>
                        <strong style="font-size: 1.1em;">Ngày <?= $dayNum ?>: <?= htmlspecialchars($lessonItem['title']) ?></strong>
                        <p style="margin: 4px 0 0; color: #64748b; font-size: 0.9em;"><?= htmlspecialchars($lessonItem['desc'] ?? '') ?></p>
                    </div>
                    <div>
                        <?php if ($isUnlocked): ?>
                            <a href="index.php?action=lesson&lang=<?= $lang ?>&day=<?= $dayNum ?>" class="btn-start">
                                <?= $isCompleted ? 'Ôn lại 🔄' : 'Học ngay ▶' ?>
                            </a>
                        <?php else: ?>
                            <span class="btn-start locked">🔒 Chưa mở</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Đang cập nhật danh sách bài học cho ngôn ngữ này...</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>