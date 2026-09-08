<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Core/Database.php';

$pdo = Database::getInstance();
$rows = $pdo->query('SELECT page_key, section_key, is_enabled, sort_order, title, subtitle FROM page_sections ORDER BY page_key, sort_order')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo implode(' | ', $r) . PHP_EOL;
}
