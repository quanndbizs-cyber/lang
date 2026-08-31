<?php

$config = require __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

$data = scan_a2_key_data($config);
echo "=== SCAN RESULT ===" . PHP_EOL;
echo "Books found: " . count($data['books']) . PHP_EOL;
foreach ($data['books'] as $k => $b) {
    $totalParts = 0;
    foreach ($b['tests'] as $t) {
        $totalParts += count($t['parts']);
    }
    echo " -> [{$k}] {$b['title']}: " . count($b['tests']) . " tests, {$totalParts} tracks" . PHP_EOL;
}
echo "Video: " . ($data['video']['title'] ?? 'None') . " (File: " . ($data['video']['file'] ?? '') . ")" . PHP_EOL;
echo "PDF Answer Keys: " . count($data['keys']) . " tests" . PHP_EOL;
foreach ($data['keys'] as $tNum => $kInfo) {
    echo " -> Test {$tNum}: Listening PDF = " . ($kInfo['listening'] ? 'OK' : 'MISSING') . ", Reading PDF = " . ($kInfo['reading'] ? 'OK' : 'MISSING') . PHP_EOL;
}

$db = connect_database($config);
$stats = get_study_stats($db);
echo "Database initialized successfully! Total stars: " . $stats['total_stars'] . PHP_EOL;
