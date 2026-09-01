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
        $lang = $_GET['lang'] ?? 'en'; // Mặc định chọn Tiếng Anh
        $languages = $this->model->getLanguages();
        $lessons = $this->model->getLessonsByLanguage($lang);
        $userProgress = $_SESSION['user_progress'] ?? [];

        // Nạp giao diện trang chủ
        require_once __DIR__ . '/../views/home.php';
    }
}