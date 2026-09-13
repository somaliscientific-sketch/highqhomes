<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once APP_PATH . '/Core/Database.php';

$db = Database::getInstance();
$sql = file_get_contents(__DIR__ . '/../database/patches/site-identity.sql');
foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
    if ($statement !== '') {
        $db->exec($statement);
    }
}

echo "Site identity settings applied.\n";
