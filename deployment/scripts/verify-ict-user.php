<?php
require_once dirname(__DIR__) . '/config/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Models/UserModel.php';

$u = (new UserModel())->findByEmail('info@highqhomes.com');
if (!$u) {
    echo "User missing\n";
    exit(1);
}
echo 'role=' . $u['role'] . ' pass=' . (password_verify('garowe1234', $u['password']) ? 'ok' : 'fail') . "\n";
