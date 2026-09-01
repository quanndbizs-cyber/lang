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
        header('Content-Type: application/json');
        
        $lang = $_POST['lang'] ?? 'en';
        $day = (int)($_POST['day'] ?? 1);
        $stageIndex = (int)($_POST['stage'] ?? 1);
        $xpEarned = 15; // +15 XP cho mỗi chặng hoàn thành

        $result = $this->model->saveStageProgress($lang, $day, $stageIndex, $xpEarned);

        echo json_encode([
            'success' => true,
            'message' => "Hoàn thành Chặng {$stageIndex}! +{$xpEarned} XP",
            'total_xp' => $result['total_xp'],
            'completed' => $result['completed']
        ]);
        exit;
    }
}