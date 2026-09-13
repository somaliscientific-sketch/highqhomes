<?php
declare(strict_types=1);

class UserModel extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    public function updateLastLogin(int $id): void
    {
        $this->update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function changePassword(int $id, string $newPassword): void
    {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->update($id, ['password' => $hash]);
    }
}
