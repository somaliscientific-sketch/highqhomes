<?php
declare(strict_types=1);

class PageSectionModel extends Model
{
    protected string $table = 'page_sections';

    public function getByPage(string $pageKey): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM page_sections WHERE page_key = ? ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([$pageKey]);
        $rows = $stmt->fetchAll();

        $sections = [];
        foreach ($rows as $row) {
            $row['data'] = $this->decodeData($row['data'] ?? null);
            $sections[$row['section_key']] = $row;
        }
        return $sections;
    }

    public function findSection(string $pageKey, string $sectionKey): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM page_sections WHERE page_key = ? AND section_key = ? LIMIT 1'
        );
        $stmt->execute([$pageKey, $sectionKey]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $row['data'] = $this->decodeData($row['data'] ?? null);
        return $row;
    }

    public function upsert(array $data): void
    {
        $existing = $this->findSection($data['page_key'], $data['section_key']);
        $payload = [
            'page_key'    => $data['page_key'],
            'section_key' => $data['section_key'],
            'title'       => $data['title'] ?? null,
            'subtitle'    => $data['subtitle'] ?? null,
            'content'     => $data['content'] ?? null,
            'data'        => isset($data['data']) ? json_encode($data['data'], JSON_UNESCAPED_UNICODE) : null,
            'image_url'   => $data['image_url'] ?? null,
            'is_enabled'  => (int)($data['is_enabled'] ?? 1),
            'sort_order'  => (int)($data['sort_order'] ?? 0),
        ];

        if ($existing) {
            $this->update((int)$existing['id'], $payload);
            return;
        }
        $this->insert($payload);
    }

    public function pageKeys(): array
    {
        return [
            'home'     => 'Homepage',
            'about'    => 'About Page',
            'services' => 'Services Page',
            'projects' => 'Projects Page',
            'gallery'  => 'Gallery Page',
            'paints'   => 'Paints & Products Page',
            'contact'  => 'Contact Page',
            'header'   => 'Header & Topbar',
            'footer'   => 'Footer & CTA',
        ];
    }

    private function decodeData(mixed $raw): mixed
    {
        if ($raw === null || $raw === '') {
            return null;
        }
        if (is_array($raw)) {
            return $raw;
        }
        $decoded = json_decode((string)$raw, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }
}
