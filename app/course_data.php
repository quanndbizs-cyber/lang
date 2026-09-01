<?php
// app/course_data.php - Dữ liệu các khóa học theo Lộ trình 3 Chặng

return [
    'en' => [
        1 => [
            'title' => 'Chào hỏi & Giới thiệu bản thân',
            'desc' => 'Học 5 từ vựng, phát âm âm /h/ chuẩn BBC và mẫu câu I am / You are',
            'stages' => [
                1 => [
                    'vocab' => [
                        ['word' => 'Hello', 'ipa' => '/həˈləʊ/', 'meaning' => 'Xin chào 🖐️'],
                        ['word' => 'Name', 'ipa' => '/neɪm/', 'meaning' => 'Tên 📛'],
                        ['word' => 'Student', 'ipa' => '/ˈstjuː.dənt/', 'meaning' => 'Học sinh 🎒'],
                        ['word' => 'Friend', 'ipa' => '/frend/', 'meaning' => 'Bạn bè 🤝'],
                        ['word' => 'Happy', 'ipa' => '/ˈhæp.i/', 'meaning' => 'Vui vẻ 😄']
                    ]
                ],
                2 => [
                    'embed_video' => 'https://www.youtube-nocookie.com/embed/g2bHDo7YR30',
                    'embed_audio' => 'https://www.w3schools.com/html/horse.mp3'
                ],
                3 => [
                    'content' => '
                        <h4>Ngữ pháp: Cấu trúc To Be cơ bản</h4>
                        <p>• <strong>I am + Tên/Nghề nghiệp:</strong> Ví dụ: <em>I am Ben. I am a student.</em></p>
                        <p>• <strong>You are + Tính từ:</strong> Ví dụ: <em>You are happy.</em></p>
                        <br>
                        <h4>Mẫu truyện ngắn đọc hiểu:</h4>
                        <blockquote style="background: #f1f5f9; padding: 12px; border-left: 4px solid #0284c7; margin: 10px 0;">
                            "Hello! My name is Ben. I am a student. I am 12 years old. I am very happy today!"
                        </blockquote>
                    '
                ]
            ]
        ],
        2 => [
            'title' => 'Gia đình của tôi (My Family)',
            'desc' => 'Từ vựng các thành viên gia đình & Phát âm âm /m/',
            'stages' => []
        ]
    ],
    'zh' => [
        1 => [
            'title' => 'Tiếng Trung Ngày 1: Chào hỏi (你好)',
            'desc' => 'Học phát âm Pinyin cơ bản',
            'stages' => []
        ]
    ],
    'ja' => [
        1 => [
            'title' => 'Tiếng Nhật Ngày 1: Bảng chữ cái Hiragana',
            'desc' => 'Học các nguyên âm A, I, U, E, O',
            'stages' => []
        ]
    ],
    'ko' => [
        1 => [
            'title' => 'Tiếng Hàn Ngày 1: Bảng chữ cái Hangul',
            'desc' => 'Học nguyên âm & phụ âm cơ bản',
            'stages' => []
        ]
    ]
];