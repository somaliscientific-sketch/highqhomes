<?php
declare(strict_types=1);

class PaintModel extends Model
{
    protected string $table = 'paints';

    public function getPublished(): array
    {
        $rows = $this->db->query(
            "SELECT * FROM paints WHERE is_published = 1 ORDER BY sort_order ASC, created_at DESC"
        )->fetchAll();
        return array_map([$this, 'decode'], $rows);
    }

    public function getFiltered(?string $category = null, ?string $brand = null): array
    {
        $conditions = ['is_published = 1'];
        $params     = [];

        if ($category !== null && $category !== '') {
            $conditions[] = 'category = ?';
            $params[]     = $category;
        }
        if ($brand !== null && $brand !== '') {
            $conditions[] = 'brand = ?';
            $params[]     = $brand;
        }

        $where = implode(' AND ', $conditions);
        $stmt  = $this->db->prepare(
            "SELECT * FROM paints WHERE {$where} ORDER BY sort_order ASC, created_at DESC"
        );
        $stmt->execute($params);
        return array_map([$this, 'decode'], $stmt->fetchAll());
    }

    /** @return list<string> */
    public function getCategories(): array
    {
        $rows = $this->db->query(
            "SELECT DISTINCT category FROM paints WHERE is_published = 1 AND category IS NOT NULL AND category != '' ORDER BY category ASC"
        )->fetchAll(PDO::FETCH_COLUMN);
        return array_values(array_filter($rows));
    }

    /** @return list<string> */
    public function getBrands(): array
    {
        $rows = $this->db->query(
            "SELECT DISTINCT brand FROM paints WHERE is_published = 1 AND brand IS NOT NULL AND brand != '' ORDER BY brand ASC"
        )->fetchAll(PDO::FETCH_COLUMN);
        return array_values(array_filter($rows));
    }

    public function getRelated(int $excludeId, ?string $category, int $limit = 3): array
    {
        if ($category) {
            $stmt = $this->db->prepare(
                "SELECT * FROM paints WHERE is_published = 1 AND id != ? AND category = ? ORDER BY sort_order ASC LIMIT ?"
            );
            $stmt->execute([$excludeId, $category, $limit]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT * FROM paints WHERE is_published = 1 AND id != ? ORDER BY sort_order ASC LIMIT ?"
            );
            $stmt->execute([$excludeId, $limit]);
        }
        return array_map([$this, 'decode'], $stmt->fetchAll());
    }

    public function countPublished(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM paints WHERE is_published = 1')->fetchColumn();
    }

    public function findBySlug(string $slug): ?array
    {
        $row = $this->findBy('slug', $slug);
        return $row ? $this->decode($row) : null;
    }

    private function decode(array $row): array
    {
        if (!empty($row['features']) && is_string($row['features'])) {
            $row['features'] = json_decode($row['features'], true) ?? [];
        } else {
            $row['features'] = [];
        }
        if (!empty($row['specifications']) && is_string($row['specifications'])) {
            $row['specifications'] = json_decode($row['specifications'], true) ?? [];
        } else {
            $row['specifications'] = [];
        }
        if (!empty($row['gallery_images']) && is_string($row['gallery_images'])) {
            $row['gallery_images'] = json_decode($row['gallery_images'], true) ?? [];
        } else {
            $row['gallery_images'] = [];
        }
        return $row;
    }
}
