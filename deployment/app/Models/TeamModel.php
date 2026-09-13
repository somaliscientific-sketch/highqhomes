<?php
declare(strict_types=1);

class TeamModel extends Model
{
    protected string $table = 'team';

    public function getPublished(): array
    {
        return $this->db->query(
            "SELECT * FROM team WHERE is_published = 1 ORDER BY sort_order ASC"
        )->fetchAll();
    }
}
