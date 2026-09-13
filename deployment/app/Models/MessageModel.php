<?php
declare(strict_types=1);

class MessageModel extends Model
{
    protected string $table = 'messages';

    public function getUnreadCount(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();
    }

    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM messages ORDER BY created_at DESC LIMIT ?"
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function markRead(int $id): void
    {
        $this->update($id, ['is_read' => 1]);
    }

    public function toggleStar(int $id): void
    {
        $this->db->prepare(
            "UPDATE messages SET is_starred = NOT is_starred WHERE id = ?"
        )->execute([$id]);
    }

    public function rateLimitCheck(string $ip, int $maxPerHour = 5): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM messages WHERE ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)"
        );
        $stmt->execute([$ip]);
        return (int)$stmt->fetchColumn() < $maxPerHour;
    }

    public function getStats(): array
    {
        return [
            'total'   => (int)$this->db->query('SELECT COUNT(*) FROM messages')->fetchColumn(),
            'unread'  => $this->getUnreadCount(),
            'starred' => (int)$this->db->query('SELECT COUNT(*) FROM messages WHERE is_starred = 1')->fetchColumn(),
        ];
    }

    public function paginateFiltered(string $filter, int $page = 1, int $perPage = 20, string $q = ''): array
    {
        $conditions = [];
        $params     = [];

        if ($filter === 'unread') {
            $conditions[] = 'is_read = 0';
        } elseif ($filter === 'starred') {
            $conditions[] = 'is_starred = 1';
        }

        $q = trim($q);
        if ($q !== '') {
            $like = '%' . $q . '%';
            $conditions[] = '(name LIKE ? OR email LIKE ? OR phone LIKE ? OR subject LIKE ? OR message LIKE ?)';
            array_push($params, $like, $like, $like, $like, $like);
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM messages {$where}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            "SELECT * FROM messages {$where} ORDER BY created_at DESC LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, $perPage, $offset]);
        $items = $stmt->fetchAll();

        return [
            'items'     => $items,
            'total'     => $total,
            'per_page'  => $perPage,
            'current'   => $page,
            'last_page' => max(1, (int)ceil($total / $perPage)),
        ];
    }
}
