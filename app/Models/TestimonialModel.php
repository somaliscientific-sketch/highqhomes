<?php
declare(strict_types=1);

class TestimonialModel extends Model
{
    protected string $table = 'testimonials';

    public function getPublished(): array
    {
        return $this->db->query(
            "SELECT * FROM testimonials WHERE is_published = 1 ORDER BY is_featured DESC, created_at DESC"
        )->fetchAll();
    }

    public function getFeatured(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM testimonials WHERE is_published = 1 AND is_featured = 1
             ORDER BY created_at DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        $rows = $stmt->fetchAll();

        if ($rows) {
            return $rows;
        }

        return array_slice($this->getPublished(), 0, $limit);
    }
}
