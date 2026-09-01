<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài học Ngày <?= htmlspecialchars($day) ?> - <?= htmlspecialchars($lesson['title'] ?? '') ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .stage-card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .badge { display: inline-block; padding: 4px 12px; background: #e0f2fe; color: #0369a1; border-radius: 20px; font-weight: bold; }
        .media-container { margin: 15px 0; border-radius: 8px; overflow: hidden; }
        .btn-complete { background: #22c55e; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn-complete:hover { background: #16a34a; }
    </style>
</head>
<body>

<div class="container" style="max-width: 800px; margin: 20px auto; padding: 0 15px;">
    <p><a href="index.php?action=courses&lang=<?= urlencode($lang) ?>">← Quay lại Bản đồ bài học</a></p>
    
    <h2>📅 Bài học Ngày <?= htmlspecialchars($day) ?>: <?= htmlspecialchars($lesson['title'] ?? 'Luyện tập') ?></h2>
    <hr style="border: 0; height: 1px; background: #e2e8f0; margin-bottom: 25px;">

    <!-- 🟢 CHẶNG 1: TỪ VỰNG & GAME -->
    <div id="stage-1" class="stage-block">
	  <div class="stage-card">
        <span class="badge">Chặng 1 🎮</span>
        <h3>Từ vựng & Mini-game</h3>
        <p>Học từ vựng cơ bản và ghép từ tương ứng:</p>
        
        <div class="vocab-list">
            <?php if (!empty($lesson['stages'][1]['vocab'])): ?>
                <ul>
                    <?php foreach ($lesson['stages'][1]['vocab'] as $item): ?>
                        <li><strong><?= htmlspecialchars($item['word']) ?></strong> (<?= htmlspecialchars($item['ipa']) ?>): <?= htmlspecialchars($item['meaning']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nội dung từ vựng đang được cập nhật...</p>
            <?php endif; ?>
        </div>
	</div>

        <a href="index.php?action=complete_stage&lang=<?= $lang ?>&level=<?= $levelKey ?>&day=<?= $day ?>&stage=1" class="btn-next">
    Hoàn thành Chặng 1 & Sang Chặng 2 ➔</a>
    </div>
  </div>

    <!-- 🟡 CHẶNG 2: PHÁT ÂM & NGHE (MULTIMEDIA EMBEDDED) -->
    <div id="stage-2" class="stage-block">
	<div class="stage-card">
        <span class="badge">Chặng 2 🎧</span>
        <h3>Luyện Phát âm & Nghe phản xạ</h3>
        
        <?php if (!empty($lesson['stages'][2]['embed_video'])): ?>
            <div class="media-container">
                <iframe width="100%" height="315" src="<?= htmlspecialchars($lesson['stages'][2]['embed_video']) ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        <?php endif; ?>

        <?php if (!empty($lesson['stages'][2]['embed_audio'])): ?>
            <div class="media-container">
                <audio controls style="width: 100%;">
                    <source src="<?= htmlspecialchars($lesson['stages'][2]['embed_audio']) ?>" type="audio/mpeg">
                </audio>
            </div>
        <?php endif; ?>

        <a href="index.php?action=complete_stage&lang=<?= $lang ?>&level=<?= $levelKey ?>&day=<?= $day ?>&stage=2" class="btn-next">
    Hoàn thành Chặng 2 & Sang Chặng 3 ➔</a>
    </div>
    </div>

    <!-- 🔴 CHẶNG 3: NGỮ PHÁP & ĐỌC HIỂU -->
    <div id="stage-3" class="stage-block">
	<div class="stage-card">
        <span class="badge">Chặng 3 📖</span>
        <h3>Ngữ pháp & Đọc truyện ngắn</h3>
        <div class="reading-content">
            <?= $lesson['stages'][3]['content'] ?? 'Nội dung đọc hiểu...' ?>
        </div>

        <a href="index.php?action=complete_stage&lang=<?= $lang ?>&level=<?= $levelKey ?>&day=<?= $day ?>&stage=3" class="btn-next">
    Hoàn thành Bài học 🎉</a>
    </div>
  </div>
</div>

<script>
function completeStage(stageNum) {
    const formData = new FormData();
    formData.append('lang', '<?= $lang ?>');
    formData.append('day', '<?= $day ?>');
    formData.append('stage', stageNum);

    fetch('index.php?action=complete_stage', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            if(data.completed) {
                alert('🎉 Bạn đã hoàn thành toàn bộ bài học Ngày <?= $day ?>! Tiến tới bài tiếp theo nhé.');
                window.location.href = 'index.php?action=home&lang=<?= $lang ?>';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<!-- Scroll đến chặng tiếp theo  -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Lấy số chặng từ URL (ví dụ: ?stage=2)
    const urlParams = new URLSearchParams(window.location.search);
    const currentStage = urlParams.get('stage') || '1';
    
    // Tìm phần tử chặng tương ứng
    const targetStage = document.getElementById('stage-' + currentStage);
    
    if (targetStage) {
        // Tự động cuộn mượt đến vị trí của chặng đó
        targetStage.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }
});
</script>

</body>
</html>