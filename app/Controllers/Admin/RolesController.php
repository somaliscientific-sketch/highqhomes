<?php
declare(strict_types=1);

class AdminRolesController extends Controller
{
    private RoleModel $model;

    public function __construct()
    {
        $this->model = new RoleModel();
    }

    public function index(array $params = []): void
    {
        if (!Auth::isSuperAdmin()) {
            $this->requirePermission('users.manage');
        } else {
            $this->requireAuth();
        }
        $roles = $this->model->findAll('id', 'ASC');
        $permissionMap = $this->permissionMap();
        $this->render('admin/roles/index', compact('roles', 'permissionMap'), 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireAuth();
        if (!Auth::isSuperAdmin()) {
            Session::flash('error', 'Only Super Admin can edit roles.');
            $this->redirect('/admin/roles');
        }
        CSRF::check();
        $role = $this->model->findById((int)$params['id']);
        if (!$role || $role['name'] === 'super_admin') {
            Session::flash('error', 'This role cannot be modified.');
            $this->redirect('/admin/roles');
        }

        $selected = $this->post('permissions', []);
        $permissions = is_array($selected) ? array_values(array_unique($selected)) : [];

        $this->model->update((int)$role['id'], [
            'label'       => $this->sanitize($this->post('label', (string)$role['label'])),
            'permissions' => json_encode($permissions, JSON_UNESCAPED_UNICODE),
        ]);

        Audit::log('update', 'roles', "Updated permissions for role {$role['name']}", 'role', (int)$role['id']);
        Session::flash('success', 'Role permissions updated.');
        $this->redirect('/admin/roles');
    }

    private function permissionMap(): array
    {
        return [
            'content.view'     => 'View content & dashboard',
            'content.manage'   => 'Create, edit & delete content',
            'media.manage'     => 'Media library upload & manage',
            'messages.view'    => 'View contact messages',
            'messages.manage'  => 'Delete contact messages',
            'settings.manage'  => 'Site settings & branding',
            'menus.manage'     => 'Navigation menus',
            'seo.manage'       => 'SEO settings',
            'users.manage'     => 'User accounts & status',
            'logs.view'        => 'View admin activity logs',
            'security.manage'  => 'Security & session settings',
            'sections.manage'  => 'Page sections (Home, About, etc.)',
        ];
    }
}
