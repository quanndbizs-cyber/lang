<?php
// app/controllers/HubSystemController.php

class HubSystemController {
    public function index() {
        // Lấy ngôn ngữ hiện tại từ URL (để truyền sang link Khóa học mới)
        $current_lang = $_GET['lang'] ?? 'en';
        
        // Gọi file giao diện/trang chủ cũ của hệ thống
        // (Nếu trang cũ của bạn sử dụng view hoặc logic cũ, ta include ở đây)
        require_once __DIR__ . '/../views/index_hub.php'; 
    }
}