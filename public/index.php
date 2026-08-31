<?php

declare(strict_types=1);

$config = require __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/learning_data.php';

$db = connect_database($config);
$stats = get_study_stats($db);
$a2Data = scan_a2_key_data($config);
$pinyinData = get_pinyin_data();
$kanaData = get_kana_data();
$hangulData = get_hangul_data();
$vocabPacks = get_language_vocab_packs();
$recentLogs = fetch_recent_logs($db, 15);

$activeLang = $_GET['lang'] ?? 'en';
if (!isset($config['languages'][$activeLang])) {
    $activeLang = 'en';
}

$basePath = $config['public_base_path'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiLang Hub - Học Ngoại Ngữ Siêu Nhẹ & Cambridge A2 Key</title>
    <!-- Tailwind CSS CDN for lightweight styling & Lucide icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1e293b; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
        .tab-btn.active { background: #4f46e5; color: #ffffff; border-color: #6366f1; }
        .subtab-btn.active { background: #334155; color: #38bdf8; border-color: #38bdf8; }
        .flashcard { perspective: 1000px; }
        .flashcard-inner { transition: transform 0.6s; transform-style: preserve-3d; }
        .flashcard.flipped .flashcard-inner { transform: rotateY(180deg); }
        .flashcard-front, .flashcard-back { backface-visibility: hidden; }
        .flashcard-back { transform: rotateY(180deg); }
    </style>
</head>
<body class="min-h-full flex flex-col font-sans bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Top Header & Navigation -->
    <header class="sticky top-0 z-40 bg-slate-900/90 backdrop-blur border-b border-slate-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & App Title -->
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">🌐</span>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-lg text-white tracking-wide">MultiLang Hub</span>
                            <span class="text-xs bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded-full font-mono border border-indigo-500/30">PHP 8.4</span>
                        </div>
                        <p class="text-xs text-slate-400">Học Ngoại Ngữ Siêu Nhẹ • Anh • Trung • Nhật • Hàn</p>
                    </div>
                </div>

                <!-- Live Reward & Progress Badges -->
                <div class="flex items-center space-x-3 text-sm">
                    <div class="flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/30 text-amber-300 px-3 py-1 rounded-lg font-medium shadow-sm">
                        <span>⭐</span>
                        <span id="headerStars"><?= $stats['total_stars'] ?></span>
                        <span class="text-xs text-amber-400/80">Sao</span>
                    </div>
                    <div class="hidden sm:flex items-center gap-1.5 bg-sky-500/10 border border-sky-500/30 text-sky-300 px-3 py-1 rounded-lg font-medium">
                        <span>⏱️</span>
                        <span id="headerMinutes"><?= $stats['total_minutes'] ?></span>
                        <span class="text-xs text-sky-400/80">phút</span>
                    </div>
                    <div class="hidden md:flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-3 py-1 rounded-lg font-medium">
                        <span>📝</span>
                        <span id="headerTests"><?= $stats['tests_count'] ?></span>
                        <span class="text-xs text-emerald-400/80">bài test</span>
                    </div>
                </div>
            </div>

            <!-- Language Tabs -->
            <div class="flex space-x-2 border-t border-slate-800/80 py-2 overflow-x-auto custom-scrollbar">
                <?php foreach ($config['languages'] as $langKey => $lang): ?>
                    <a href="?lang=<?= $langKey ?>" 
                       class="tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150 whitespace-nowrap border border-transparent <?= $activeLang === $langKey ? 'active shadow-lg shadow-indigo-500/20' : 'bg-slate-800/60 text-slate-300 hover:bg-slate-800 hover:text-white' ?>">
                        <span class="text-base"><?= $lang['icon'] ?></span>
                        <span><?= $lang['name'] ?></span>
                        <span class="text-xs opacity-75 bg-black/20 px-1.5 py-0.5 rounded"><?= $lang['badge'] ?></span>
                    </a>
                <?php endforeach; ?>
                <button onclick="switchMainTab('stats')" id="tabBtnStats"
                        class="tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150 whitespace-nowrap bg-slate-800/60 text-slate-300 hover:bg-slate-800 hover:text-white border border-transparent">
                    <span>📊</span>
                    <span>Thống Kê & Nhật Ký</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- ========================================================================= -->
        <!-- TAB 1: ENGLISH - CAMBRIDGE A2 KEY HUB (DEFAULT) -->
        <!-- ========================================================================= -->
        <?php if ($activeLang === 'en'): ?>
        <section id="module-en" class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 border border-indigo-500/20 rounded-2xl p-5 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🇬🇧</span>
                        <h1 class="text-xl font-bold text-white">Cambridge A2 Key (KET) Learning Studio</h1>
                        <span class="text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2 py-0.5 rounded-full font-semibold">5 Bộ Sách Sẵn Sàng</span>
                    </div>
                    <p class="text-sm text-slate-400 mt-1">Luyện nghe chuyên sâu, tua lặp đoạn Shadowing (A-B Loop), chép chính tả (Dictation) & xem PDF giải thích đáp án.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <?php if (!empty($a2Data['video'])): ?>
                        <button onclick="openSpeakingVideoModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-purple-600/20 text-purple-300 border border-purple-500/30 hover:bg-purple-600/30 text-sm font-medium transition">
                            <span>🎥</span> Video Speaking Test
                        </button>
                    <?php endif; ?>
                    <button onclick="openPdfModal(1, 'listening')" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-sky-600/20 text-sky-300 border border-sky-500/30 hover:bg-sky-600/30 text-sm font-medium transition">
                        <span>📑</span> Sách Giải Thích Đáp Án
                    </button>
                </div>
            </div>

            <!-- Book Selector Pills -->
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold mr-2">Chọn Bộ Sách:</span>
                <?php $isFirstBook = true; ?>
                <?php foreach ($a2Data['books'] as $bKey => $bInfo): ?>
                    <button onclick="selectBook('<?= $bKey ?>')" id="bookBtn_<?= $bKey ?>"
                            class="book-pill inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium transition border <?= $isFirstBook ? 'bg-indigo-600 text-white border-indigo-500 shadow-md' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-750' ?>">
                        <span><?= $bInfo['icon'] ?></span>
                        <span><?= h($bInfo['title']) ?></span>
                    </button>
                    <?php $isFirstBook = false; ?>
                <?php endforeach; ?>
            </div>

            <!-- Main Workspace Grid (Left: Audio & Tests, Right: Dictation / Notes / Answer Sheet) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left Column: Audio Player & Track Selector (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Advanced Audio Player Card -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-xs text-indigo-400 font-semibold tracking-wider uppercase" id="playerBookLabel">Cambridge A2 Key 1</span>
                                <h2 class="text-lg font-bold text-white flex items-center gap-2" id="playerTrackTitle">
                                    <span>🎵</span> <span id="trackNameDisplay">Test 1 Part 1</span>
                                </h2>
                            </div>
                            <span class="text-xs bg-slate-800 text-slate-400 px-2.5 py-1 rounded-md font-mono" id="playbackSpeedBadge">1.0x</span>
                        </div>

                        <!-- Native Audio Element -->
                        <audio id="mainAudio" preload="metadata" class="hidden"></audio>

                        <!-- Timeline & Time -->
                        <div class="space-y-1.5">
                            <input type="range" id="audioSeek" min="0" max="100" value="0" step="0.1" 
                                   class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                            <div class="flex justify-between text-xs font-mono text-slate-400">
                                <span id="currentTime">00:00</span>
                                <div class="flex items-center gap-2" id="loopIndicator" style="display:none;">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                    <span class="text-emerald-400 font-semibold" id="loopText">Lặp A-B</span>
                                </div>
                                <span id="totalDuration">00:00</span>
                            </div>
                        </div>

                        <!-- Primary Controls -->
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <!-- Quick Jump Controls -->
                            <div class="flex items-center gap-1.5">
                                <button onclick="seekOffset(-10)" title="Lùi 10 giây" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-mono transition">⏪ -10s</button>
                                <button onclick="seekOffset(-5)" title="Lùi 5 giây" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-mono transition">⏮️ -5s</button>
                            </div>

                            <!-- Play/Pause Button -->
                            <button id="btnPlayPause" onclick="togglePlayPause()" class="w-14 h-14 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white flex items-center justify-center text-2xl shadow-lg shadow-indigo-600/30 transition transform active:scale-95">
                                ▶️
                            </button>

                            <!-- Forward Controls -->
                            <div class="flex items-center gap-1.5">
                                <button onclick="seekOffset(5)" title="Tua 5 giây" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-mono transition">+5s ⏭️</button>
                                <button onclick="seekOffset(10)" title="Tua 10 giây" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-mono transition">+10s ⏩</button>
                            </div>
                        </div>

                        <!-- Secondary Controls: Speed & Shadowing A-B Loop -->
                        <div class="pt-3 border-t border-slate-800/80 flex flex-wrap items-center justify-between gap-3 text-xs">
                            <!-- Playback Speed -->
                            <div class="flex items-center gap-1">
                                <span class="text-slate-400 mr-1">Tốc độ:</span>
                                <button onclick="setSpeed(0.75)" class="speed-btn px-2 py-1 rounded bg-slate-800 text-slate-300 hover:bg-slate-700">0.75x</button>
                                <button onclick="setSpeed(1.0)" class="speed-btn active px-2 py-1 rounded bg-indigo-600 text-white">1.0x</button>
                                <button onclick="setSpeed(1.25)" class="speed-btn px-2 py-1 rounded bg-slate-800 text-slate-300 hover:bg-slate-700">1.25x</button>
                                <button onclick="setSpeed(1.5)" class="speed-btn px-2 py-1 rounded bg-slate-800 text-slate-300 hover:bg-slate-700">1.5x</button>
                            </div>

                            <!-- Shadowing A-B Loop Tools -->
                            <div class="flex items-center gap-1.5 bg-slate-950/60 p-1.5 rounded-xl border border-slate-800">
                                <span class="text-slate-400 font-semibold px-1">Shadowing Loop:</span>
                                <button onclick="setLoopPoint('A')" id="btnLoopA" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-mono font-bold">Đặt [A]</button>
                                <button onclick="setLoopPoint('B')" id="btnLoopB" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 font-mono font-bold">Đặt [B]</button>
                                <button onclick="toggleLoopMode()" id="btnToggleLoop" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold">🔁 Lặp</button>
                                <button onclick="clearLoop()" class="px-2 py-1 text-rose-400 hover:text-rose-300" title="Xóa lặp">✕</button>
                            </div>
                        </div>
                    </div>

                    <!-- Track Selector List (Tests & Parts) -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <span>📋</span> Danh Sách Bài Nghe
                            </h3>
                            <span class="text-xs text-slate-400" id="totalTracksLabel">4 Tests • 20 Parts</span>
                        </div>

                        <!-- Dynamic Container for Book Tests -->
                        <div id="testsContainer" class="space-y-4 max-h-[480px] overflow-y-auto custom-scrollbar pr-1">
                            <!-- Populated via JS -->
                        </div>
                    </div>
                </div>

                <!-- Right Column: Dictation Studio, Answer Sheet & Personal Notes (5 cols) -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- Utility Switcher (Dictation vs Answer Sheet vs Explanation) -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                            <div class="flex gap-2">
                                <button onclick="switchRightTab('dictation')" id="rtabBtnDictation" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white">
                                    ✍️ Chép Chính Tả
                                </button>
                                <button onclick="switchRightTab('quiz')" id="rtabBtnQuiz" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700">
                                    📝 Phiếu Bài Làm
                                </button>
                                <button onclick="switchRightTab('pdf')" id="rtabBtnPdf" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700">
                                    📖 PDF Đáp Án
                                </button>
                            </div>
                        </div>

                        <!-- Pane 1: Dictation / Shadowing Pad -->
                        <div id="paneDictation" class="space-y-3">
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span>Gõ lại những gì bạn nghe được (Shadowing & Dictation):</span>
                                <span id="dictationWordCount" class="font-mono text-indigo-400">0 từ</span>
                            </div>
                            <textarea id="dictationText" rows="11" 
                                      class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3.5 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 font-mono resize-none placeholder-slate-600"
                                      placeholder="Bấm Play bài nghe, nghe từng câu và chép lại vào đây. Sử dụng nút Đặt [A] / [B] bên trái để lặp lại câu chưa nghe rõ..."></textarea>
                            
                            <div class="flex items-center justify-between pt-1">
                                <button onclick="saveCurrentNote()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-md shadow-emerald-600/20 transition">
                                    <span>💾</span> Lưu Bài Chép (+2 ⭐)
                                </button>
                                <button onclick="clearDictation()" class="text-xs text-slate-400 hover:text-slate-200">Xóa trắng</button>
                            </div>
                        </div>

                        <!-- Pane 2: Answer Sheet & Score Simulator -->
                        <div id="paneQuiz" class="space-y-4" style="display:none;">
                            <div class="bg-indigo-950/40 border border-indigo-500/20 rounded-xl p-3 text-xs text-indigo-200">
                                <strong>Phiếu làm bài chuẩn Cambridge A2 Key:</strong> Chọn hoặc điền đáp án, sau đó bấm <strong>Chấm điểm & Quy đổi thang Cambridge</strong>.
                            </div>

                            <!-- 5 Questions Interactive Form for current Part -->
                            <div id="quizQuestionsContainer" class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-1">
                                <!-- Populated dynamically -->
                            </div>

                            <div class="pt-2 border-t border-slate-800 flex items-center justify-between">
                                <button onclick="submitQuizAnswers()" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-md transition">
                                    ✅ Chấm Điểm (+3 ⭐)
                                </button>
                                <div id="quizResultBadge" class="text-xs font-bold"></div>
                            </div>
                        </div>

                        <!-- Pane 3: PDF Explanation Viewer -->
                        <div id="panePdf" class="space-y-3" style="display:none;">
                            <div class="flex flex-wrap gap-2 text-xs">
                                <button onclick="loadPdfViewer(1, 'listening')" class="px-2.5 py-1.5 rounded bg-slate-800 text-slate-300 hover:bg-indigo-600 hover:text-white">Test 1 Listening PDF</button>
                                <button onclick="loadPdfViewer(2, 'listening')" class="px-2.5 py-1.5 rounded bg-slate-800 text-slate-300 hover:bg-indigo-600 hover:text-white">Test 2 Listening PDF</button>
                                <button onclick="loadPdfViewer(3, 'listening')" class="px-2.5 py-1.5 rounded bg-slate-800 text-slate-300 hover:bg-indigo-600 hover:text-white">Test 3 Listening PDF</button>
                                <button onclick="loadPdfViewer(4, 'listening')" class="px-2.5 py-1.5 rounded bg-slate-800 text-slate-300 hover:bg-indigo-600 hover:text-white">Test 4 Listening PDF</button>
                            </div>
                            <div class="w-full h-[380px] bg-slate-950 rounded-xl overflow-hidden border border-slate-800">
                                <iframe id="pdfFrame" src="" class="w-full h-full border-0"></iframe>
                            </div>
                            <div class="flex justify-between items-center text-xs text-slate-400">
                                <span id="pdfStatusLabel">Chọn Test để xem PDF</span>
                                <a id="pdfDownloadLink" href="#" target="_blank" class="text-indigo-400 hover:underline">Mở toàn màn hình ↗</a>
                            </div>
                        </div>

                    </div>

                    <!-- English A2 Essential Vocab Flashcards Mini Box -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-white text-sm flex items-center gap-2">
                                <span>📚</span> Từ Vựng Trọng Tâm A2 Key
                            </h3>
                            <span class="text-xs text-slate-400">Cambridge Vocabulary</span>
                        </div>
                        <div id="enFlashcardBox" class="bg-slate-950 p-4 rounded-xl border border-slate-800 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-lg font-bold text-indigo-300" id="enCardWord">accommodation</h4>
                                    <p class="text-xs font-mono text-slate-400" id="enCardIpa">/əˌkɒm.əˈdeɪ.ʃən/</p>
                                </div>
                                <button onclick="speakCurrentWord('en')" class="p-2 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/30" title="Nghe phát âm">🔊</button>
                            </div>
                            <p class="text-sm text-slate-200" id="enCardMeaning">chỗ ở, nơi trọ</p>
                            <p class="text-xs italic text-slate-400 bg-slate-900 p-2 rounded border border-slate-800" id="enCardExample">"We need to find cheap accommodation near the university."</p>
                            <div class="flex justify-between pt-2">
                                <button onclick="nextEnVocab(-1)" class="text-xs text-slate-400 hover:text-white">← Từ trước</button>
                                <span class="text-xs text-slate-500" id="enVocabCounter">1 / 17</span>
                                <button onclick="nextEnVocab(1)" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold">Từ kế tiếp →</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <?php endif; ?>


        <!-- ========================================================================= -->
        <!-- TAB 2: CHINESE - HSK & PINYIN STUDIO -->
        <!-- ========================================================================= -->
        <?php if ($activeLang === 'zh'): ?>
        <section id="module-zh" class="space-y-6">
            <div class="bg-gradient-to-r from-slate-900 via-rose-950/30 to-slate-900 border border-rose-500/20 rounded-2xl p-5 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🇨🇳</span>
                        <h1 class="text-xl font-bold text-white">Hệ Thống Học Tiếng Trung & HSK</h1>
                        <span class="text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30 px-2 py-0.5 rounded-full font-semibold">HSK 1 - 4</span>
                    </div>
                    <p class="text-sm text-slate-400 mt-1">Bảng Pinyin tương tác phát âm chuẩn, Thanh mẫu, Vận mẫu, Thanh điệu & Flashcard Chữ Hán.</p>
                </div>
            </div>

            <!-- Sub Navigation -->
            <div class="flex gap-2 border-b border-slate-800 pb-2">
                <button onclick="switchZhTab('pinyin')" id="zhTabPinyin" class="subtab-btn active px-4 py-2 rounded-xl text-sm font-medium border border-transparent">
                    🔤 Bảng Pinyin & Phát Âm
                </button>
                <button onclick="switchZhTab('hsk')" id="zhTabHsk" class="subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent bg-slate-800 text-slate-300 hover:bg-slate-700">
                    🎴 Flashcard HSK Từ Vựng
                </button>
            </div>

            <!-- Pane: Pinyin Chart -->
            <div id="zhPanePinyin" class="space-y-6">
                <!-- Tones -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>🎵</span> 4 Thanh Điệu Cơ Bản trong Tiếng Trung
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                        <?php foreach ($pinyinData['tones'] as $t): ?>
                            <div onclick="speakZhText('<?= addslashes(explode(':', $t['desc'])[1] ?? 'mā') ?>')" 
                                 class="cursor-pointer bg-slate-950 hover:bg-rose-950/30 p-3.5 rounded-xl border border-slate-800 hover:border-rose-500/40 transition group">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-rose-400"><?= h($t['tone']) ?></span>
                                    <span class="text-lg group-hover:scale-110 transition font-serif font-bold text-white"><?= h($t['mark']) ?></span>
                                </div>
                                <p class="text-xs text-slate-400 mt-2"><?= h($t['desc']) ?></p>
                                <span class="text-[11px] text-rose-400/80 mt-1 block">🔊 Bấm nghe mẫu</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Initials (Thanh Mẫu) -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>🗣️</span> Thanh Mẫu (Phụ âm đầu - Initials)
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-2.5">
                        <?php foreach ($pinyinData['initials'] as $item): ?>
                            <button onclick="speakZhText('<?= addslashes($item['p']) ?>a')" 
                                    class="bg-slate-950 hover:bg-rose-900/30 p-3 rounded-xl border border-slate-800 hover:border-rose-500/40 text-left transition group">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-white font-mono"><?= h($item['p']) ?></span>
                                    <span class="text-xs text-rose-400 font-mono">[<?= h($item['ipa']) ?>]</span>
                                </div>
                                <p class="text-[11px] text-slate-400 truncate mt-1"><?= h($item['desc']) ?></p>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Finals (Vận Mẫu) -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>🌊</span> Vận Mẫu Chính (Finals)
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5">
                        <?php foreach ($pinyinData['finals'] as $item): ?>
                            <button onclick="speakZhText('<?= addslashes($item['p']) ?>')" 
                                    class="bg-slate-950 hover:bg-rose-900/30 p-3 rounded-xl border border-slate-800 hover:border-rose-500/40 text-left transition">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-white font-mono"><?= h($item['p']) ?></span>
                                    <span class="text-xs text-rose-400 font-mono">[<?= h($item['ipa']) ?>]</span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1"><?= h($item['desc']) ?></p>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Pane: HSK Flashcards -->
            <div id="zhPaneHsk" class="space-y-6" style="display:none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($vocabPacks['zh'] as $pack): ?>
                        <?php foreach ($pack['items'] as $word): ?>
                            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-3 hover:border-rose-500/30 transition shadow-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-3xl font-bold text-white font-serif"><?= h($word['word']) ?></h3>
                                        <p class="text-sm font-semibold text-rose-400 mt-0.5"><?= h($word['ipa']) ?></p>
                                    </div>
                                    <button onclick="speakZhText('<?= addslashes($word['word']) ?>')" class="p-2.5 rounded-xl bg-rose-500/20 text-rose-300 hover:bg-rose-500/30">🔊</button>
                                </div>
                                <div class="text-sm text-slate-200 font-medium"><?= h($word['meaning']) ?></div>
                                <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 text-xs text-slate-300 italic"><?= h($word['example']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>


        <!-- ========================================================================= -->
        <!-- TAB 3: JAPANESE - KANA & JLPT N5 STUDIO -->
        <!-- ========================================================================= -->
        <?php if ($activeLang === 'ja'): ?>
        <section id="module-ja" class="space-y-6">
            <div class="bg-gradient-to-r from-slate-900 via-emerald-950/30 to-slate-900 border border-emerald-500/20 rounded-2xl p-5 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🇯🇵</span>
                        <h1 class="text-xl font-bold text-white">Học Tiếng Nhật - Bảng Chữ Cái & JLPT N5</h1>
                        <span class="text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2 py-0.5 rounded-full font-semibold">Hiragana • Katakana • Kanji</span>
                    </div>
                    <p class="text-sm text-slate-400 mt-1">Bảng 50 âm cơ bản, âm đục (Dakuten) và từ vựng sơ cấp N5 kèm âm thanh phát âm.</p>
                </div>
            </div>

            <!-- Sub Navigation -->
            <div class="flex gap-2 border-b border-slate-800 pb-2">
                <button onclick="switchJaTab('kana')" id="jaTabKana" class="subtab-btn active px-4 py-2 rounded-xl text-sm font-medium border border-transparent">
                    🗾 Bảng Hiragana & Katakana
                </button>
                <button onclick="switchJaTab('vocab')" id="jaTabVocab" class="subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent bg-slate-800 text-slate-300 hover:bg-slate-700">
                    🌸 Từ Vựng JLPT N5 & Kanji
                </button>
            </div>

            <!-- Pane: Kana Chart -->
            <div id="jaPaneKana" class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <span>🎌</span> Bảng Chữ Cái 50 Âm (Hiragana / Katakana / Romaji)
                </h3>
                <div class="grid grid-cols-5 sm:grid-cols-5 md:grid-cols-10 gap-2">
                    <?php foreach ($kanaData['hiragana'] as $item): ?>
                        <?php if ($item['h'] === ''): ?>
                            <div class="p-2 opacity-10"></div>
                        <?php else: ?>
                            <button onclick="speakJaText('<?= addslashes($item['h']) ?>')" 
                                    class="bg-slate-950 hover:bg-emerald-900/30 p-2.5 rounded-xl border border-slate-800 hover:border-emerald-500/40 text-center transition group">
                                <div class="text-xl font-bold text-emerald-300 font-serif group-hover:scale-110 transition"><?= h($item['h']) ?></div>
                                <div class="text-xs text-slate-400"><?= h($item['k']) ?></div>
                                <div class="text-[11px] text-indigo-400 font-mono"><?= h($item['r']) ?></div>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Pane: JLPT Vocab -->
            <div id="jaPaneVocab" class="space-y-6" style="display:none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($vocabPacks['ja'] as $pack): ?>
                        <?php foreach ($pack['items'] as $word): ?>
                            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-3 hover:border-emerald-500/30 transition shadow-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-emerald-300 font-serif"><?= h($word['word']) ?></h3>
                                        <p class="text-xs font-semibold text-slate-400 mt-0.5"><?= h($word['ipa']) ?></p>
                                    </div>
                                    <button onclick="speakJaText('<?= addslashes($word['word']) ?>')" class="p-2.5 rounded-xl bg-emerald-500/20 text-emerald-300 hover:bg-emerald-500/30">🔊</button>
                                </div>
                                <div class="text-sm text-slate-100 font-medium"><?= h($word['meaning']) ?></div>
                                <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 text-xs text-slate-300"><?= h($word['example']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>


        <!-- ========================================================================= -->
        <!-- TAB 4: KOREAN - HANGUL & TOPIK I STUDIO -->
        <!-- ========================================================================= -->
        <?php if ($activeLang === 'ko'): ?>
        <section id="module-ko" class="space-y-6">
            <div class="bg-gradient-to-r from-slate-900 via-sky-950/30 to-slate-900 border border-sky-500/20 rounded-2xl p-5 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🇰🇷</span>
                        <h1 class="text-xl font-bold text-white">Học Tiếng Hàn - Bảng Hangul & TOPIK I</h1>
                        <span class="text-xs bg-sky-500/20 text-sky-300 border border-sky-500/30 px-2 py-0.5 rounded-full font-semibold">Hangul • TOPIK 1-2</span>
                    </div>
                    <p class="text-sm text-slate-400 mt-1">Bảng nguyên âm, phụ âm, quy tắc ghép âm và từ vựng giao tiếp thông dụng hàng ngày.</p>
                </div>
            </div>

            <!-- Sub Navigation -->
            <div class="flex gap-2 border-b border-slate-800 pb-2">
                <button onclick="switchKoTab('hangul')" id="koTabHangul" class="subtab-btn active px-4 py-2 rounded-xl text-sm font-medium border border-transparent">
                    🇰🇷 Bảng Chữ Cái Hangul
                </button>
                <button onclick="switchKoTab('topik')" id="koTabTopik" class="subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent bg-slate-800 text-slate-300 hover:bg-slate-700">
                    💬 Từ Vựng & Mẫu Câu TOPIK I
                </button>
            </div>

            <!-- Pane: Hangul Chart -->
            <div id="koPaneHangul" class="space-y-6">
                <!-- Vowels -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>🌟</span> Nguyên Âm Hangul (Vowels)
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-2.5">
                        <?php foreach ($hangulData['vowels'] as $item): ?>
                            <button onclick="speakKoText('<?= addslashes($item['c']) ?>')" 
                                    class="bg-slate-950 hover:bg-sky-900/30 p-3 rounded-xl border border-slate-800 hover:border-sky-500/40 text-center transition group">
                                <div class="text-2xl font-bold text-sky-300 group-hover:scale-110 transition"><?= h($item['c']) ?></div>
                                <div class="text-xs font-mono text-indigo-400 mt-1">[<?= h($item['r']) ?>]</div>
                                <div class="text-[11px] text-slate-400 truncate mt-0.5"><?= h($item['desc']) ?></div>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Consonants -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>🎯</span> Phụ Âm Hangul (Consonants)
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5">
                        <?php foreach ($hangulData['consonants'] as $item): ?>
                            <button onclick="speakKoText('<?= addslashes($item['c']) ?>')" 
                                    class="bg-slate-950 hover:bg-sky-900/30 p-3 rounded-xl border border-slate-800 hover:border-sky-500/40 text-left transition group">
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-sky-300 group-hover:scale-110 transition"><?= h($item['c']) ?></span>
                                    <span class="text-xs font-mono text-indigo-400">[<?= h($item['r']) ?>]</span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-1"><?= h($item['desc']) ?></div>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Pane: TOPIK Vocab -->
            <div id="koPaneTopik" class="space-y-6" style="display:none;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($vocabPacks['ko'] as $pack): ?>
                        <?php foreach ($pack['items'] as $word): ?>
                            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-3 hover:border-sky-500/30 transition shadow-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-bold text-sky-300 font-serif"><?= h($word['word']) ?></h3>
                                        <p class="text-xs font-semibold text-slate-400 mt-0.5"><?= h($word['ipa']) ?></p>
                                    </div>
                                    <button onclick="speakKoText('<?= addslashes($word['word']) ?>')" class="p-2.5 rounded-xl bg-sky-500/20 text-sky-300 hover:bg-sky-500/30">🔊</button>
                                </div>
                                <div class="text-sm text-slate-100 font-medium"><?= h($word['meaning']) ?></div>
                                <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 text-xs text-slate-300"><?= h($word['example']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>


        <!-- ========================================================================= -->
        <!-- TAB 5: STATS & STUDY LOGS -->
        <!-- ========================================================================= -->
        <section id="module-stats" class="space-y-6" style="display:none;">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
                <h2 class="text-lg font-bold text-white flex items-center gap-2 mb-4">
                    <span>📊</span> Bảng Tiến Độ & Nhật Ký Học Tập
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                        <span class="text-xs text-slate-400">Tổng Sao Học Tập</span>
                        <div class="text-2xl font-bold text-amber-400 mt-1">⭐ <?= $stats['total_stars'] ?></div>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                        <span class="text-xs text-slate-400">Tổng Thời Gian</span>
                        <div class="text-2xl font-bold text-sky-400 mt-1">⏱️ <?= $stats['total_minutes'] ?> phút</div>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                        <span class="text-xs text-slate-400">Bài Test Đã Làm</span>
                        <div class="text-2xl font-bold text-emerald-400 mt-1">📝 <?= $stats['tests_count'] ?></div>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                        <span class="text-xs text-slate-400">Số Lần Luyện Tập</span>
                        <div class="text-2xl font-bold text-indigo-400 mt-1">🔥 <?= $stats['total_sessions'] ?></div>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-bold text-slate-300 mb-3">Nhật Ký Học Gần Đây</h3>
                    <div class="space-y-2">
                        <?php if (empty($recentLogs)): ?>
                            <p class="text-xs text-slate-500 py-4">Chưa có nhật ký học tập nào. Hãy bắt đầu nghe bài KET đầu tiên nhé!</p>
                        <?php else: ?>
                            <?php foreach ($recentLogs as $log): ?>
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-950 border border-slate-800 text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base"><?= $log['language'] === 'en' ? '🇬🇧' : ($log['language'] === 'zh' ? '🇨🇳' : ($log['language'] === 'ja' ? '🇯🇵' : '🇰🇷')) ?></span>
                                        <div>
                                            <div class="font-semibold text-slate-200"><?= h($log['title']) ?></div>
                                            <div class="text-[11px] text-slate-500"><?= h($log['created_at']) ?> • <?= h($log['category']) ?></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sky-400"><?= $log['duration_minutes'] ?>m</span>
                                        <span class="text-amber-400 font-bold">+<?= $log['stars'] ?> ⭐</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Speaking Video Modal -->
    <?php if (!empty($a2Data['video'])): ?>
    <div id="videoModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" style="display:none;">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-3xl w-full p-5 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <span>🎥</span> <?= h($a2Data['video']['title']) ?>
                </h3>
                <button onclick="closeSpeakingVideoModal()" class="text-slate-400 hover:text-white text-lg">✕</button>
            </div>
            <div class="aspect-video bg-black rounded-xl overflow-hidden">
                <video id="speakingVideo" controls class="w-full h-full" preload="none">
                    <source src="serve_media.php?file=<?= rawurlencode($a2Data['video']['file']) ?>" type="video/mp4">
                    Trình duyệt của bạn không hỗ trợ video HTML5.
                </video>
            </div>
            <p class="text-xs text-slate-400">Xem video thi nói thực tế của Cambridge A2 Key để làm quen với cấu trúc phần thi Speaking Part 1 & Part 2.</p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Data Injection for Client Javascript -->
    <script>
        const A2_DATA = <?= json_encode($a2Data, JSON_UNESCAPED_UNICODE) ?>;
        const VOCAB_EN = <?= json_encode($vocabPacks['en'][0]['items'], JSON_UNESCAPED_UNICODE) ?>;
        
        let currentBookKey = 'a2_key_1';
        let currentTestIdx = 0;
        let currentPartIdx = 0;
        let currentTrackFile = '';
        let loopA = null;
        let loopB = null;
        let isLooping = false;
        let currentEnVocabIdx = 0;

        const audio = document.getElementById('mainAudio');
        const audioSeek = document.getElementById('audioSeek');
        const currentTimeEl = document.getElementById('currentTime');
        const totalDurationEl = document.getElementById('totalDuration');
        const btnPlayPause = document.getElementById('btnPlayPause');

        // Init on page load
        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('module-en')) {
                renderBookTests('a2_key_1');
                updateEnVocabCard();
                bindAudioEvents();
            }
        });

        function selectBook(bookKey) {
            currentBookKey = bookKey;
            document.querySelectorAll('.book-pill').forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-md');
                btn.classList.add('bg-slate-800', 'text-slate-300', 'border-slate-700');
            });
            const selectedBtn = document.getElementById('bookBtn_' + bookKey);
            if (selectedBtn) {
                selectedBtn.classList.remove('bg-slate-800', 'text-slate-300', 'border-slate-700');
                selectedBtn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500', 'shadow-md');
            }
            renderBookTests(bookKey);
        }

        function renderBookTests(bookKey) {
            const book = A2_DATA.books[bookKey];
            if (!book) return;

            document.getElementById('playerBookLabel').innerText = book.title;
            const container = document.getElementById('testsContainer');
            container.innerHTML = '';

            let totalPartsCount = 0;

            book.tests.forEach((t, tIdx) => {
                const testCard = document.createElement('div');
                testCard.className = 'bg-slate-950/60 rounded-xl p-3.5 border border-slate-800 space-y-2';
                
                const header = document.createElement('div');
                header.className = 'flex justify-between items-center text-xs font-bold text-slate-300';
                header.innerHTML = `<span>📖 ${t.title}</span><span class="text-slate-500">${t.parts.length} phần</span>`;
                testCard.appendChild(header);

                const partsGrid = document.createElement('div');
                partsGrid.className = 'grid grid-cols-2 sm:grid-cols-5 gap-2';

                t.parts.forEach((p, pIdx) => {
                    totalPartsCount++;
                    const partBtn = document.createElement('button');
                    partBtn.className = 'track-btn text-left p-2.5 rounded-lg bg-slate-900 hover:bg-indigo-950/60 border border-slate-800 hover:border-indigo-500/40 text-xs transition';
                    partBtn.id = `trk_${bookKey}_${tIdx}_${pIdx}`;
                    partBtn.innerHTML = `
                        <div class="font-bold text-slate-200">${p.title}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5 truncate">Click để nghe</div>
                    `;
                    partBtn.onclick = () => loadTrack(bookKey, tIdx, pIdx);
                    partsGrid.appendChild(partBtn);
                });

                testCard.appendChild(partsGrid);
                container.appendChild(testCard);
            });

            document.getElementById('totalTracksLabel').innerText = `${book.tests.length} Tests • ${totalPartsCount} Tracks`;

            // Auto load first track of this book
            if (book.tests.length > 0 && book.tests[0].parts.length > 0) {
                loadTrack(bookKey, 0, 0, false);
            }
        }

        function loadTrack(bookKey, tIdx, pIdx, autoPlay = true) {
            const book = A2_DATA.books[bookKey];
            if (!book || !book.tests[tIdx] || !book.tests[tIdx].parts[pIdx]) return;

            currentBookKey = bookKey;
            currentTestIdx = tIdx;
            currentPartIdx = pIdx;
            const part = book.tests[tIdx].parts[pIdx];
            currentTrackFile = part.file;

            document.querySelectorAll('.track-btn').forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-500');
                b.classList.add('bg-slate-900', 'text-slate-200', 'border-slate-800');
            });
            const activeTrk = document.getElementById(`trk_${bookKey}_${tIdx}_${pIdx}`);
            if (activeTrk) {
                activeTrk.classList.remove('bg-slate-900', 'border-slate-800');
                activeTrk.classList.add('bg-indigo-600', 'text-white', 'border-indigo-500');
            }

            document.getElementById('trackNameDisplay').innerText = `${book.tests[tIdx].title} - ${part.title}`;
            audio.src = `serve_media.php?file=${encodeURIComponent(part.file)}`;
            audio.load();

            clearLoop();
            loadDictationForTrack();
            renderQuizQuestions(book.tests[tIdx].test_number, part.part_number);

            if (autoPlay) {
                audio.play().catch(() => {});
            }
        }

        function bindAudioEvents() {
            audio.addEventListener('timeupdate', () => {
                if (!isNaN(audio.duration)) {
                    audioSeek.value = (audio.currentTime / audio.duration) * 100;
                    currentTimeEl.innerText = formatTime(audio.currentTime);
                    totalDurationEl.innerText = formatTime(audio.duration);

                    // Check A-B loop
                    if (isLooping && loopA !== null && loopB !== null) {
                        if (audio.currentTime >= loopB || audio.currentTime < loopA) {
                            audio.currentTime = loopA;
                        }
                    }
                }
            });

            audio.addEventListener('play', () => {
                btnPlayPause.innerText = '⏸️';
            });

            audio.addEventListener('pause', () => {
                btnPlayPause.innerText = '▶️';
            });

            audio.addEventListener('ended', () => {
                btnPlayPause.innerText = '▶️';
                // Log 1 star for listening to a part
                fetch('actions.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `action=log_study&language=en&category=listening&title=Nghe xong ${encodeURIComponent(document.getElementById('trackNameDisplay').innerText)}&duration_minutes=5&stars=1`
                }).then(r => r.json()).then(res => {
                    if (res.ok && res.stats) {
                        updateHeaderStats(res.stats);
                    }
                });
            });

            audioSeek.addEventListener('input', () => {
                if (!isNaN(audio.duration)) {
                    audio.currentTime = (audioSeek.value / 100) * audio.duration;
                }
            });
        }

        function togglePlayPause() {
            if (audio.paused) {
                audio.play().catch(e => console.error(e));
            } else {
                audio.pause();
            }
        }

        function seekOffset(seconds) {
            audio.currentTime = Math.max(0, Math.min(audio.duration || 0, audio.currentTime + seconds));
        }

        function setSpeed(speed) {
            audio.playbackRate = speed;
            document.getElementById('playbackSpeedBadge').innerText = speed + 'x';
            document.querySelectorAll('.speed-btn').forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white', 'active');
                btn.classList.add('bg-slate-800', 'text-slate-300');
            });
            event.target.classList.remove('bg-slate-800', 'text-slate-300');
            event.target.classList.add('bg-indigo-600', 'text-white', 'active');
        }

        function setLoopPoint(point) {
            if (point === 'A') {
                loopA = audio.currentTime;
                document.getElementById('btnLoopA').innerText = `[A: ${formatTime(loopA)}]`;
                document.getElementById('btnLoopA').classList.add('bg-emerald-600', 'text-white');
            } else if (point === 'B') {
                loopB = audio.currentTime;
                document.getElementById('btnLoopB').innerText = `[B: ${formatTime(loopB)}]`;
                document.getElementById('btnLoopB').classList.add('bg-emerald-600', 'text-white');
                if (loopA !== null && loopB > loopA) {
                    toggleLoopMode(true);
                }
            }
        }

        function toggleLoopMode(forceState = null) {
            isLooping = forceState !== null ? forceState : !isLooping;
            const btn = document.getElementById('btnToggleLoop');
            const ind = document.getElementById('loopIndicator');
            if (isLooping && loopA !== null && loopB !== null && loopB > loopA) {
                btn.classList.add('bg-emerald-600', 'text-white');
                ind.style.display = 'flex';
                document.getElementById('loopText').innerText = `Lặp ${formatTime(loopA)} ➔ ${formatTime(loopB)}`;
                audio.currentTime = loopA;
                audio.play();
            } else {
                isLooping = false;
                btn.classList.remove('bg-emerald-600', 'text-white');
                ind.style.display = 'none';
            }
        }

        function clearLoop() {
            loopA = null;
            loopB = null;
            isLooping = false;
            document.getElementById('btnLoopA').innerText = 'Đặt [A]';
            document.getElementById('btnLoopA').classList.remove('bg-emerald-600', 'text-white');
            document.getElementById('btnLoopB').innerText = 'Đặt [B]';
            document.getElementById('btnLoopB').classList.remove('bg-emerald-600', 'text-white');
            document.getElementById('btnToggleLoop').classList.remove('bg-emerald-600', 'text-white');
            document.getElementById('loopIndicator').style.display = 'none';
        }

        function formatTime(seconds) {
            if (isNaN(seconds)) return '00:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }

        // Dictation & Notes
        function loadDictationForTrack() {
            if (!currentTrackFile) return;
            fetch(`actions.php?action=get_note&target_key=${encodeURIComponent(currentTrackFile)}`)
                .then(r => r.json())
                .then(res => {
                    document.getElementById('dictationText').value = res.content || '';
                    updateWordCount();
                });
        }

        function saveCurrentNote() {
            if (!currentTrackFile) return;
            const content = document.getElementById('dictationText').value;
            fetch('actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=save_note&target_key=${encodeURIComponent(currentTrackFile)}&language=en&content=${encodeURIComponent(content)}`
            }).then(r => r.json()).then(res => {
                alert(res.message);
                // Also award 2 stars
                fetch('actions.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `action=log_study&language=en&category=dictation&title=Ghi chép chính tả ${encodeURIComponent(document.getElementById('trackNameDisplay').innerText)}&duration_minutes=10&stars=2`
                }).then(r => r.json()).then(r2 => {
                    if (r2.ok && r2.stats) updateHeaderStats(r2.stats);
                });
            });
        }

        function clearDictation() {
            if (confirm('Xóa trắng bài chép này?')) {
                document.getElementById('dictationText').value = '';
                updateWordCount();
            }
        }

        document.getElementById('dictationText')?.addEventListener('input', updateWordCount);
        function updateWordCount() {
            const txt = document.getElementById('dictationText')?.value.trim() || '';
            const words = txt ? txt.split(/\s+/).length : 0;
            const el = document.getElementById('dictationWordCount');
            if (el) el.innerText = `${words} từ`;
        }

        // Quiz Simulator
        function renderQuizQuestions(testNum, partNum) {
            const container = document.getElementById('quizQuestionsContainer');
            if (!container) return;
            container.innerHTML = '';

            for (let q = 1; q <= 5; q++) {
                const qDiv = document.createElement('div');
                qDiv.className = 'bg-slate-950 p-2.5 rounded-lg border border-slate-800 flex items-center justify-between text-xs';
                qDiv.innerHTML = `
                    <span class="font-bold text-slate-300">Câu ${q}:</span>
                    <div class="flex gap-2">
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="q_${q}" value="A" class="accent-indigo-500"> A</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="q_${q}" value="B" class="accent-indigo-500"> B</label>
                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="q_${q}" value="C" class="accent-indigo-500"> C</label>
                    </div>
                `;
                container.appendChild(qDiv);
            }
        }

        function submitQuizAnswers() {
            let score = 0;
            for (let q = 1; q <= 5; q++) {
                const checked = document.querySelector(`input[name="q_${q}"]:checked`);
                if (checked) score++;
            }

            fetch('actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=submit_test&book_key=${encodeURIComponent(currentBookKey)}&test_number=${currentTestIdx+1}&part_number=${currentPartIdx+1}&score=${score}&total=5`
            }).then(r => r.json()).then(res => {
                if (res.ok) {
                    const badge = document.getElementById('quizResultBadge');
                    badge.innerHTML = `<span class="${res.cambridge.color}">Đúng ${score}/5 • ${res.cambridge.grade}</span>`;
                    updateHeaderStats(res.stats);
                }
            });
        }

        // PDF Viewer
        function loadPdfViewer(testNum, type = 'listening') {
            const keyInfo = A2_DATA.keys[testNum];
            if (!keyInfo) return;
            const file = type === 'listening' ? keyInfo.listening : keyInfo.reading;
            if (!file) return;

            const frame = document.getElementById('pdfFrame');
            const streamUrl = `serve_media.php?file=${encodeURIComponent(file)}`;
            frame.src = streamUrl;
            document.getElementById('pdfStatusLabel').innerText = `Đang xem: Test ${testNum} ${type === 'listening' ? 'Listening' : 'Reading'} Explanation`;
            document.getElementById('pdfDownloadLink').href = streamUrl;
            switchRightTab('pdf');
        }

        function openPdfModal(testNum, type) {
            loadPdfViewer(testNum, type);
        }

        function switchRightTab(tab) {
            document.getElementById('paneDictation').style.display = tab === 'dictation' ? 'block' : 'none';
            document.getElementById('paneQuiz').style.display = tab === 'quiz' ? 'block' : 'none';
            document.getElementById('panePdf').style.display = tab === 'pdf' ? 'block' : 'none';

            document.getElementById('rtabBtnDictation').className = tab === 'dictation' ? 'px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white' : 'px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700';
            document.getElementById('rtabBtnQuiz').className = tab === 'quiz' ? 'px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white' : 'px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700';
            document.getElementById('rtabBtnPdf').className = tab === 'pdf' ? 'px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white' : 'px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:bg-slate-700';
        }

        // Speaking Video Modal
        function openSpeakingVideoModal() {
            const modal = document.getElementById('videoModal');
            if (modal) modal.style.display = 'flex';
        }
        function closeSpeakingVideoModal() {
            const modal = document.getElementById('videoModal');
            if (modal) {
                modal.style.display = 'none';
                const v = document.getElementById('speakingVideo');
                if (v) v.pause();
            }
        }

        // Vocab Flashcard Box for English
        function updateEnVocabCard() {
            if (!VOCAB_EN || VOCAB_EN.length === 0) return;
            const item = VOCAB_EN[currentEnVocabIdx];
            document.getElementById('enCardWord').innerText = item.word;
            document.getElementById('enCardIpa').innerText = item.ipa;
            document.getElementById('enCardMeaning').innerText = item.meaning;
            document.getElementById('enCardExample').innerText = `"${item.example}"`;
            document.getElementById('enVocabCounter').innerText = `${currentEnVocabIdx + 1} / ${VOCAB_EN.length}`;
        }
        function nextEnVocab(delta) {
            currentEnVocabIdx = (currentEnVocabIdx + delta + VOCAB_EN.length) % VOCAB_EN.length;
            updateEnVocabCard();
        }

        // Web Speech TTS helper for 4 languages
        function speakText(text, langCode) {
            if (!('speechSynthesis' in window)) {
                alert('Trình duyệt không hỗ trợ Web Speech Synthesis API');
                return;
            }
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = langCode;
            utterance.rate = 0.9;
            window.speechSynthesis.speak(utterance);
        }
        function speakCurrentWord(lang) {
            if (lang === 'en' && VOCAB_EN[currentEnVocabIdx]) {
                speakText(VOCAB_EN[currentEnVocabIdx].word, 'en-US');
            }
        }
        function speakZhText(text) { speakText(text, 'zh-CN'); }
        function speakJaText(text) { speakText(text, 'ja-JP'); }
        function speakKoText(text) { speakText(text, 'ko-KR'); }

        // Subtabs for Zh, Ja, Ko
        function switchZhTab(tab) {
            document.getElementById('zhPanePinyin').style.display = tab === 'pinyin' ? 'block' : 'none';
            document.getElementById('zhPaneHsk').style.display = tab === 'hsk' ? 'block' : 'none';
            document.getElementById('zhTabPinyin').className = `subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent ${tab === 'pinyin' ? 'active' : 'bg-slate-800 text-slate-300'}`;
            document.getElementById('zhTabHsk').className = `subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent ${tab === 'hsk' ? 'active' : 'bg-slate-800 text-slate-300'}`;
        }

        function switchJaTab(tab) {
            document.getElementById('jaPaneKana').style.display = tab === 'kana' ? 'block' : 'none';
            document.getElementById('jaPaneVocab').style.display = tab === 'vocab' ? 'block' : 'none';
            document.getElementById('jaTabKana').className = `subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent ${tab === 'kana' ? 'active' : 'bg-slate-800 text-slate-300'}`;
            document.getElementById('jaTabVocab').className = `subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent ${tab === 'vocab' ? 'active' : 'bg-slate-800 text-slate-300'}`;
        }

        function switchKoTab(tab) {
            document.getElementById('koPaneHangul').style.display = tab === 'hangul' ? 'block' : 'none';
            document.getElementById('koPaneTopik').style.display = tab === 'topik' ? 'block' : 'none';
            document.getElementById('koTabHangul').className = `subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent ${tab === 'hangul' ? 'active' : 'bg-slate-800 text-slate-300'}`;
            document.getElementById('koTabTopik').className = `subtab-btn px-4 py-2 rounded-xl text-sm font-medium border border-transparent ${tab === 'topik' ? 'active' : 'bg-slate-800 text-slate-300'}`;
        }

        function switchMainTab(tab) {
            if (tab === 'stats') {
                const enMod = document.getElementById('module-en');
                const zhMod = document.getElementById('module-zh');
                const jaMod = document.getElementById('module-ja');
                const koMod = document.getElementById('module-ko');
                if (enMod) enMod.style.display = 'none';
                if (zhMod) zhMod.style.display = 'none';
                if (jaMod) jaMod.style.display = 'none';
                if (koMod) koMod.style.display = 'none';

                document.getElementById('module-stats').style.display = 'block';
                document.getElementById('tabBtnStats').classList.add('active');
            }
        }

        function updateHeaderStats(stats) {
            if (document.getElementById('headerStars')) document.getElementById('headerStars').innerText = stats.total_stars;
            if (document.getElementById('headerMinutes')) document.getElementById('headerMinutes').innerText = stats.total_minutes;
            if (document.getElementById('headerTests')) document.getElementById('headerTests').innerText = stats.tests_count;
        }

        // Global Keyboard Shortcuts
        window.addEventListener('keydown', (e) => {
            if (['INPUT', 'TEXTAREA'].includes(document.activeElement?.tagName)) return;
            if (e.code === 'Space') {
                e.preventDefault();
                togglePlayPause();
            } else if (e.code === 'ArrowLeft') {
                seekOffset(-5);
            } else if (e.code === 'ArrowRight') {
                seekOffset(5);
            }
        });
    </script>
</body>
</html>
