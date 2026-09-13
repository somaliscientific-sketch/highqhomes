<?php
declare(strict_types=1);

class ProjectModel extends Model
{
    protected string $table = 'projects';

    public function getPublished(?string $category = null, ?string $status = null): array
    {
        $sql    = "SELECT * FROM projects WHERE is_published = 1";
        $params = [];

        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY is_featured DESC, created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        return array_map([$this, 'decode'], $rows);
    }

    public function getFeatured(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM projects WHERE is_published = 1 AND is_featured = 1
             ORDER BY sort_order ASC, created_at DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return array_map([$this, 'decode'], $stmt->fetchAll());
    }

    public function getLatest(int $limit = 6, bool $excludeFeatured = true): array
    {
        $sql = "SELECT * FROM projects WHERE is_published = 1";
        if ($excludeFeatured) {
            $sql .= " AND is_featured = 0";
        }
        $sql .= " ORDER BY created_at DESC, sort_order ASC LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return array_map([$this, 'decode'], $stmt->fetchAll());
    }

    public function findBySlug(string $slug): ?array
    {
        $row = $this->findBy('slug', $slug);
        return $row ? $this->decode($row) : null;
    }

    public function getCategories(): array
    {
        return $this->db->query(
            "SELECT DISTINCT category FROM projects WHERE is_published = 1 ORDER BY category"
        )->fetchAll(PDO::FETCH_COLUMN);
    }

    public function countPublished(?string $category = null, ?string $status = null): int
    {
        $sql    = 'SELECT COUNT(*) FROM projects WHERE is_published = 1';
        $params = [];

        if ($category) {
            $sql .= ' AND category = ?';
            $params[] = $category;
        }
        if ($status) {
            $sql .= ' AND status = ?';
            $params[] = $status;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM projects WHERE is_published = 1 AND status = ?'
        );
        $stmt->execute([$status]);
        return (int)$stmt->fetchColumn();
    }

    public function getRelated(int $excludeId, ?string $category = null, int $limit = 3): array
    {
        $sql    = 'SELECT * FROM projects WHERE is_published = 1 AND id != ?';
        $params = [$excludeId];

        if ($category) {
            $sql .= ' AND category = ?';
            $params[] = $category;
        }

        $sql .= ' ORDER BY is_featured DESC, created_at DESC LIMIT ?';
        $params[] = $limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return array_map([$this, 'decode'], $stmt->fetchAll());
    }

    private function decode(array $row): array
    {
        if (!empty($row['gallery_images']) && is_string($row['gallery_images'])) {
            $row['gallery_images'] = json_decode($row['gallery_images'], true) ?? [];
        } else {
            $row['gallery_images'] = [];
        }
        return $row;
    }
}
