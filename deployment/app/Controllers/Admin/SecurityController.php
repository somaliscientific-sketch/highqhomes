<?php
declare(strict_types=1);

class AdminSecurityController extends Controller
{
    private SettingModel $model;

    public function __construct()
    {
        $this->model = new SettingModel();
    }

    public function index(array $params = []): void
    {
        $this->requirePermission('security.manage');
        $settings = $this->model->getAllAsMap();
        $security = [
            'max_attempts'    => (int)($settings['security_max_attempts'] ?? 5),
            'lockout_minutes' => (int)($settings['security_lockout_minutes'] ?? 15),
            'session_hours'   => (int)($settings['security_session_hours'] ?? 2),
            'retention_days'  => (int)($settings['security_audit_retention_days'] ?? 90),
            'maintenance'     => ($settings['maintenance_mode'] ?? '0') === '1',
        ];
        $this->render('admin/security/index', compact('security'), 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requirePermission('security.manage');
        CSRF::check();

        $map = [
            'security_max_attempts'         => (string)max(3, min(10, (int)$this->post('security_max_attempts', 5))),
            'security_lockout_minutes'      => (string)max(5, min(60, (int)$this->post('security_lockout_minutes', 15))),
            'security_session_hours'        => (string)max(1, min(12, (int)$this->post('security_session_hours', 2))),
            'security_audit_retention_days' => (string)max(7, min(365, (int)$this->post('security_audit_retention_days', 90))),
            'maintenance_mode'              => $this->post('maintenance_mode') ? '1' : '0',
        ];

        $this->model->setBulk($map);

        $retention = (int)$map['security_audit_retention_days'];
        $purged = (new AdminLogModel())->purgeOlderThan($retention);

        Audit::log('update', 'security', 'Security settings updated' . ($purged ? " · purged {$purged} old log(s)" : ''));
        Session::flash('success', 'Security settings saved.');
        $this->redirect('/admin/security');
    }
}
