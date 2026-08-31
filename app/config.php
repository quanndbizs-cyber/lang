<?php

return [
    'app_name' => 'MultiLang Hub - Học Ngoại Ngữ Siêu Nhẹ',
    'app_version' => '2.0.0',
    'db_file' => __DIR__ . '/../database/learning.db',
    'upload_dir' => __DIR__ . '/../public/uploads',
    'data_dir' => __DIR__ . '/../data',
    'public_base_path' => getenv('PUBLIC_BASE_PATH') ?: '',
    'default_language' => 'en',
    'languages' => [
        'en' => [
            'code' => 'en',
            'name' => 'Tiếng Anh',
            'sub' => 'Cambridge A2 Key (KET), Luyện Nghe, Shadowing, Đọc & Nói',
            'icon' => '🇬🇧',
            'badge' => 'A2-B1',
        ],
        'zh' => [
            'code' => 'zh',
            'name' => 'Tiếng Trung',
            'sub' => 'HSK 1 - 4, Bảng Pinyin, Chữ Hán, Từ Vựng & Mẫu Câu',
            'icon' => '🇨🇳',
            'badge' => 'HSK 1-4',
        ],
        'ja' => [
            'code' => 'ja',
            'name' => 'Tiếng Nhật',
            'sub' => 'Hiragana, Katakana, JLPT N5 - N4, Kanji & Ngữ Pháp',
            'icon' => '🇯🇵',
            'badge' => 'N5-N4',
        ],
        'ko' => [
            'code' => 'ko',
            'name' => 'Tiếng Hàn',
            'sub' => 'Bảng Hangul, TOPIK I Sơ Cấp, Từ Vựng & Giao Tiếp',
            'icon' => '🇰🇷',
            'badge' => 'TOPIK I',
        ],
    ],
    'study_categories' => [
        'listening' => 'Luyện nghe A2 Key',
        'dictation' => 'Chép chính tả / Shadowing',
        'test_practice' => 'Làm bài thi thử',
        'vocabulary' => 'Học từ vựng Flashcard',
        'alphabet' => 'Học bảng chữ cái / Phát âm',
        'grammar' => 'Học ngữ pháp / Mẫu câu',
        'speaking' => 'Luyện nói / Video mẫu',
    ],
    'star_rewards' => [
        'listen_part' => 1,
        'finish_test' => 3,
        'dictation_note' => 2,
        'vocab_pack' => 2,
        'perfect_score' => 5,
    ],
];
