<?php
declare(strict_types=1);

class GalleryModel extends Model
{
    protected string $table = 'gallery';

    private static bool $showcaseReady = false;

    public function getPublished(?string $category = null): array
    {
        try {
            if ($category) {
                $stmt = $this->db->prepare(
                    "SELECT * FROM gallery WHERE is_published = 1 AND category = ? ORDER BY sort_order ASC, id DESC"
                );
                $stmt->execute([$category]);
                $rows = $this->withoutStale($stmt->fetchAll());
            } else {
                $rows = $this->withoutStale($this->db->query(
                    "SELECT * FROM gallery WHERE is_published = 1 ORDER BY sort_order ASC, id DESC"
                )->fetchAll());
            }
            if ($rows !== []) {
                return $rows;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Gallery getPublished: ' . $e->getMessage());
            }
        }

        return galleryShowcaseItems($category);
    }

    public function getCategories(): array
    {
        $rows = [];
        try {
            $rows = $this->withoutStale($this->db->query(
                "SELECT * FROM gallery WHERE is_published = 1 AND category IS NOT NULL AND category != ''"
            )->fetchAll());
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Gallery getCategories: ' . $e->getMessage());
            }
        }

        if ($rows === []) {
            $rows = galleryShowcaseItems();
        }

        $cats = array_values(array_unique(array_filter(array_map(
            static fn(array $row): string => (string)($row['category'] ?? ''),
            $rows
        ))));

        $order = ['residential' => 0, 'exterior' => 1, 'construction' => 2];
        usort($cats, static function (string $a, string $b) use ($order): int {
            return ($order[$a] ?? 9) <=> ($order[$b] ?? 9);
        });

        return $cats;
    }

    public function countPublished(?string $category = null): int
    {
        try {
            if ($category) {
                $stmt = $this->db->prepare(
                    'SELECT * FROM gallery WHERE is_published = 1 AND category = ?'
                );
                $stmt->execute([$category]);
                $count = count($this->withoutStale($stmt->fetchAll()));
            } else {
                $count = count($this->withoutStale($this->db->query(
                    'SELECT * FROM gallery WHERE is_published = 1'
                )->fetchAll()));
            }
            if ($count > 0) {
                return $count;
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Gallery countPublished: ' . $e->getMessage());
            }
        }

        return count(galleryShowcaseItems($category));
    }

    public function ensureShowcase(): void
    {
        if (self::$showcaseReady) {
            return;
        }
        self::$showcaseReady = true;

        try {
            foreach (galleryShowcaseItems() as $row) {
                $existing = $this->findBy('image', (string)$row['image']);
                if ($existing) {
                    continue;
                }
                unset($row['id']);
                $this->insert($row);
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Gallery showcase: ' . $e->getMessage());
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
            static fn(array $row): bool => !isStaleGalleryImage($row['image'] ?? null)
        ));
    }
}
