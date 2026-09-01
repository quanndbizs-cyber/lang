<?php
// app/models/LessonModel.php

class LessonModel {
    
    /**
     * Lấy danh sách danh mục ngôn ngữ hỗ trợ
     */
    public function getLanguages() {
        return [
            'en' => ['name' => 'Tiếng Anh (Lớp 6 - KET)', 'icon' => '🇬🇧'],
            'zh' => ['name' => 'Tiếng Trung', 'icon' => '🇨🇳'],
            'ja' => ['name' => 'Tiếng Nhật', 'icon' => '🇯🇵'],
            'ko' => ['name' => 'Tiếng Hàn', 'icon' => '🇰🇷']
        ];
    }

    /**
     * Lấy danh sách bài học theo ngôn ngữ chọn lựa
     */
    public function getLessonsByLanguage($lang = 'en') {
        // Tải dữ liệu tương ứng từ DB hoặc file cấu hình dữ liệu
        $allData = require __DIR__ . '/../course_data.php';
        return isset($allData[$lang]) ? $allData[$lang] : [];
    }

    /**
     * Lấy thông tin bài học chi tiết theo Ngày và Ngôn ngữ
     */
    public function getLessonDetail($lang, $day) {
        $lessons = $this->getLessonsByLanguage($lang);
        return isset($lessons[$day]) ? $lessons[$day] : null;
    }

    /**
     * Kiểm tra điều kiện bài học đã được mở khóa (dựa trên tiến trình học)
     */
    public function isLessonUnlocked($lang, $day, $userProgress) {
        if ($day == 1) return true; // Bài 1 luôn mở
        $prevDay = $day - 1;
        return isset($userProgress[$lang][$prevDay]['completed']) && $userProgress[$lang][$prevDay]['completed'] === true;
    }

    /**
     * Cập nhật cộng điểm XP, Streak và lưu tiến trình bài học vào Session
     */
    public function saveStageProgress($lang, $day, $stageIndex, $xpEarned) {
        if (!isset($_SESSION['user_progress'])) {
            $_SESSION['user_progress'] = [];
        }
        
        // Lưu lại chặng đã hoàn thành
        $_SESSION['user_progress'][$lang][$day]['stages'][$stageIndex] = true;
        
        // Cộng dồn XP
        $_SESSION['user_xp'] = ($_SESSION['user_xp'] ?? 0) + $xpEarned;
        
        // Đánh dấu hoàn thành toàn bộ ngày nếu đã xong đủ 3 chặng
        if (count($_SESSION['user_progress'][$lang][$day]['stages']) >= 3) {
            $_SESSION['user_progress'][$lang][$day]['completed'] = true;
        }

        return [
            'total_xp' => $_SESSION['user_xp'],
            'completed' => $_SESSION['user_progress'][$lang][$day]['completed'] ?? false
        ];
    }
}