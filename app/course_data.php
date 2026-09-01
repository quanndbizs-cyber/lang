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
    'desc' => 'Học 10 từ vựng gia đình, luyện phát âm âm /m/, nắm vững Tính từ sở hữu và bài đọc KET Part 4.',
    'stages' => [
        // CHẶNG 1: TỪ VỰNG & MINI-GAME 🔤
        1 => [
            'vocab' => [
                ['word' => 'Father', 'type' => 'n', 'ipa' => '/ˈfɑː.ðər/', 'meaning' => 'Bố / Cha 👨', 'ex' => 'My father is a doctor.'],
                ['word' => 'Mother', 'type' => 'n', 'ipa' => '/ˈmʌð.ər/', 'meaning' => 'Mẹ 👩', 'ex' => 'My mother is very kind.'],
                ['word' => 'Parents', 'type' => 'n', 'ipa' => '/ˈpeə.rənts/', 'meaning' => 'Bố mẹ / Phụ huynh 🧑‍🧑‍🧒', 'ex' => 'I love my parents.'],
                ['word' => 'Brother', 'type' => 'n', 'ipa' => '/ˈbrʌð.ər/', 'meaning' => 'Anh / Em trai 👦', 'ex' => 'He is my older brother.'],
                ['word' => 'Sister', 'type' => 'n', 'ipa' => '/ˈsɪs.tər/', 'meaning' => 'Chị / Em gái 👧', 'ex' => 'My sister is eight years old.'],
                ['word' => 'Grandfather', 'type' => 'n', 'ipa' => '/ˈɡræn.fɑː.ðər/', 'meaning' => 'Ông 👴', 'ex' => 'My grandfather likes reading.'],
                ['word' => 'Grandmother', 'type' => 'n', 'ipa' => '/ˈɡræn.mʌð.ər/', 'meaning' => 'Bà 👵', 'ex' => 'My grandmother makes nice cakes.'],
                ['word' => 'Family', 'type' => 'n', 'ipa' => '/ˈfæm.əl.i/', 'meaning' => 'Gia đình 🏠', 'ex' => 'I have a happy family.'],
                ['word' => 'Younger', 'type' => 'adj', 'ipa' => '/ˈjʌŋ.ɡər/', 'meaning' => 'Trẻ hơn / Nhỏ tuổi hơn 👶', 'ex' => 'She is my younger sister.'],
                ['word' => 'Together', 'type' => 'adv', 'ipa' => '/təˈɡeð.ər/', 'meaning' => 'Cùng nhau 🤝', 'ex' => 'We play games together.']
            ]
        ],
        // CHẶNG 2: PHÁT ÂM & NGHE 🎧
        2 => [
            'embed_video' => 'https://www.youtube-nocookie.com/embed/g2bHDo7YR30',
            'embed_audio' => 'https://www.w3schools.com/html/horse.mp3'
        ],
        // CHẶNG 3: NGỮ PHÁP & ĐỌC HIỂU KET MINI 📖
        3 => [
            'content' => '
                <h4>1. Ngữ pháp: Tính từ sở hữu (Possessive Adjectives)</h4>
                <table border="1" cellpadding="8" style="border-collapse: collapse; width: 100%; margin: 10px 0;">
                    <tr style="background: #e0f2fe;">
                        <th>Đại từ nhân xưng</th>
                        <th>Tính từ sở hữu</th>
                        <th>Ví dụ</th>
                    </tr>
                    <tr><td>I</td><td><strong>My</strong> (của tôi)</td><td><code>My family is small.</code></td></tr>
                    <tr><td>You</td><td><strong>Your</strong> (của bạn)</td><td><code>What is your name?</code></td></tr>
                    <tr><td>He</td><td><strong>His</strong> (của anh ấy)</td><td><code>His father is tall.</code></td></tr>
                    <tr><td>She</td><td><strong>Her</strong> (của cô ấy)</td><td><code>Her mother is a teacher.</code></td></tr>
                    <tr><td>We / They</td><td><strong>Our / Their</strong></td><td><code>Our house is big.</code></td></tr>
                </table>
                <br>
                <h4>2. Luyện đọc KET Part 4 (Điền từ vào chỗ trống):</h4>
                <blockquote style="background: #f1f5f9; padding: 15px; border-left: 4px solid #0284c7; margin: 10px 0;">
                    "This is a photo of (1) ___ family. My father is a doctor and (2) ___ name is David. 
                    I have one brother. We like playing football (3) ___ at the weekend."
                </blockquote>
                <p><strong>Câu hỏi luyện tập:</strong></p>
                <ol>
                    <li>(1) Choose: <strong>A. my</strong> | <strong>B. I</strong> | <strong>C. me</strong></li>
                    <li>(2) Choose: <strong>A. her</strong> | <strong>B. his</strong> | <strong>C. your</strong></li>
                    <li>(3) Choose: <strong>A. friendly</strong> | <strong>B. together</strong> | <strong>C. classroom</strong></li>
                </ol>
            '
        ]
    ]
]
            ]
        ],
        // Các Level tiếp theo cho Lớp 7, 8, 9...
        'level_2' => ['name' => 'Level 2 (Lớp 7 - Elementary)', 'lessons' => []],
        'level_3' => ['name' => 'Level 3 (Lớp 8 - Pre-Intermediate)', 'lessons' => []]
    ]
];