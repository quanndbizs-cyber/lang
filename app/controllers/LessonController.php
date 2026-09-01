<?php
// app/controllers/LessonController.php

require_once __DIR__ . '/../models/LessonModel.php';

class LessonController {
    private $model;

    public function __construct() {
        $this->model = new LessonModel();
    }

    /**
     * Hiển thị danh sách bài học / Bản đồ lộ trình
     */
    public function index() {
        $lang = $_GET['lang'] ?? 'en'; // Mặc định là Tiếng Anh
        $languages = $this->model->getLanguages();
        $lessons = $this->model->getLessonsByLanguage($lang);
        $userProgress = $_SESSION['user_progress'] ?? [];

        // Nạp View bản đồ bài học
        require_once __DIR__ . '/../views/home.php';
    }

    /**
     * Hiển thị bài học chi tiết theo ngày
     */
	public function show($day) {
		$lang = $_GET['lang'] ?? 'en';
		$levelKey = $_GET['level'] ?? 'level_1'; // Mặc định là level_1 (Lớp 6)

		// 🛠️ Gọi getLessonDetail với đúng 3 tham số: ngôn ngữ, level và ngày
		$lesson = $this->model->getLessonDetail($lang, $levelKey, $day);

		if (!$lesson) {
			http_response_code(404);
			echo "Bài học không tồn tại!";
			return;
		}

		$userProgress = $_SESSION['user_progress'] ?? [];
		$isUnlocked = $this->model->isLessonUnlocked($lang, $levelKey, $day, $userProgress);

		if (!$isUnlocked) {
			echo "<script>alert('Bạn cần hoàn thành bài học trước đó!'); window.location.href='index.php?action=courses&lang={$lang}';</script>";
			return;
		}

		require_once __DIR__ . '/../views/lesson.php';
	}

    /**
     * Xử lý AJAX ghi nhận hoàn thành từng chặng bài học
     */
 public function completeStage() {
    // 1. Nhận dữ liệu gửi từ thẻ <a> thông qua $_GET
    $lang = $_GET['lang'] ?? 'en';
    $levelKey = $_GET['level'] ?? 'level_1';
    $day = (int)($_GET['day'] ?? 1);
    $currentStage = (int)($_GET['stage'] ?? 1);
    
    // 2. Lưu tiến trình của chặng vừa hoàn thành vào SESSION
    $_SESSION['user_progress'][$lang][$levelKey][$day]['stages'][$currentStage] = true;
    $_SESSION['user_xp'] = ($_SESSION['user_xp'] ?? 0) + 10; // Cộng 10 XP 🪙

    $totalStages = 3; // Tổng số chặng trong 1 bài học

    // 3. Kiểm tra điều kiện chuyển hướng 🔀
    if ($currentStage < $totalStages) {
        // ➡️ Chưa xong bài: Chuyển sang Chặng tiếp theo (stage + 1)
        $nextStage = $currentStage + 1;
        header("Location: index.php?action=lesson&lang={$lang}&level={$levelKey}&day={$day}&stage={$nextStage}");
        exit();
    } else {
        // 🎉 Đã hoàn thành Chặng 3: Đánh dấu xong toàn bộ bài học!
        $_SESSION['user_progress'][$lang][$levelKey][$day]['completed'] = true;
        
        // 🏠 Quay về Trang chủ Khóa học để sẵn sàng cho bài học/bài test tiếp theo
        header("Location: index.php?action=courses&lang={$lang}&level={$levelKey}&completed_day={$day}");
        exit();
    }
  }
}