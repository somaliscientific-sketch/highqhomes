<?php
declare(strict_types=1);

class SettingModel extends Model
{
    protected string $table = 'settings';
    protected string $primaryKey = 'key';

    public function getAllAsMap(): array
    {
        $rows = $this->db->query("SELECT `key`, `value` FROM settings ORDER BY sort_order")->fetchAll();
        $map  = [];
        foreach ($rows as $row) {
            $map[$row['key']] = $row['value'];
        }
        return $map;
    }

    public function getByGroup(string $group): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM settings WHERE group_name = ? ORDER BY sort_order"
        );
        $stmt->execute([$group]);
        return $stmt->fetchAll();
    }

    public function getGroups(): array
    {
        $rows = $this->db->query(
            "SELECT group_name FROM settings GROUP BY group_name ORDER BY MIN(sort_order)"
        )->fetchAll();
        return array_column($rows, 'group_name');
    }

    /** Groups managed on dedicated admin pages (identity, security, SEO). */
    public function getManageableGroups(): array
    {
        $hidden = ['identity', 'security', 'seo'];
        return array_values(array_filter(
            $this->getGroups(),
            static fn(string $group): bool => !in_array($group, $hidden, true)
        ));
    }

    public function getValue(string $key, string $default = ''): string
    {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE `key` = ?");
        $stmt->execute([$key]);
        return $stmt->fetchColumn() ?: $default;
    }

    public function setValue(string $key, string $value): void
    {
        $this->db->prepare(
            "UPDATE settings SET value = ? WHERE `key` = ?"
        )->execute([$value, $key]);
    }

    public function setBulk(array $map): void
    {
        $stmt = $this->db->prepare("UPDATE settings SET value = ? WHERE `key` = ?");
        foreach ($map as $key => $value) {
            $stmt->execute([$value, $key]);
        }
    }
}
