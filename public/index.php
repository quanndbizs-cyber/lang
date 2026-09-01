<?php
// public/index.php - Router cập nhật kết nối 2 hệ thống

session_start();

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        // 🏠 MẶC ĐỊNH: Hiển thị trang tổng hợp thông tin CŨ
        // Gọi lại logic/view của hệ thống cũ tại đây
        require_once __DIR__ . '/../app/controllers/HubSystemController.php'; 
        $controller = new HubSystemController();
        $controller->index();
        break;

    case 'courses':
        // 🎓 HỆ THỐNG MỚI: Bản đồ khóa học Language (3 chặng)
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case 'lesson':
        // 📖 Chi tiết bài học theo ngày
        require_once __DIR__ . '/../app/controllers/LessonController.php';
        $controller = new LessonController();
        $day = isset($_GET['day']) ? (int)$_GET['day'] : 1;
        $controller->show($day);
        break;

    case 'complete_stage':
        // ⚡ Xử lý AJAX hoàn thành chặng
        require_once __DIR__ . '/../app/controllers/LessonController.php';
        $controller = new LessonController();
        $controller->completeStage();
        break;

    default:
        http_response_code(404);
        echo "404 - Trang không tồn tại.";
        break;
}