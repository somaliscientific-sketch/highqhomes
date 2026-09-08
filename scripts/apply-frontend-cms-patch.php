<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Core/Database.php';

$pdo = Database::getInstance();
$sql = file_get_contents(__DIR__ . '/../database/patches/cms-frontend-complete-control.sql');

// Remove single line SQL comments
$lines = explode("\n", $sql);
$cleanLines = [];
foreach ($lines as $line) {
    $trimmed = trim($line);
    if (str_starts_with($trimmed, '--') || str_starts_with($trimmed, 'USE ')) {
        continue;
    }
    $cleanLines[] = $line;
}
$cleanSql = implode("\n", $cleanLines);

$statements = array_filter(array_map('trim', explode(';', $cleanSql)));

$count = 0;
foreach ($statements as $stmt) {
    if ($stmt === '') continue;
    try {
        $pdo->exec($stmt);
        $count++;
    } catch (\Throwable $e) {
        echo "Error in statement: " . $e->getMessage() . "\n";
    }
}

echo "Successfully executed {$count} statements from patch.\n";
