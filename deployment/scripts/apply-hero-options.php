<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once APP_PATH . '/Core/Database.php';

$db = Database::getInstance();

$settings = [
    ['hero_carousel_autoplay', '1', 'boolean', 'Hero Auto-play Slides', 'homepage', 14],
    ['hero_carousel_interval', '6', 'number', 'Hero Slide Interval (seconds)', 'homepage', 15],
    ['hero_carousel_dots', '1', 'boolean', 'Hero Show Slide Dots', 'homepage', 16],
    ['hero_carousel_pause_hover', '1', 'boolean', 'Hero Pause on Hover', 'homepage', 17],
];

$stmt = $db->prepare('INSERT IGNORE INTO settings (`key`, `value`, `type`, `label`, `group_name`, `sort_order`) VALUES (?,?,?,?,?,?)');
foreach ($settings as $row) {
    $stmt->execute($row);
}

$columns = [
    'show_description' => "ADD COLUMN `show_description` TINYINT(1) NOT NULL DEFAULT 1 AFTER `text_align`",
    'image_focus'      => "ADD COLUMN `image_focus` VARCHAR(20) NOT NULL DEFAULT 'center' AFTER `show_description`",
    'content_style'    => "ADD COLUMN `content_style` VARCHAR(20) NOT NULL DEFAULT 'standard' AFTER `image_focus`",
    'badge_text'       => "ADD COLUMN `badge_text` VARCHAR(80) DEFAULT NULL AFTER `content_style`",
];

$existing = $db->query("SHOW COLUMNS FROM sliders")->fetchAll(PDO::FETCH_COLUMN);
foreach ($columns as $name => $sql) {
    if (!in_array($name, $existing, true)) {
        $db->exec("ALTER TABLE sliders {$sql}");
        echo "Added column: {$name}\n";
    }
}

echo "Hero carousel options applied.\n";
