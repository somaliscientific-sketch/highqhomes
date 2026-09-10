<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Models/SliderModel.php';

$model = new SliderModel();
$model->ensureSchema();

echo "Hero slider CMS schema is up to date.\n";
echo 'Slides: ' . $model->count() . "\n";
