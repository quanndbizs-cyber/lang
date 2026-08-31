<?php

$config = require __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

$db = connect_database($config);

// Test 1: save a note
save_study_note($db, 'test_track_1', 'en', 'This is a sample dictation note for test track 1.');
$content = get_study_note($db, 'test_track_1');
assert($content === 'This is a sample dictation note for test track 1.', 'Note content mismatch');

// Test 2: log study activity & stars
$logId = log_study_activity($db, 'en', 'listening', 'Test 1 Part 1 Practice', 10, 2);
assert($logId > 0, 'Log id should be > 0');

// Test 3: save test result
$testId = save_test_result($db, 'a2_key_1', 1, 1, 4, 5, ['q1' => 'A', 'q2' => 'B']);
assert($testId > 0, 'Test id should be > 0');

// Test 4: stats
$stats = get_study_stats($db);
echo "Stats after tests: " . json_encode($stats) . PHP_EOL;
assert($stats['total_stars'] >= 3, 'Stars should be >= 3');
assert($stats['tests_count'] >= 1, 'Tests count should be >= 1');

echo "ALL UNIT & INTEGRATION TESTS PASSED SUCCESSFULLY!" . PHP_EOL;
