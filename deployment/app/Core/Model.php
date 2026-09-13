<?php
declare(strict_types=1);

abstract class Model
{
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ─── Core CRUD ─────────────────────────────────────────────────

    public function findAll(string $orderBy = 'id', string $dir = 'ASC', ?int $limit = null, int $offset = 0): array
    {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `{$orderBy}` {$dir}";
        if ($limit !== null) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findBy(string $column, mixed $value): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$column}` = ? LIMIT 1");
        $stmt->execute([$value]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findAllBy(string $column, mixed $value, string $orderBy = 'id', string $dir = 'ASC'): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$column}` = ? ORDER BY `{$orderBy}` {$dir}"
        );
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    public function insert(array $data): int
    {
        $columns = implode('`, `', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($col) => "`{$col}` = ?", array_keys($data)));
        $stmt = $this->db->prepare(
            "UPDATE `{$this->table}` SET {$sets} WHERE `{$this->primaryKey}` = ?"
        );
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?");
        return $stmt->execute([$id]);
    }

    public function count(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM `{$this->table}`")->fetchColumn();
    }

    public function countWhere(string $column, mixed $value): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` WHERE `{$column}` = ?");
        $stmt->execute([$value]);
        return (int)$stmt->fetchColumn();
    }

    public function paginate(int $page = 1, int $perPage = 20, string $orderBy = 'id', string $dir = 'DESC'): array
    {
        $page   = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $total  = $this->count();
        $items  = $this->findAll($orderBy, $dir, $perPage, $offset);

        return [
            'items'       => $items,
            'total'       => $total,
            'per_page'    => $perPage,
            'current'     => $page,
            'last_page'   => (int)ceil($total / $perPage),
        ];
    }

    // ─── Slug utilities ─────────────────────────────────────────────

    public static function slugify(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
        $text = preg_replace('/[\s\-]+/', '-', trim($text));
        return trim($text, '-');
    }

    public function uniqueSlug(string $text, ?int $excludeId = null): string
    {
        $base = self::slugify($text);
        $slug = $base;
        $i    = 1;

        while (true) {
            $sql  = "SELECT id FROM `{$this->table}` WHERE slug = ?";
            $args = [$slug];
            if ($excludeId !== null) {
                $sql  .= " AND id != ?";
                $args[] = $excludeId;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($args);
            if (!$stmt->fetch()) break;
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
