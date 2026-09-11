<?php
declare(strict_types=1);

class ProjectModel extends Model
{
    protected string $table = 'projects';

    private static bool $showcaseReady = false;

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

        $sql .= " ORDER BY sort_order ASC, is_featured DESC, created_at DESC";
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
        $sql .= " ORDER BY sort_order ASC, created_at DESC LIMIT ?";

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

    public function getStatuses(): array
    {
        $rows = $this->db->query(
            "SELECT DISTINCT status FROM projects WHERE is_published = 1"
        )->fetchAll(PDO::FETCH_COLUMN);

        $order = ['completed' => 0, 'in_progress' => 1, 'planned' => 2];
        usort($rows, static function (string $a, string $b) use ($order): int {
            return ($order[$a] ?? 9) <=> ($order[$b] ?? 9);
        });

        return $rows;
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

    public function yearSpan(): string
    {
        $row = $this->db->query(
            'SELECT MIN(project_year) AS y_min, MAX(project_year) AS y_max
             FROM projects WHERE is_published = 1 AND project_year IS NOT NULL'
        )->fetch();

        $min = (int)($row['y_min'] ?? 0);
        $max = (int)($row['y_max'] ?? 0);
        if ($min <= 0) {
            return '';
        }
        if ($max <= $min) {
            return (string)$min;
        }

        return $min . '–' . $max;
    }

    public function getRelated(int $excludeId, ?string $category = null, int $limit = 3): array
    {
        $sql    = 'SELECT * FROM projects WHERE is_published = 1 AND id != ?';
        $params = [$excludeId];

        if ($category) {
            $sql .= ' AND category = ?';
            $params[] = $category;
        }

        $sql .= ' ORDER BY sort_order ASC, is_featured DESC, created_at DESC LIMIT ?';
        $params[] = $limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return array_map([$this, 'decode'], $stmt->fetchAll());
    }

    /**
     * Seed a real-photo portfolio when the live table has no published projects.
     * Does not overwrite projects created in admin.
     */
    public function ensureShowcase(): void
    {
        if (self::$showcaseReady) {
            return;
        }
        self::$showcaseReady = true;

        try {
            if ($this->countPublished() > 0) {
                return;
            }

            foreach ($this->showcaseSeed() as $row) {
                $image = (string)($row['featured_image'] ?? '');
                $file  = defined('PUBLIC_PATH') ? PUBLIC_PATH . '/' . $image : '';
                if ($image !== '' && $file !== '' && !is_file($file)) {
                    continue;
                }
                if ($this->findBy('slug', (string)$row['slug'])) {
                    continue;
                }
                $this->insert($row);
            }
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Project showcase: ' . $e->getMessage());
            }
        }
    }

    /** @return list<array<string, mixed>> */
    private function showcaseSeed(): array
    {
        return [
            [
                'title'             => 'Evening Family Villa',
                'slug'              => 'evening-family-villa',
                'category'          => 'residential',
                'status'            => 'completed',
                'location'          => 'Garowe, Puntland',
                'client_name'       => 'Private client',
                'project_area'      => 'Two-storey gated villa',
                'project_year'      => 2025,
                'short_description' => 'A two-storey grey villa with a gated compound, warm evening lighting, and a finished façade built for family living in Garowe.',
                'description'       => '<p>This Garowe family villa was delivered as a complete residential build — structure, envelope, and finishing under one HighQ Homes team.</p><p>The compound is gated, the façade is finished in a durable grey render, and the two-storey plan is organised for privacy at the street and comfortable living inside. Lighting, boundary walls, and the entrance sequence were treated as part of the architecture, not as afterthoughts.</p><p>The result is a home that reads clearly at night and holds its finish in Puntland’s climate — a standard we apply from first setting-out through handover.</p>',
                'featured_image'    => 'images/builds/grey-villa-evening.jpg',
                'gallery_images'    => json_encode(['images/builds/grey-villa.jpg']),
                'is_featured'       => 1,
                'is_published'      => 1,
                'sort_order'        => 1,
            ],
            [
                'title'             => 'Twin Family Residences',
                'slug'              => 'twin-family-residences',
                'category'          => 'residential',
                'status'            => 'completed',
                'location'          => 'Garowe, Puntland',
                'client_name'       => 'Private client',
                'project_area'      => 'Paired two-storey homes',
                'project_year'      => 2025,
                'short_description' => 'A pair of matching two-storey residences sharing a composed street frontage, with independent living for two households.',
                'description'       => '<p>Twin residences give two families the presence of a single composed building while keeping each home independent.</p><p>HighQ Homes delivered the pair with aligned floor levels, a shared material language, and separate access so the street elevation stays calm and the living remains private. Windows, roof edges, and the boundary treatment were coordinated so neither house reads as an add-on.</p><p>This is a practical model for family plots in Garowe: one construction programme, two complete homes, one accountable finish standard.</p>',
                'featured_image'    => 'images/builds/twin-residences.jpg',
                'gallery_images'    => null,
                'is_featured'       => 1,
                'is_published'      => 1,
                'sort_order'        => 2,
            ],
            [
                'title'             => 'Modern Courtyard Villa',
                'slug'              => 'modern-courtyard-villa',
                'category'          => 'residential',
                'status'            => 'completed',
                'location'          => 'Garowe, Puntland',
                'client_name'       => 'Private client',
                'project_area'      => 'Two-storey villa',
                'project_year'      => 2024,
                'short_description' => 'A contemporary two-storey villa with a strong street elevation, deep openings, and a courtyard plan for shade and privacy.',
                'description'       => '<p>This villa is planned around shade, privacy, and a clear modern elevation — a courtyard sequence that cools the house and keeps family life off the street.</p><p>We delivered the structure, openings, and exterior finish as one package, with careful attention to proportions at the gate, the first-floor balcony line, and the roof edge. Interior rooms follow the courtyard so daylight arrives without exposing the home.</p><p>It is a HighQ Homes residential standard: contemporary form, local climate sense, and a finish you can inspect at handover.</p>',
                'featured_image'    => 'images/builds/modern-villa.jpg',
                'gallery_images'    => null,
                'is_featured'       => 1,
                'is_published'      => 1,
                'sort_order'        => 3,
            ],
            [
                'title'             => 'Stone Compound Residence',
                'slug'              => 'stone-compound-residence',
                'category'          => 'residential',
                'status'            => 'completed',
                'location'          => 'Garowe, Puntland',
                'client_name'       => 'Private client',
                'project_area'      => 'Gated family compound',
                'project_year'      => 2024,
                'short_description' => 'A family residence set behind a stone compound wall, with a composed gate, landscaped approach, and durable exterior finishes.',
                'description'       => '<p>The stone compound is the first room of this house: a gated approach, a planted setback, and a residence that sits calmly behind the wall.</p><p>HighQ Homes built the home and the compound as one project — masonry, openings, and exterior finishes specified to last. The gate and boundary are not temporary site works; they are part of the architecture clients see every day.</p><p>This is typical of our Garowe residential work: a secure compound, a finished elevation, and a family plan that stays private from the street.</p>',
                'featured_image'    => 'images/builds/stone-residence.jpg',
                'gallery_images'    => null,
                'is_featured'       => 0,
                'is_published'      => 1,
                'sort_order'        => 4,
            ],
            [
                'title'             => 'Two-Storey Family Residence',
                'slug'              => 'two-storey-family-residence',
                'category'          => 'residential',
                'status'            => 'completed',
                'location'          => 'Garowe, Puntland',
                'client_name'       => 'Private client',
                'project_area'      => 'Two-storey residence',
                'project_year'      => 2024,
                'short_description' => 'A completed two-storey family home with a warm rendered façade, balanced openings, and a clear street presence.',
                'description'       => '<p>This two-storey residence is a straightforward family house, finished with care: a warm render, balanced window rhythm, and a roof line that sits cleanly on the plot.</p><p>We delivered it from structure to finishing under one programme — no split between “builder” and “finisher”. Interior rooms are planned for daily family use; the exterior is specified for sun, dust, and long-term maintenance.</p><p>It is the kind of home HighQ Homes is known for in Garowe: honest construction, a composed elevation, and a handover you can walk with a checklist.</p>',
                'featured_image'    => 'images/builds/yellow-residence.jpg',
                'gallery_images'    => null,
                'is_featured'       => 0,
                'is_published'      => 1,
                'sort_order'        => 5,
            ],
            [
                'title'             => 'Green Roof Family Home',
                'slug'              => 'green-roof-family-home',
                'category'          => 'residential',
                'status'            => 'completed',
                'location'          => 'Garowe, Puntland',
                'client_name'       => 'Private client',
                'project_area'      => 'Two-storey residence',
                'project_year'      => 2023,
                'short_description' => 'A two-storey family home with a distinctive green roof, deep eaves, and a finished compound that reads clearly from the street.',
                'description'       => '<p>The green roof is the signature of this house — a practical shade device and a strong identity on the street.</p><p>HighQ Homes delivered the residence with coordinated eaves, openings, and exterior colour so the roof belongs to the building rather than sitting on it. The compound, approach, and façade were finished together so the first impression matches the completed interior.</p><p>Clients looking for a family home with a distinct profile — not a generic box — will recognise the standard: design intent carried through construction and finishing.</p>',
                'featured_image'    => 'images/builds/green-roof-residence.jpg',
                'gallery_images'    => null,
                'is_featured'       => 0,
                'is_published'      => 1,
                'sort_order'        => 6,
            ],
            [
                'title'             => 'Gated Villa Entrance',
                'slug'              => 'gated-villa-entrance',
                'category'          => 'residential',
                'status'            => 'completed',
                'location'          => 'Garowe, Puntland',
                'client_name'       => 'Private client',
                'project_area'      => 'Villa compound & gate',
                'project_year'      => 2025,
                'short_description' => 'A custom gate and compound wall for a two-storey villa — the street face of a finished HighQ Homes residence.',
                'description'       => '<p>The entrance is where a residential project meets the city. This gate and compound wall were designed and built with the villa, not added later.</p><p>We coordinated masonry, metalwork, and the house elevation so the street composition is one piece: solid boundary, a considered opening, and a two-storey home that sits correctly behind it. Daylight on the façade shows the same finish quality as the evening view of the related villa.</p><p>HighQ Homes treats compound works as architecture — security, proportion, and craft in a single delivery.</p>',
                'featured_image'    => 'images/builds/grey-villa.jpg',
                'gallery_images'    => json_encode(['images/builds/grey-villa-evening.jpg']),
                'is_featured'       => 0,
                'is_published'      => 1,
                'sort_order'        => 7,
            ],
            [
                'title'             => 'Residential Build in Progress',
                'slug'              => 'residential-build-in-progress',
                'category'          => 'residential',
                'status'            => 'in_progress',
                'location'          => 'Garowe, Puntland',
                'client_name'       => 'Private client',
                'project_area'      => 'Active residential site',
                'project_year'      => 2026,
                'short_description' => 'An active HighQ Homes residential site — structure rising with programmed inspections before each stage is signed off.',
                'description'       => '<p>This active build is how our completed homes begin: a cleared plot, a set-out, and a structure rising under a written programme.</p><p>Clients receive milestone updates and quality checks before the next stage is released. Scaffolding, blockwork, and site access are managed so the finished house can meet the same standard as the villas already in this portfolio.</p><p>If you are planning a home in Garowe, this is the delivery model — visible progress, accountable stages, and a finish that will photograph like the completed projects beside it.</p>',
                'featured_image'    => 'images/builds/active-build.jpg',
                'gallery_images'    => null,
                'is_featured'       => 1,
                'is_published'      => 1,
                'sort_order'        => 8,
            ],
        ];
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
