<?php
declare(strict_types=1);

class ProjectModel extends Model
{
    protected string $table = 'projects';

    private static bool $showcaseReady = false;

    public function getPublished(?string $category = null, ?string $status = null): array
    {
        try {
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

            $sql .= " ORDER BY sort_order ASC, is_featured DESC, created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $rows = array_map([$this, 'decode'], $stmt->fetchAll());
            if ($rows !== []) {
                return $rows;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project getPublished: ' . $e->getMessage());
            }
        }

        return $this->filterShowcase($category, $status);
    }

    public function getFeatured(int $limit = 6): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM projects WHERE is_published = 1 AND is_featured = 1 AND status <> 'planned'
                 ORDER BY sort_order ASC, created_at DESC LIMIT " . (int)$limit
            );
            $stmt->execute();
            $rows = array_map([$this, 'decode'], $stmt->fetchAll());
            if ($rows !== []) {
                return $rows;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project getFeatured: ' . $e->getMessage());
            }
        }

        $rows = array_values(array_filter(
            $this->filterShowcase(null, null),
            static fn(array $row): bool => !empty($row['is_featured']) && !isUpcomingProject($row)
        ));

        return array_slice($rows, 0, $limit);
    }

    public function getLatest(int $limit = 6, bool $excludeFeatured = true): array
    {
        try {
            $sql = "SELECT * FROM projects WHERE is_published = 1 AND status <> 'planned'";
            if ($excludeFeatured) {
                $sql .= " AND is_featured = 0";
            }
            $sql .= " ORDER BY sort_order ASC, created_at DESC LIMIT " . (int)$limit;

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $rows = array_map([$this, 'decode'], $stmt->fetchAll());
            if ($rows !== []) {
                return $rows;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project getLatest: ' . $e->getMessage());
            }
        }

        $rows = array_values(array_filter(
            $this->filterShowcase(null, null),
            static fn(array $row): bool => !isUpcomingProject($row)
        ));
        if ($excludeFeatured) {
            $rows = array_values(array_filter(
                $rows,
                static fn(array $row): bool => empty($row['is_featured'])
            ));
        }

        return array_slice($rows, 0, $limit);
    }

    public function findBySlug(string $slug): ?array
    {
        try {
            $row = $this->findBy('slug', $slug);
            if ($row) {
                return $this->decode($row);
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project findBySlug: ' . $e->getMessage());
            }
        }

        foreach ($this->filterShowcase(null, null) as $item) {
            if (($item['slug'] ?? '') === $slug) {
                return $item;
            }
        }

        return null;
    }

    public function getCategories(): array
    {
        try {
            $rows = $this->db->query(
                "SELECT DISTINCT category FROM projects WHERE is_published = 1 ORDER BY category"
            )->fetchAll(PDO::FETCH_COLUMN);
            if ($rows !== []) {
                return $rows;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project getCategories: ' . $e->getMessage());
            }
        }

        return array_values(array_unique(array_map(
            static fn(array $row): string => (string)($row['category'] ?? 'residential'),
            $this->filterShowcase(null, null)
        )));
    }

    public function getStatuses(): array
    {
        $rows = [];
        try {
            $rows = $this->db->query(
                "SELECT DISTINCT status FROM projects WHERE is_published = 1"
            )->fetchAll(PDO::FETCH_COLUMN);
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project getStatuses: ' . $e->getMessage());
            }
        }

        if ($rows === []) {
            $rows = array_values(array_unique(array_map(
                static fn(array $row): string => (string)($row['status'] ?? 'completed'),
                $this->filterShowcase(null, null)
            )));
        }

        $order = ['planned' => 0, 'in_progress' => 1, 'completed' => 2];
        usort($rows, static function (string $a, string $b) use ($order): int {
            return ($order[$a] ?? 9) <=> ($order[$b] ?? 9);
        });

        return $rows;
    }

    public function countPublished(?string $category = null, ?string $status = null): int
    {
        try {
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
            $count = (int)$stmt->fetchColumn();
            if ($count > 0) {
                return $count;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project countPublished: ' . $e->getMessage());
            }
        }

        return count($this->filterShowcase($category, $status));
    }

    public function countByStatus(string $status): int
    {
        try {
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) FROM projects WHERE is_published = 1 AND status = ?'
            );
            $stmt->execute([$status]);
            $count = (int)$stmt->fetchColumn();
            if ($this->countPublishedFromDb() > 0) {
                return $count;
            }
            if ($count > 0) {
                return $count;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project countByStatus: ' . $e->getMessage());
            }
        }

        return count($this->filterShowcase(null, $status));
    }

    public function yearSpan(): string
    {
        try {
            $row = $this->db->query(
                'SELECT MIN(project_year) AS y_min, MAX(project_year) AS y_max
                 FROM projects WHERE is_published = 1 AND project_year IS NOT NULL'
            )->fetch();

            $min = (int)($row['y_min'] ?? 0);
            $max = (int)($row['y_max'] ?? 0);
            if ($min > 0) {
                return $max > $min ? $min . '–' . $max : (string)$min;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project yearSpan: ' . $e->getMessage());
            }
        }

        $years = array_values(array_filter(array_map(
            static fn(array $row): int => (int)($row['project_year'] ?? 0),
            $this->filterShowcase(null, null)
        )));
        if ($years === []) {
            return '';
        }

        $min = min($years);
        $max = max($years);
        return $max > $min ? $min . '–' . $max : (string)$min;
    }

    public function getRelated(int $excludeId, ?string $category = null, int $limit = 3, ?string $excludeSlug = null): array
    {
        try {
            if ($excludeId > 0) {
                $sql    = 'SELECT * FROM projects WHERE is_published = 1 AND id != ?';
                $params = [$excludeId];

                if ($category) {
                    $sql .= ' AND category = ?';
                    $params[] = $category;
                }

                $sql .= ' ORDER BY sort_order ASC, is_featured DESC, created_at DESC LIMIT ' . (int)$limit;

                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $rows = array_map([$this, 'decode'], $stmt->fetchAll());
                if ($rows !== []) {
                    return $rows;
                }
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project getRelated: ' . $e->getMessage());
            }
        }

        $rows = array_values(array_filter(
            $this->filterShowcase($category, null),
            static function (array $row) use ($excludeId, $excludeSlug): bool {
                if ($excludeSlug !== null && ($row['slug'] ?? '') === $excludeSlug) {
                    return false;
                }
                return (int)($row['id'] ?? 0) !== $excludeId;
            }
        ));

        return array_slice($rows, 0, $limit);
    }

    /**
     * Seed the portfolio when the live table is empty, and insert catalog
     * additions (such as the upcoming villa) without overwriting admin projects.
     */
    public function ensureShowcase(): void
    {
        if (self::$showcaseReady) {
            return;
        }
        self::$showcaseReady = true;

        try {
            if ($this->countPublishedFromDb() === 0) {
                foreach ($this->showcaseCatalog() as $row) {
                    $this->insertCatalogRowIfMissing($row);
                }
                return;
            }

            $this->syncUpcomingCatalog();
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project showcase: ' . $e->getMessage());
            }
        }
    }

    private function syncUpcomingCatalog(): void
    {
        foreach ($this->showcaseCatalog() as $row) {
            if (($row['slug'] ?? '') !== 'desert-courtyard-villa') {
                continue;
            }
            $this->insertCatalogRowIfMissing($row);
            return;
        }
    }

    /** @param array<string, mixed> $row */
    private function insertCatalogRowIfMissing(array $row): void
    {
        $slug = (string)($row['slug'] ?? '');
        if ($slug === '' || $this->findBy('slug', $slug)) {
            return;
        }

        unset($row['id']);
        if (isset($row['gallery_images']) && is_array($row['gallery_images'])) {
            if ($row['gallery_images'] === []) {
                unset($row['gallery_images']);
            } else {
                $row['gallery_images'] = json_encode($row['gallery_images']);
            }
        }
        if (($row['gallery_images'] ?? null) === null) {
            unset($row['gallery_images']);
        }

        $this->insert($row);
    }

    /** @return list<array<string, mixed>> */
    private function filterShowcase(?string $category, ?string $status): array
    {
        $rows = array_map([$this, 'hydrateShowcase'], $this->showcaseCatalog());
        if ($category) {
            $rows = array_values(array_filter(
                $rows,
                static fn(array $row): bool => ($row['category'] ?? '') === $category
            ));
        }
        if ($status) {
            $rows = array_values(array_filter(
                $rows,
                static fn(array $row): bool => ($row['status'] ?? '') === $status
            ));
        }

        return $rows;
    }

    /** @param array<string, mixed> $row */
    private function hydrateShowcase(array $row): array
    {
        if (is_string($row['gallery_images'] ?? null)) {
            $row['gallery_images'] = json_decode((string)$row['gallery_images'], true) ?? [];
        } elseif (!is_array($row['gallery_images'] ?? null)) {
            $row['gallery_images'] = [];
        }
        $row['id'] = $row['id'] ?? 0;
        $row['is_published'] = 1;
        return $row;
    }

    private function countPublishedFromDb(): int
    {
        try {
            return (int)$this->db->query(
                'SELECT COUNT(*) FROM projects WHERE is_published = 1'
            )->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /** @return list<array<string, mixed>> */
    private function showcaseCatalog(): array
    {
        return projectShowcaseItems();
    }

    private function decode(array $row): array
    {
        if (!empty($row['gallery_images']) && is_string($row['gallery_images'])) {
            $row['gallery_images'] = json_decode($row['gallery_images'], true) ?? [];
        } else {
            $row['gallery_images'] = is_array($row['gallery_images'] ?? null) ? $row['gallery_images'] : [];
        }
        return $row;
    }
}
