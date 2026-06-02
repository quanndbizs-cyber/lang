<?php

return [
    'db_file' => __DIR__ . '/../database/summer.db',
    'upload_dir' => __DIR__ . '/../public/uploads',
    'max_upload_size' => 5 * 1024 * 1024,
    'allowed_upload_types' => [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ],
    'activity_options' => [
        'study_2h' => ['Học ban ngày 2 giờ (T2-T6)', 2],
        'read_book' => ['Đọc sách', 1],
        'copy_sutra' => ['Chép kinh', 1],
        'write_story' => ['Viết truyện', 1],
        'journal' => ['Viết nhật ký', 1],
        'exercise' => ['Tập thể dục / vận động', 1],
        'creative' => ['Vẽ tranh / sáng tạo', 1],
        'housework' => ['Việc nhà được giao', 1],
        'water_plant' => ['Tưới cây / chăm cây', 1],
        'feed_fish' => ['Cho cá ăn / chăm bể cá', 1],
        'clean_room' => ['Dọn bàn học, phòng ngủ', 1],
        'screen_ok' => ['Không vượt thời gian màn hình', 1],
    ],
    'penalty_options' => [
        0 => ['Không vượt giờ / không chơi quá quy định', 0],
        60 => ['Chơi 1 giờ YouTube/TV/Game', -3],
        120 => ['Chơi 2 giờ YouTube/TV/Game', -10],
    ],
    'reward_options' => [
        'Hoạt động gia đình' => 20,
        '1 quyển truyện' => 25,
        '1 cây nhỏ' => 25,
        'Phần thưởng tự chọn bất kỳ' => 35,
        'Về ngủ chơi nhà bà nội' => 50,
    ],
];
