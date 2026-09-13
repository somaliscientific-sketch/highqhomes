<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once APP_PATH . '/Core/Database.php';

$db = Database::getInstance();
$sqlFile = dirname(__DIR__) . '/database/patches/cms-v2-upgrade.sql';
$sql = file_get_contents($sqlFile);

$statements = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));

foreach ($statements as $statement) {
    if ($statement === '' || str_starts_with($statement, '--') || str_starts_with($statement, 'USE ')) {
        continue;
    }
    try {
        $db->exec($statement);
        echo "OK: " . substr(str_replace(["\r", "\n"], ' ', $statement), 0, 80) . "...\n";
    } catch (PDOException $e) {
        $msg = $e->getMessage();
        if (str_contains($msg, 'Duplicate column') || str_contains($msg, 'already exists')) {
            echo "SKIP: {$msg}\n";
            continue;
        }
        echo "ERR: {$msg}\n";
        echo "SQL: {$statement}\n";
    }
}

echo "\nMigration complete.\n";
