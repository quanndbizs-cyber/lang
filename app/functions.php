<?php

declare(strict_types=1);

require_once __DIR__ . '/learning_data.php';

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function public_url(?string $path, string $basePath = ''): string
{
    $path = trim((string) $path);
    if ($path === '') {
        return '';
    }

    if (preg_match('#^https?://#', $path)) {
        return $path;
    }

    $basePath = trim($basePath, '/');
    $path = ltrim($path, '/');

    if ($basePath === '') {
        return '/' . $path;
    }

    return '/' . $basePath . '/' . $path;
}

function is_ajax_request(): bool
{
    return str_contains(strtolower($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json')
        || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
}

function json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function scan_a2_key_data(array $config): array
{
    $dataDir = $config['data_dir'] ?? (__DIR__ . '/../data');
    $a2KeyDir = $dataDir . '/a2 key';

    $result = [
        'books' => [],
        'keys' => [],
        'video' => null,
    ];

    if (!is_dir($a2KeyDir)) {
        return $result;
    }

    // 1. A2 Key 1
    $key1Dir = $a2KeyDir . '/Audio/A2 Key 1';
    if (is_dir($key1Dir)) {
        $videoFile = $key1Dir . '/A2 Key, Speaking test video (SD).mp4';
        if (is_file($videoFile)) {
            $result['video'] = [
                'title' => 'A2 Key Speaking Test Video Sample (Standard Definition)',
                'file' => 'a2 key/Audio/A2 Key 1/A2 Key, Speaking test video (SD).mp4',
                'format' => 'mp4',
            ];
        }

        $tests = [];
        for ($t = 1; $t <= 4; $t++) {
            $testFolder = $key1Dir . "/Key A2 Audio/Test {$t} audio";
            $parts = [];
            if (is_dir($testFolder)) {
                for ($p = 1; $p <= 5; $p++) {
                    $mp3 = $testFolder . "/Test {$t} Part {$p}.mp3";
                    if (is_file($mp3)) {
                        $parts[] = [
                            'part_number' => $p,
                            'title' => "Part {$p}",
                            'file' => "a2 key/Audio/A2 Key 1/Key A2 Audio/Test {$t} audio/Test {$t} Part {$p}.mp3",
                        ];
                    }
                }
            }
            if (!empty($parts)) {
                $tests[] = [
                    'test_number' => $t,
                    'title' => "Test {$t}",
                    'parts' => $parts,
                ];
            }
        }

        $result['books']['a2_key_1'] = [
            'key' => 'a2_key_1',
            'title' => 'Cambridge A2 Key 1 (2020 Exam)',
            'desc' => 'Bộ đề chuẩn 4 bài Test đầy đủ Part 1 - 5 + Video bài thi Speaking',
            'badge' => 'Chính thống',
            'icon' => '📘',
            'tests' => $tests,
        ];
    }

    // 2. A2 Key 2
    $key2Dir = $a2KeyDir . '/Audio/A2 Key 2';
    if (is_dir($key2Dir)) {
        $tests = [];
        for ($t = 1; $t <= 4; $t++) {
            $testFolder = $key2Dir . "/Audio Mp3/Test {$t}";
            $parts = [];
            if (is_dir($testFolder)) {
                for ($p = 1; $p <= 5; $p++) {
                    $mp3 = $testFolder . "/Key2_test{$t}_audio{$p}.mp3";
                    if (is_file($mp3)) {
                        $parts[] = [
                            'part_number' => $p,
                            'title' => "Part {$p}",
                            'file' => "a2 key/Audio/A2 Key 2/Audio Mp3/Test {$t}/Key2_test{$t}_audio{$p}.mp3",
                        ];
                    }
                }
            }
            if (!empty($parts)) {
                $tests[] = [
                    'test_number' => $t,
                    'title' => "Test {$t}",
                    'parts' => $parts,
                ];
            }
        }

        $result['books']['a2_key_2'] = [
            'key' => 'a2_key_2',
            'title' => 'Cambridge A2 Key 2 (2020 Exam)',
            'desc' => 'Bộ đề chuẩn 4 bài Test tiếp theo có chia nhỏ theo từng Part',
            'badge' => 'Nâng cao',
            'icon' => '📙',
            'tests' => $tests,
        ];
    }

    // 3. A2 Key for Schools 1
    $school1Dir = $a2KeyDir . '/Audio/A2 key for schools 1 for the revised 2020 exam Audio CD';
    if (is_dir($school1Dir)) {
        $tests = [];
        for ($t = 1; $t <= 4; $t++) {
            $parts = [];
            for ($p = 1; $p <= 5; $p++) {
                $mp3 = $school1Dir . "/Test {$t} Part {$p}.mp3";
                if (is_file($mp3)) {
                    $parts[] = [
                        'part_number' => $p,
                        'title' => "Part {$p}",
                        'file' => "a2 key/Audio/A2 key for schools 1 for the revised 2020 exam Audio CD/Test {$t} Part {$p}.mp3",
                    ];
                }
            }
            if (!empty($parts)) {
                $tests[] = [
                    'test_number' => $t,
                    'title' => "Test {$t}",
                    'parts' => $parts,
                ];
            }
        }

        $result['books']['a2_schools_1'] = [
            'key' => 'a2_schools_1',
            'title' => 'A2 Key for Schools 1 (Revised 2020)',
            'desc' => 'Bộ đề Cambridge A2 Key dành cho học sinh trường học (Tests 1-4)',
            'badge' => 'Schools',
            'icon' => '📗',
            'tests' => $tests,
        ];
    }

    // 4. A2 Key for Schools 2
    $school2Dir = $a2KeyDir . '/Audio/A2 key for schools 2 for the revised 2020 exam Audio CD';
    if (is_dir($school2Dir)) {
        $files = glob($school2Dir . '/*.mp3');
        $parts = [];
        $i = 1;
        foreach ($files as $f) {
            $parts[] = [
                'part_number' => $i++,
                'title' => basename($f, '.mp3'),
                'file' => "a2 key/Audio/A2 key for schools 2 for the revised 2020 exam Audio CD/" . basename($f),
            ];
        }
        $result['books']['a2_schools_2'] = [
            'key' => 'a2_schools_2',
            'title' => 'A2 Key for Schools 2 (Revised 2020)',
            'desc' => 'Bộ đề nghe A2 Key for Schools tập 2 nguyên bản',
            'badge' => 'Schools 2',
            'icon' => '📕',
            'tests' => [
                [
                    'test_number' => 1,
                    'title' => 'Test 1 Full Audio',
                    'parts' => $parts,
                ]
            ],
        ];
    }

    // 5. A2 Key for Schools Trainer 1
    $trainer1Dir = $a2KeyDir . '/Audio/A2 Key for Schools Trainer 1';
    if (is_dir($trainer1Dir)) {
        $files = glob($trainer1Dir . '/*.mp3');
        natsort($files);
        $tracks = [];
        $idx = 1;
        foreach ($files as $f) {
            $tracks[] = [
                'part_number' => $idx++,
                'title' => basename($f, '.mp3'),
                'file' => "a2 key/Audio/A2 Key for Schools Trainer 1/" . basename($f),
            ];
        }
        $result['books']['a2_trainer_1'] = [
            'key' => 'a2_trainer_1',
            'title' => 'A2 Key for Schools Trainer 1 (6 Practice Tests)',
            'desc' => '74 Audio Tracks rèn luyện kỹ năng nghe chi tiết từng dạng câu hỏi',
            'badge' => 'Trainer 74 Tracks',
            'icon' => '🎓',
            'tests' => [
                [
                    'test_number' => 1,
                    'title' => 'Toàn bộ 74 Tracks Luyện Tập',
                    'parts' => $tracks,
                ]
            ],
        ];
    }

    // 6. Keys with explanation PDF
    $keysDir = $a2KeyDir . '/Keys with explanation';
    if (is_dir($keysDir)) {
        for ($t = 1; $t <= 4; $t++) {
            $listeningPdf = $keysDir . "/Test {$t} answer key, Listening.pdf";
            $readingPdf = $keysDir . "/Test {$t} answer key, Reading.pdf";
            $result['keys'][$t] = [
                'test_number' => $t,
                'listening' => is_file($listeningPdf) ? "a2 key/Keys with explanation/Test {$t} answer key, Listening.pdf" : null,
                'reading' => is_file($readingPdf) ? "a2 key/Keys with explanation/Test {$t} answer key, Reading.pdf" : null,
            ];
        }
    }

    return $result;
}

function get_a2_key_cambridge_scale(int $rawScore, int $maxRaw = 25): array
{
    // Cambridge English Scale for A2 Key Listening (0-25 -> 100-150)
    // 23-25: 140-150 (Level B1 Pass with Distinction)
    // 18-22: 133-139 (Pass with Merit)
    // 13-17: 120-132 (Pass - Level A2)
    // 9-12: 100-119 (Level A1)
    // <9: Below A1
    $percent = ($rawScore / max(1, $maxRaw)) * 100;
    if ($rawScore >= 23) {
        $scale = 140 + round(($rawScore - 23) * 5);
        $grade = 'Grade A (Level B1 - Xuất sắc)';
        $color = 'text-emerald-500';
    } elseif ($rawScore >= 18) {
        $scale = 133 + round(($rawScore - 18) * 1.2);
        $grade = 'Grade B (Pass with Merit - Giỏi)';
        $color = 'text-blue-500';
    } elseif ($rawScore >= 13) {
        $scale = 120 + round(($rawScore - 13) * 2.6);
        $grade = 'Grade C (Đạt chuẩn A2 Key)';
        $color = 'text-indigo-500';
    } elseif ($rawScore >= 9) {
        $scale = 100 + round(($rawScore - 9) * 4.7);
        $grade = 'Level A1 (Cần cố gắng thêm)';
        $color = 'text-amber-500';
    } else {
        $scale = max(80, 80 + $rawScore * 2);
        $grade = 'Chưa đạt chuẩn (Hãy nghe lại)';
        $color = 'text-rose-500';
    }

    return [
        'raw' => $rawScore,
        'max' => $maxRaw,
        'percent' => round($percent, 1),
        'scale' => (int) $scale,
        'grade' => $grade,
        'color' => $color,
    ];
}
