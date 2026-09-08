<?php
declare(strict_types=1);

class AdminLogModel extends Model
{
    protected string $table = 'admin_logs';

    /** @return array{rows: array, total: int, page: int, pages: int} */
    public function paginateFiltered(int $page = 1, int $perPage = 30, array $filters = []): array
    {
        $page = max(1, $page);
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['module'])) {
            $where[] = 'module = ?';
            $params[] = $filters['module'];
        }
        if (!empty($filters['action'])) {
            $where[] = 'action = ?';
            $params[] = $filters['action'];
        }
        if (!empty($filters['user_id'])) {
            $where[] = 'user_id = ?';
            $params[] = (int)$filters['user_id'];
        }
        if (!empty($filters['q'])) {
            $where[] = '(description LIKE ? OR user_name LIKE ? OR user_email LIKE ?)';
            $like = '%' . $filters['q'] . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sqlWhere = implode(' AND ', $where);
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM admin_logs WHERE {$sqlWhere}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();
        $pages = max(1, (int)ceil($total / $perPage));
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            "SELECT * FROM admin_logs WHERE {$sqlWhere} ORDER BY created_at DESC, id DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return [
            'rows'  => $stmt->fetchAll(),
            'total' => $total,
            'page'  => $page,
            'pages' => $pages,
        ];
    }

    public function distinctModules(): array
    {
        return array_column(
            $this->db->query('SELECT DISTINCT module FROM admin_logs ORDER BY module')->fetchAll(),
            'module'
        );
    }

    public function distinctActions(): array
    {
        return array_column(
            $this->db->query('SELECT DISTINCT action FROM admin_logs ORDER BY action')->fetchAll(),
            'action'
        );
    }

    public function purgeOlderThan(int $days): int
    {
        $days = max(7, $days);
        $stmt = $this->db->prepare('DELETE FROM admin_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)');
        $stmt->execute([$days]);
        return $stmt->rowCount();
    }
}
