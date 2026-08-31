# MultiLang Hub - Nền Tảng Học Ngoại Ngữ Siêu Nhẹ (PHP 8.4 + SQLite)

Trang web học ngoại ngữ siêu nhẹ, thiết kế tối ưu cho người học cơ bản & nâng cao đa ngôn ngữ (**Tiếng Anh Cambridge A2 Key, Tiếng Trung HSK, Tiếng Nhật JLPT, Tiếng Hàn TOPIK**). Chạy mượt mà trên Debian Linux 12/13 với Nginx và PHP 8.4 FPM.

---

## 🌟 Tính Năng Nổi Bật

### 1. 🇬🇧 English Studio: Cambridge A2 Key (KET) Toàn Diện
- **5 Bộ Sách Sẵn Có Đầy Đủ**:
  - `Cambridge A2 Key 1 (2020 Exam)` (Tests 1-4 Part 1-5 Audio + Video Speaking test)
  - `Cambridge A2 Key 2 (2020 Exam)` (Tests 1-4 Audio)
  - `A2 Key for Schools 1 (Revised 2020)` (Tests 1-4 Audio)
  - `A2 Key for Schools 2 (Revised 2020)` (Test 1 Full Audio)
  - `A2 Key for Schools Trainer 1` (74 Audio Tracks rèn luyện)
  - `Answer Keys & Explanations` (8 file PDF giải thích chi tiết Listening & Reading)
- **Trình phát Audio Thông Minh (Shadowing & Dictation)**:
  - Tốc độ tùy chỉnh (0.75x, 1.0x, 1.25x, 1.5x), tua nhanh -5s / +5s.
  - **Chế độ Lặp đoạn A-B (Shadowing Loop)**: Đặt điểm [A] và điểm [B] để nghe lặp đi lặp lại một câu thoại cho đến khi phát âm chuẩn và nghe rõ.
  - **Khung Chép Chính Tả (Dictation)**: Vừa nghe vừa chép lại, tự động đếm từ và lưu trữ vào SQLite.
  - **Phiếu Trắc Nghiệm Thi Thử**: Mô phỏng bài làm và tự động quy đổi điểm sang Cambridge English Scale (100 - 150 điểm, Grade A/B/C/A1).
  - **Trình đọc PDF trực tiếp**: Xem giải thích đáp án ngay cạnh bài nghe.
  - **Video Speaking Test Player**: Xem bài thi nói thực tế.

### 2. 🇨🇳 Tiếng Trung (HSK & Pinyin)
- Bảng Thanh Mẫu (Initials), Vận Mẫu (Finals), 4 Thanh Điệu tương tác.
- Phát âm chuẩn xác qua Web Speech Synthesis (`zh-CN`).
- Bộ từ vựng HSK 1 - 4 kèm Hán Việt, chữ Hán, Pinyin, nghĩa và câu ví dụ.

### 3. 🇯🇵 Tiếng Nhật (JLPT N5 & Kana)
- Bảng chữ cái 50 âm Hiragana & Katakana kèm Romaji và phát âm tự động (`ja-JP`).
- Từ vựng sơ cấp JLPT N5 và Hán tự Kanji thông dụng.

### 4. 🇰🇷 Tiếng Hàn (TOPIK I & Hangul)
- Bảng chữ cái Hangul (Nguyên âm, Phụ âm) và quy tắc ghép âm + phát âm (`ko-KR`).
- Từ vựng & mẫu câu giao tiếp hàng ngày TOPIK I.

### 5. ⭐ Tích Lũy Điểm Sao & Thống Kê Tiến Độ
- Ghi nhận thời gian học (phút), số bài nghe hoàn thành, số điểm test và số sao đạt được.

---

## 🚀 Hướng Dẫn Cài Đặt Trên Debian Linux 12 / 13

### Bước 1: Sao chép mã nguồn lên server
```bash
cd /path/to/project
sudo ./install_debian.sh
```

### Bước 2: Truy cập trình duyệt
Mở trình duyệt: `http://IP_CUA_MAY_DEBIAN/`

---

## 🛠️ Chạy Thử Nghiệm Local (PHP CLI)

```bash
php -S 127.0.0.1:8080 -t public
```
Truy cập: `http://127.0.0.1:8080/`

---

## 📁 Cấu Trúc Thư Mục
```text
├── app/
│   ├── config.php          # Cấu hình đa ngôn ngữ & thang điểm
│   ├── db.php              # Quản lý CSDL SQLite
│   ├── functions.php       # Quét dữ liệu A2 Key & helper
│   ├── learning_data.php   # Dữ liệu bảng chữ cái, Pinyin, Hangul, Kana, Từ vựng
│   └── actions.php         # API xử lý ghi chú, test, sao, thống kê
├── data/
│   └── a2 key/
│       ├── Audio/          # Toàn bộ file MP3 & MP4 5 bộ sách KET
│       └── Keys with explanation/ # 8 file PDF giải thích đáp án
├── database/
│   └── learning.db         # SQLite DB tự tạo khi chạy
├── public/
│   ├── index.php           # Giao diện học tập chính
│   └── serve_media.php     # Stream HTTP Range (MP3/MP4/PDF)
└── install_debian.sh       # Script cài đặt Debian Linux + Nginx
```
