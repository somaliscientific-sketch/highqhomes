<?php
declare(strict_types=1);

class SliderModel extends Model
{
    protected string $table = 'sliders';

    public function getPublished(): array
    {
        return $this->db->query(
            "SELECT * FROM sliders WHERE is_published = 1 ORDER BY sort_order ASC"
        )->fetchAll();
    }
}
