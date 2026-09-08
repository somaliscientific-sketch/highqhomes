<?php
declare(strict_types=1);

class MenuModel extends Model
{
    protected string $table = 'menus';

    public function getPublished(string $location = 'primary'): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM menus WHERE location = ? AND is_published = 1 ORDER BY sort_order ASC, id ASC"
        );
        $stmt->execute([$location]);
        return $stmt->fetchAll();
    }
}
