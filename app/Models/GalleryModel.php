<?php
declare(strict_types=1);

class GalleryModel extends Model
{
    protected string $table = 'gallery';

    public function getPublished(?string $category = null): array
    {
        if ($category) {
            $stmt = $this->db->prepare(
                "SELECT * FROM gallery WHERE is_published = 1 AND category = ? ORDER BY sort_order ASC, id DESC"
            );
            $stmt->execute([$category]);
            return $stmt->fetchAll();
        }
        return $this->db->query(
            "SELECT * FROM gallery WHERE is_published = 1 ORDER BY sort_order ASC, id DESC"
        )->fetchAll();
    }

    public function getCategories(): array
    {
        return $this->db->query(
            "SELECT DISTINCT category FROM gallery WHERE is_published = 1 AND category IS NOT NULL AND category != '' ORDER BY category"
        )->fetchAll(PDO::FETCH_COLUMN);
    }

    public function countPublished(?string $category = null): int
    {
        if ($category) {
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) FROM gallery WHERE is_published = 1 AND category = ?'
            );
            $stmt->execute([$category]);
            return (int)$stmt->fetchColumn();
        }

        return (int)$this->db->query(
            'SELECT COUNT(*) FROM gallery WHERE is_published = 1'
        )->fetchColumn();
    }
}
