<?php
declare(strict_types=1);

class Audit
{
    public static function log(
        string $action,
        string $module = 'system',
        string $description = '',
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $user = null
    ): void {
        try {
            $actor = $user ?? Auth::user();
            $model = new AdminLogModel();
            $model->insert([
                'user_id'     => $actor['id'] ?? null,
                'user_name'   => $actor['name'] ?? 'System',
                'user_email'  => $actor['email'] ?? null,
                'action'      => substr($action, 0, 40),
                'module'      => substr($module, 0, 60),
                'entity_type' => $entityType ? substr($entityType, 0, 60) : null,
                'entity_id'   => $entityId,
                'description' => $description !== '' ? substr($description, 0, 500) : null,
                'ip_address'  => substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45) ?: null,
                'user_agent'  => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 300) ?: null,
            ]);
        } catch (\Throwable $e) {
            // Never break CMS flows if logging fails
        }
    }
}
