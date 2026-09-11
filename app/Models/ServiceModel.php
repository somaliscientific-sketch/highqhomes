<?php
declare(strict_types=1);

class ServiceModel extends Model
{
    protected string $table = 'services';

    private static bool $showcaseReady = false;

    public function getPublished(): array
    {
        try {
            $rows = $this->withoutStale($this->db->query(
                "SELECT * FROM services WHERE is_published = 1 ORDER BY is_featured DESC, sort_order ASC"
            )->fetchAll());
            if ($rows !== []) {
                return $rows;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Service getPublished: ' . $e->getMessage());
            }
        }

        return serviceShowcaseItems();
    }

    public function getFeatured(int $limit = 6): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM services WHERE is_published = 1 AND is_featured = 1
                 ORDER BY sort_order ASC LIMIT " . (int)$limit
            );
            $stmt->execute();
            $rows = $this->withoutStale($stmt->fetchAll());
            if ($rows !== []) {
                return $rows;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Service getFeatured: ' . $e->getMessage());
            }
        }

        $rows = array_values(array_filter(
            serviceShowcaseItems(),
            static fn(array $row): bool => !empty($row['is_featured'])
        ));

        return array_slice($rows, 0, $limit);
    }

    public function findBySlug(string $slug): ?array
    {
        try {
            $row = $this->findBy('slug', $slug);
            if ($row) {
                return $row;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Service findBySlug: ' . $e->getMessage());
            }
        }

        foreach (serviceShowcaseItems() as $item) {
            if (($item['slug'] ?? '') === $slug) {
                return $item;
            }
        }

        return null;
    }

    public function countPublished(): int
    {
        try {
            $count = count($this->withoutStale($this->db->query(
                "SELECT slug FROM services WHERE is_published = 1"
            )->fetchAll()));
            if ($count > 0) {
                return $count;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Service countPublished: ' . $e->getMessage());
            }
        }

        return count(serviceShowcaseItems());
    }

    public function countFeatured(): int
    {
        try {
            $count = count($this->withoutStale($this->db->query(
                "SELECT slug FROM services WHERE is_published = 1 AND is_featured = 1"
            )->fetchAll()));
            if ($count > 0) {
                return $count;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Service countFeatured: ' . $e->getMessage());
            }
        }

        return count(array_filter(
            serviceShowcaseItems(),
            static fn(array $row): bool => !empty($row['is_featured'])
        ));
    }

    public function getRelated(int $excludeId, int $limit = 3, ?string $excludeSlug = null): array
    {
        try {
            if ($excludeId > 0) {
                $stmt = $this->db->prepare(
                    'SELECT * FROM services
                     WHERE is_published = 1 AND id != ?
                     ORDER BY is_featured DESC, sort_order ASC
                     LIMIT ' . (int)$limit
                );
                $stmt->execute([$excludeId]);
                $rows = $this->withoutStale($stmt->fetchAll());
                if ($rows !== []) {
                    return $rows;
                }
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Service getRelated: ' . $e->getMessage());
            }
        }

        $rows = array_values(array_filter(
            serviceShowcaseItems(),
            static function (array $row) use ($excludeId, $excludeSlug): bool {
                if ($excludeSlug !== null && ($row['slug'] ?? '') === $excludeSlug) {
                    return false;
                }
                return (int)($row['id'] ?? 0) !== $excludeId;
            }
        ));

        return array_slice($rows, 0, $limit);
    }

    public function ensureShowcase(): void
    {
        if (self::$showcaseReady) {
            return;
        }
        self::$showcaseReady = true;

        try {
            foreach (serviceShowcaseItems() as $row) {
                if ($this->findBy('slug', (string)$row['slug'])) {
                    continue;
                }
                unset($row['id']);
                $this->insert($row);
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Service showcase: ' . $e->getMessage());
            }
        }
    }

    /**
     * @param list<array<string, mixed>> $rows
     * @return list<array<string, mixed>>
     */
    private function withoutStale(array $rows): array
    {
        return array_values(array_filter(
            $rows,
            static fn(array $row): bool => !isStaleServiceSlug((string)($row['slug'] ?? ''))
        ));
    }
}
