<?php
declare(strict_types=1);

class SeoModel extends Model
{
    protected string $table = 'seo';

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('page_slug', $slug);
    }

    public function upsert(string $slug, array $data): void
    {
        $existing = $this->findBySlug($slug);
        if ($existing) {
            $this->update($existing['id'], $data);
        } else {
            $data['page_slug'] = $slug;
            $this->insert($data);
        }
    }
}
