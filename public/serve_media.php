<?php

declare(strict_types=1);

$config = require __DIR__ . '/../app/config.php';
$dataDir = realpath($config['data_dir'] ?? (__DIR__ . '/../data'));

if (!$dataDir || !is_dir($dataDir)) {
    http_response_code(500);
    echo 'Data directory not configured or found.';
    exit;
}

$fileParam = $_GET['file'] ?? '';
if ($fileParam === '') {
    http_response_code(400);
    echo 'File parameter is required.';
    exit;
}

// Sanitize requested relative path
$cleanRelPath = str_replace('\\', '/', $fileParam);
$cleanRelPath = ltrim($cleanRelPath, '/');

// Prevent directory traversal
$filePath = realpath($dataDir . '/' . $cleanRelPath);

if (!$filePath || !str_starts_with($filePath, $dataDir) || !is_file($filePath)) {
    http_response_code(404);
    echo 'File not found.';
    exit;
}

$ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
$mimeMap = [
    'mp3'  => 'audio/mpeg',
    'wav'  => 'audio/wav',
    'ogg'  => 'audio/ogg',
    'm4a'  => 'audio/mp4',
    'mp4'  => 'video/mp4',
    'webm' => 'video/webm',
    'pdf'  => 'application/pdf',
    'png'  => 'image/png',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'webp' => 'image/webp',
];

$contentType = $mimeMap[$ext] ?? 'application/octet-stream';
$fileSize = filesize($filePath);
$filename = basename($filePath);

// Check if browser sent a Range header
$rangeHeader = $_SERVER['HTTP_RANGE'] ?? null;

if ($rangeHeader !== null && preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches)) {
    $start = (int) $matches[1];
    $end = ($matches[2] !== '') ? (int) $matches[2] : ($fileSize - 1);

    if ($start > $end || $start >= $fileSize) {
        http_response_code(416);
        header('Content-Range: bytes */' . $fileSize);
        exit;
    }

    $length = $end - $start + 1;

    http_response_code(206);
    header('Content-Type: ' . $contentType);
    header('Content-Length: ' . $length);
    header(sprintf('Content-Range: bytes %d-%d/%d', $start, $end, $fileSize));
    header('Accept-Ranges: bytes');
    header('Cache-Control: public, max-age=86400');

    $fp = fopen($filePath, 'rb');
    fseek($fp, $start);
    $chunkSize = 1024 * 64;
    $bytesRemaining = $length;

    while (!feof($fp) && $bytesRemaining > 0 && (connection_status() === 0)) {
        $readSize = min($chunkSize, $bytesRemaining);
        $buffer = fread($fp, $readSize);
        echo $buffer;
        flush();
        $bytesRemaining -= strlen($buffer);
    }
    fclose($fp);
    exit;
}

// Standard full file response
header('Content-Type: ' . $contentType);
header('Content-Length: ' . $fileSize);
header('Accept-Ranges: bytes');
header('Cache-Control: public, max-age=86400');
header('Content-Disposition: inline; filename="' . rawurlencode($filename) . '"');

readfile($filePath);
exit;
