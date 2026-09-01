<?php
// app/course_data.php - Dữ liệu các khóa học phân theo Level & Lớp

return [
    'en' => [
        // --------------------------------------------------
        // LEVEL 1: LỚP 6 (STARTER / PRE-KET)
        // --------------------------------------------------
        'level_1' => [
            'name' => 'Level 1 (Lớp 6 - Starter)',
            'lessons' => [
                1 => [
                    'title' => 'Bài 1: Chào hỏi & Trường học (Greetings & School)',
                    'desc' => 'Học 10 từ vựng nền tảng, phát âm âm /h/ & /w/, làm chủ Động từ To Be và bài đọc KET Part 4.',
                    'stages' => [
                        // CHẶNG 1: TỪ VỰNG & MINI-GAME
                        1 => [
                            'vocab' => [
                                ['word' => 'Teacher', 'type' => 'n', 'ipa' => '/ˈtiː.tʃər/', 'meaning' => 'Giáo viên 👩‍🏫', 'ex' => 'She is my English teacher.'],
                                ['word' => 'Student', 'type' => 'n', 'ipa' => '/ˈstjuː.dənt/', 'meaning' => 'Học sinh 🎒', 'ex' => 'I am a new student.'],
                                ['word' => 'Classroom', 'type' => 'n', 'ipa' => '/ˈklɑːs.ruːm/', 'meaning' => 'Lớp học 🏫', 'ex' => 'Our classroom is big.'],
                                ['word' => 'Classmate', 'type' => 'n', 'ipa' => '/ˈklɑːs.meɪt/', 'meaning' => 'Bạn cùng lớp 🧑‍🤝‍🧑', 'ex' => 'Ben is my classmate.'],
                                ['word' => 'Library', 'type' => 'n', 'ipa' => '/ˈlaɪ.brər.i/', 'meaning' => 'Thư viện 📚', 'ex' => 'We read books in the library.'],
                                ['word' => 'Schoolbag', 'type' => 'n', 'ipa' => '/ˈskuːl.bæɡ/', 'meaning' => 'Cặp sách 🎒', 'ex' => 'My schoolbag is blue.'],
                                ['word' => 'Notebook', 'type' => 'n', 'ipa' => '/ˈnəʊt.bʊk/', 'meaning' => 'Vở ghi 📓', 'ex' => 'I write in my notebook.'],
                                ['word' => 'Subject', 'type' => 'n', 'ipa' => '/ˈsʌb.dʒekt/', 'meaning' => 'Môn học 📖', 'ex' => 'Math is my favorite subject.'],
                                ['word' => 'Friendly', 'type' => 'adj', 'ipa' => '/ˈfrend.li/', 'meaning' => 'Thân thiện 😊', 'ex' => 'My classmates are friendly.'],
                                ['word' => 'Learn', 'type' => 'v', 'ipa' => '/lɜːn/', 'meaning' => 'Học tập ✍️', 'ex' => 'We learn English today.']
                            ]
                        ],
                        // CHẶNG 2: PHÁT ÂM & NGHE
                        2 => [
                            'embed_video' => 'https://www.youtube-nocookie.com/embed/g2bHDo7YR30',
                            'embed_audio' => 'https://www.w3schools.com/html/horse.mp3'
                        ],
                        // CHẶNG 3: NGỮ PHÁP & ĐỌC HIỂU KET MINI
                        3 => [
                            'content' => '
                                <h4>1. Ngữ pháp: Động từ "To Be" (Thì Hiện tại đơn)</h4>
                                <ul>
                                    <li><strong>I + am</strong> (Viết tắt: <em>I\'m</em>): <code>I am a student.</code></li>
                                    <li><strong>He / She / It + is</strong> (Viết tắt: <em>He\'s / She\'s / It\'s</em>): <code>She is my teacher.</code></li>
                                    <li><strong>You / We / They + are</strong> (Viết tắt: <em>You\'re / We\'re / They\'re</em>): <code>They are friendly.</code></li>
                                </ul>
                                <br>
                                <h4>2. Luyện đọc KET Part 4 (Điền từ vào chỗ trống):</h4>
                                <blockquote style="background: #f1f5f9; padding: 15px; border-left: 4px solid #0284c7; margin: 10px 0;">
                                    "Hello! My name (1) ___ Ben. I am 12 years old and I am a (2) ___ at Secondary School. 
                                    My favorite (3) ___ is English. My classmates are very friendly!"
                                </blockquote>
                                <p><strong>Câu hỏi luyện tập:</strong></p>
                                <ol>
                                    <li>(1) Choose: <strong>A. am</strong> | <strong>B. is</strong> | <strong>C. are</strong></li>
                                    <li>(2) Choose: <strong>A. teacher</strong> | <strong>B. student</strong> | <strong>C. doctor</strong></li>
                                    <li>(3) Choose: <strong>A. subject</strong> | <strong>B. classroom</strong> | <strong>C. library</strong></li>
                                </ol>
                            '
                        ]
                    ]
                ],
                2 => [
                    'title' => 'Bài 2: Gia đình của tôi (My Family)',
                    'desc' => 'Từ vựng các thành viên gia đình, tính từ sở hữu (My, Your, His, Her) và phát âm âm /m/.',
                    'stages' => []
                ]
            ]
        ],
        // Các Level tiếp theo cho Lớp 7, 8, 9...
        'level_2' => ['name' => 'Level 2 (Lớp 7 - Elementary)', 'lessons' => []],
        'level_3' => ['name' => 'Level 3 (Lớp 8 - Pre-Intermediate)', 'lessons' => []]
    ]
];