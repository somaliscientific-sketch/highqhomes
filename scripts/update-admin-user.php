<?php
declare(strict_types=1);

/**
 * One-time admin credential update.
 * Usage: php scripts/update-admin-user.php info@highqhomes.net "your-password"
 */

require_once __DIR__ . '/../config/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Models/UserModel.php';

$email = strtolower(trim($argv[1] ?? 'info@highqhomes.net'));
$password = (string)($argv[2] ?? '');

if ($email === '' || $password === '') {
    fwrite(STDERR, "Usage: php scripts/update-admin-user.php <email> <password>\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$db = Database::getInstance();
$stmt = $db->prepare(
    'UPDATE users SET email = ?, password = ?, name = ?, role = ?, is_active = 1 WHERE email IN (?, ?) OR id = 2'
);
$stmt->execute([$email, $hash, 'ICT Admin', 'super_admin', 'info@highqhomes.com', 'info@highqhomes.net']);

if ($stmt->rowCount() === 0) {
    $insert = $db->prepare(
        'INSERT INTO users (name, email, password, role, is_active) VALUES (?, ?, ?, ?, 1)'
    );
    $insert->execute(['ICT Admin', $email, $hash, 'super_admin']);
    echo "Created admin user: {$email}\n";
} else {
    echo "Updated admin user: {$email}\n";
}

$user = (new UserModel())->findByEmail($email);
echo password_verify($password, $user['password'] ?? '') ? "Password verified OK\n" : "Password verify FAILED\n";
