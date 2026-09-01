<?php
// app/controllers/HomeController.php

require_once __DIR__ . '/../models/LessonModel.php';

class HomeController {
    private $model;

    public function __construct() {
        $this->model = new LessonModel();
    }

    /**
     * Hiển thị trang chủ / Bản đồ bài học
     */
	public function index() {
		$lang = $_GET['lang'] ?? 'en';
		$languages = $this->model->getLanguages();
		
		// 🛠️ Đổi getLessonsByLanguage thành getLevelsByLanguage
		$levels = $this->model->getLevelsByLanguage($lang); 
		
		$userProgress = $_SESSION['user_progress'] ?? [];

		require_once __DIR__ . '/../views/home.php';
	}
}