<?php
declare(strict_types=1);

class PageModel extends Model
{
    protected string $table = 'pages';

    public function findPublishedBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE slug = ? AND is_published = 1 LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getPublished(int $limit = 6, array $excludeSlugs = ['privacy-policy', 'terms-of-service']): array
    {
        $excludeSlugs = array_values(array_filter($excludeSlugs));
        $sql = 'SELECT * FROM pages WHERE is_published = 1';
        $params = [];

        if ($excludeSlugs) {
            $placeholders = implode(',', array_fill(0, count($excludeSlugs), '?'));
            $sql .= " AND slug NOT IN ({$placeholders})";
            $params = array_merge($params, $excludeSlugs);
        }

        $sql .= ' ORDER BY COALESCE(published_at, created_at) DESC, sort_order ASC LIMIT ?';
        $params[] = $limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function search(string $term = '', int $page = 1, int $perPage = 15): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $where = '';
        $args = [];

        if ($term !== '') {
            $where = "WHERE title LIKE ? OR slug LIKE ? OR excerpt LIKE ?";
            $like = "%{$term}%";
            $args = [$like, $like, $like];
        }

        $count = $this->db->prepare("SELECT COUNT(*) FROM pages {$where}");
        $count->execute($args);
        $total = (int)$count->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM pages {$where} ORDER BY sort_order ASC, id DESC LIMIT {$perPage} OFFSET {$offset}");
        $stmt->execute($args);

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current' => $page,
            'last_page' => (int)ceil($total / $perPage),
        ];
    }
}
