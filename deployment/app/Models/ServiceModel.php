<?php
declare(strict_types=1);

class ServiceModel extends Model
{
    protected string $table = 'services';

    public function getPublished(): array
    {
        return $this->db->query(
            "SELECT * FROM services WHERE is_published = 1 ORDER BY is_featured DESC, sort_order ASC"
        )->fetchAll();
    }

    public function getFeatured(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM services WHERE is_published = 1 AND is_featured = 1
             ORDER BY sort_order ASC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function countPublished(): int
    {
        return (int)$this->db->query(
            'SELECT COUNT(*) FROM services WHERE is_published = 1'
        )->fetchColumn();
    }

    public function countFeatured(): int
    {
        return (int)$this->db->query(
            'SELECT COUNT(*) FROM services WHERE is_published = 1 AND is_featured = 1'
        )->fetchColumn();
    }

    public function getRelated(int $excludeId, int $limit = 3): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM services
             WHERE is_published = 1 AND id != ?
             ORDER BY is_featured DESC, sort_order ASC
             LIMIT ?'
        );
        $stmt->execute([$excludeId, $limit]);
        return $stmt->fetchAll();
    }
}
