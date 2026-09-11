<?php
declare(strict_types=1);

class SliderModel extends Model
{
    protected string $table = 'sliders';

    /** @var list<string>|null */
    private static ?array $columns = null;

    private static bool $schemaReady = false;

    public function ensureSchema(): void
    {
        if (self::$schemaReady) {
            return;
        }

        $existing = $this->columnNames();
        $alters = [
            'show_description'  => "ADD COLUMN `show_description` TINYINT(1) NOT NULL DEFAULT 1",
            'image_focus'       => "ADD COLUMN `image_focus` VARCHAR(20) NOT NULL DEFAULT 'center'",
            'content_style'     => "ADD COLUMN `content_style` VARCHAR(20) NOT NULL DEFAULT 'standard'",
            'badge_text'        => "ADD COLUMN `badge_text` VARCHAR(80) DEFAULT NULL",
            'mobile_image'      => "ADD COLUMN `mobile_image` VARCHAR(400) DEFAULT NULL",
            'autoplay_duration' => "ADD COLUMN `autoplay_duration` INT UNSIGNED DEFAULT NULL",
            'transition_type'   => "ADD COLUMN `transition_type` VARCHAR(20) NOT NULL DEFAULT 'inherit'",
            'start_date'        => "ADD COLUMN `start_date` DATETIME DEFAULT NULL",
            'end_date'          => "ADD COLUMN `end_date` DATETIME DEFAULT NULL",
            'updated_at'        => "ADD COLUMN `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP",
        ];

        foreach ($alters as $name => $sql) {
            if (!in_array($name, $existing, true)) {
                try {
                    $this->db->exec("ALTER TABLE `sliders` {$sql}");
                } catch (\Throwable $e) {
                    if (class_exists('Production')) {
                        Production::log('Slider schema ' . $name . ': ' . $e->getMessage());
                    }
                }
            }
        }

        try {
            $stmt = $this->db->prepare(
                'INSERT IGNORE INTO settings (`key`, `value`, `type`, `label`, `group_name`, `sort_order`) VALUES (?,?,?,?,?,?)'
            );
            $stmt->execute(['hero_carousel_transition', 'kenburns', 'text', 'Hero Slide Transition', 'homepage', 18]);
            $stmt->execute(['hero_carousel_autoplay', '1', 'boolean', 'Hero Auto-play Slides', 'homepage', 14]);
            $stmt->execute(['hero_carousel_interval', '6', 'number', 'Hero Slide Interval (seconds)', 'homepage', 15]);
            $stmt->execute(['hero_carousel_dots', '1', 'boolean', 'Hero Show Slide Dots', 'homepage', 16]);
            $stmt->execute(['hero_carousel_pause_hover', '1', 'boolean', 'Hero Pause on Hover', 'homepage', 17]);
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Slider settings: ' . $e->getMessage());
            }
        }

        self::$columns = null;
        self::$schemaReady = true;
    }

    public function ensureShowcase(): void
    {
        static $ready = false;
        if ($ready) {
            return;
        }
        $ready = true;
        $this->ensureSchema();

        try {
            $this->syncShowcaseSlides();
        } catch (\Throwable $e) {
            if (class_exists('Production')) {
                Production::log('Hero slides: ' . $e->getMessage());
            }
        }
    }

    private function syncShowcaseSlides(): void
    {
        $catalog = heroSliderCatalog();
        $rows = $this->db->query('SELECT * FROM `sliders` ORDER BY `sort_order` ASC, `id` ASC')->fetchAll();
        $version = 'site-video-8';
        $current = '';
        try {
            $stmt = $this->db->prepare('SELECT `value` FROM `settings` WHERE `key` = ?');
            $stmt->execute(['hero_showcase_version']);
            $current = (string)$stmt->fetchColumn();
        } catch (\Throwable $e) {
            $current = '';
        }

        if ($rows === [] || $current !== $version) {
            foreach ($catalog as $i => $slide) {
                $slide['sort_order'] = $i;
                $slide['is_published'] = 1;
                if (isset($rows[$i])) {
                    $this->update((int)$rows[$i]['id'], $this->onlyColumns($slide));
                } else {
                    $this->insert($this->onlyColumns($slide));
                }
            }
            for ($i = count($catalog); $i < count($rows); $i++) {
                $this->update((int)$rows[$i]['id'], $this->onlyColumns([
                    'is_published' => 0,
                    'sort_order' => $i,
                ]));
            }
            try {
                $stmt = $this->db->prepare(
                    'INSERT INTO `settings` (`key`, `value`, `type`, `label`, `group_name`, `sort_order`) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
                );
                $stmt->execute(['hero_showcase_version', $version, 'text', 'Hero Showcase Version', 'homepage', 19]);
            } catch (\Throwable $e) {
                if (class_exists('Production')) {
                    Production::log('Hero showcase version: ' . $e->getMessage());
                }
            }
            return;
        }

        foreach ($rows as $i => $row) {
            if (isStaleHeroSlideImage((string)($row['image'] ?? ''))) {
                $fallback = $catalog[$i % count($catalog)]['image'];
                $this->update((int)$row['id'], $this->onlyColumns(['image' => $fallback]));
            }
        }
    }

    public function hasColumn(string $name): bool
    {
        return in_array($name, $this->columnNames(), true);
    }

    /** @return list<string> */
    public function columnNames(): array
    {
        if (self::$columns === null) {
            self::$columns = $this->db->query('SHOW COLUMNS FROM `sliders`')->fetchAll(PDO::FETCH_COLUMN);
        }
        return self::$columns;
    }

    /** @param array<string, mixed> $data */
    public function onlyColumns(array $data): array
    {
        $allowed = array_flip($this->columnNames());
        return array_intersect_key($data, $allowed);
    }

    public function getPublished(): array
    {
        $this->ensureSchema();
        $sql = 'SELECT * FROM `sliders` WHERE `is_published` = 1';
        if ($this->hasColumn('start_date')) {
            $sql .= ' AND (`start_date` IS NULL OR `start_date` <= NOW())';
        }
        if ($this->hasColumn('end_date')) {
            $sql .= ' AND (`end_date` IS NULL OR `end_date` >= NOW())';
        }
        $sql .= ' ORDER BY `sort_order` ASC, `id` ASC';
        return $this->db->query($sql)->fetchAll();
    }

    public function nextSortOrder(): int
    {
        return (int)$this->db->query('SELECT COALESCE(MAX(`sort_order`), -1) + 1 FROM `sliders`')->fetchColumn();
    }

    /** @param list<int> $ids */
    public function reorder(array $ids): void
    {
        $stmt = $this->db->prepare('UPDATE `sliders` SET `sort_order` = ? WHERE `id` = ?');
        foreach (array_values($ids) as $index => $id) {
            $stmt->execute([$index, (int)$id]);
        }
    }

    public function countUsingImage(string $path, ?int $exceptId = null): int
    {
        if ($path === '') {
            return 0;
        }
        if ($this->hasColumn('mobile_image')) {
            $sql = 'SELECT COUNT(*) FROM `sliders` WHERE (`image` = ? OR `mobile_image` = ?)';
            $params = [$path, $path];
        } else {
            $sql = 'SELECT COUNT(*) FROM `sliders` WHERE `image` = ?';
            $params = [$path];
        }
        if ($exceptId !== null) {
            $sql .= ' AND `id` <> ?';
            $params[] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
