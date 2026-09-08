<?php
declare(strict_types=1);

class MediaModel extends Model
{
    protected string $table = 'media';

    public function search(string $term = '', int $page = 1, int $perPage = 24, ?string $folder = null, ?string $type = null): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $where = [];
        $args = [];

        if ($term !== '') {
            $where[] = '(title LIKE ? OR alt_text LIKE ? OR caption LIKE ? OR folder LIKE ? OR original_name LIKE ?)';
            $like = "%{$term}%";
            array_push($args, $like, $like, $like, $like, $like);
        }
        if ($folder !== null && $folder !== '') {
            $where[] = 'folder = ?';
            $args[] = $folder;
        }
        if ($type === 'image') {
            $where[] = "mime_type LIKE 'image/%'";
        } elseif ($type === 'video') {
            $where[] = "mime_type LIKE 'video/%'";
        } elseif ($type === 'document') {
            $where[] = "(mime_type LIKE 'application/%' OR mime_type LIKE 'text/%')";
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $count = $this->db->prepare("SELECT COUNT(*) FROM media {$whereSql}");
        $count->execute($args);
        $total = (int)$count->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM media {$whereSql} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}");
        $stmt->execute($args);

        return [
            'items'      => $stmt->fetchAll(),
            'total'      => $total,
            'per_page'   => $perPage,
            'current'    => $page,
            'last_page'  => max(1, (int)ceil($total / $perPage)),
        ];
    }

    public function folders(): array
    {
        $stmt = $this->db->query('SELECT folder, COUNT(*) AS total FROM media GROUP BY folder ORDER BY folder ASC');
        return $stmt->fetchAll();
    }

    public function trackUsage(int $mediaId, string $entityType, int $entityId, ?string $field = null): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO media_usage (media_id, entity_type, entity_id, field_name) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$mediaId, $entityType, $entityId, $field]);
    }

    public function usage(int $mediaId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM media_usage WHERE media_id = ? ORDER BY id DESC');
        $stmt->execute([$mediaId]);
        return $stmt->fetchAll();
    }

    public function usageCount(int $mediaId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM media_usage WHERE media_id = ?');
        $stmt->execute([$mediaId]);
        return (int)$stmt->fetchColumn();
    }

    public function clearUsage(int $mediaId): void
    {
        $stmt = $this->db->prepare('DELETE FROM media_usage WHERE media_id = ?');
        $stmt->execute([$mediaId]);
    }

    public function isImage(array $item): bool
    {
        $mime = (string)($item['mime_type'] ?? $item['file_type'] ?? '');
        return str_starts_with($mime, 'image/');
    }

    public function isVideo(array $item): bool
    {
        $mime = (string)($item['mime_type'] ?? $item['file_type'] ?? '');
        return str_starts_with($mime, 'video/');
    }
}
