<?php
declare(strict_types=1);

class AdminUsersController extends Controller
{
    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index(array $params = []): void
    {
        $this->requirePermission('users.manage');
        $users = $this->model->findAll('id', 'DESC');
        $roles = (new RoleModel())->findAll('id', 'ASC');
        $roleLabels = [];
        foreach ($roles as $role) {
            $roleLabels[$role['name']] = $role['label'];
        }
        $this->render('admin/users/index', compact('users', 'roles', 'roleLabels'), 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requirePermission('users.manage');
        CSRF::check();
        $password = (string)$this->post('password', '');
        if (!$this->strongPassword($password)) {
            Session::flash('error', 'Password must be at least 12 characters and include upper case, lower case, and a number.');
            $this->redirect('/admin/users');
        }
        $email = strtolower(trim((string)$this->post('email', '')));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Invalid email address.');
            $this->redirect('/admin/users');
        }
        if ($this->model->findByEmail($email)) {
            Session::flash('error', 'Email already registered.');
            $this->redirect('/admin/users');
        }
        $role = $this->validRole((string)$this->post('role', 'editor'));
        if ($role === 'super_admin' && !Auth::isSuperAdmin()) {
            Session::flash('error', 'Only a Super Admin can create another Super Admin.');
            $this->redirect('/admin/users');
        }
        $name = $this->sanitize($this->post('name', ''));
        if ($name === '') {
            Session::flash('error', 'Name is required.');
            $this->redirect('/admin/users');
        }
        $id = $this->model->insert([
            'name'      => $name,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'role'      => $role,
            'is_active' => $this->post('is_active') ? 1 : 0,
        ]);
        Audit::log('create', 'users', "Created user {$name} ({$email})", 'user', is_int($id) ? $id : null);
        Session::flash('success', 'User created.');
        $this->redirect('/admin/users');
    }

    public function update(array $params = []): void
    {
        $this->requirePermission('users.manage');
        CSRF::check();
        $id = (int)$params['id'];
        $target = $this->model->findById($id);
        if (!$target) {
            Session::flash('error', 'User account not found.');
            $this->redirect('/admin/users');
        }
        if (($target['role'] ?? '') === 'super_admin' && !Auth::isSuperAdmin()) {
            Session::flash('error', 'Only a Super Admin can modify a Super Admin account.');
            $this->redirect('/admin/users');
        }
        $email = strtolower(trim((string)$this->post('email', '')));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Invalid email address.');
            $this->redirect('/admin/users');
        }
        $existing = $this->model->findByEmail($email);
        if ($existing && (int)$existing['id'] !== $id) {
            Session::flash('error', 'Email already in use.');
            $this->redirect('/admin/users');
        }
        $role = $this->validRole((string)$this->post('role', 'editor'));
        if ($role === 'super_admin' && !Auth::isSuperAdmin()) {
            Session::flash('error', 'Only a Super Admin can assign the Super Admin role.');
            $this->redirect('/admin/users');
        }
        $name = $this->sanitize($this->post('name', ''));
        if ($name === '') {
            Session::flash('error', 'Name is required.');
            $this->redirect('/admin/users');
        }
        $data = [
            'name'      => $name,
            'email'     => $email,
            'role'      => $role,
            'is_active' => $this->post('is_active') ? 1 : 0,
        ];
        if ((string)$this->post('password', '') !== '') {
            if (!$this->strongPassword((string)$this->post('password'))) {
                Session::flash('error', 'Password must be at least 12 characters and include upper case, lower case, and a number.');
                $this->redirect('/admin/users');
            }
            $data['password'] = password_hash((string)$this->post('password'), PASSWORD_DEFAULT);
            Audit::log('password_reset', 'users', "Admin reset password for user #{$id}", 'user', $id);
        }
        $this->model->update($id, $data);
        Audit::log('update', 'users', "Updated user #{$id}", 'user', $id);
        Session::flash('success', 'User updated.');
        $this->redirect('/admin/users');
    }

    public function toggle(array $params = []): void
    {
        $this->requirePermission('users.manage');
        CSRF::check();
        $id = (int)$params['id'];
        if ($id === Auth::id()) {
            Session::flash('error', 'You cannot disable your own account.');
            $this->redirect('/admin/users');
        }
        $user = $this->model->findById($id);
        if (!$user) {
            $this->redirect('/admin/users');
        }
        if (($user['role'] ?? '') === 'super_admin' && !Auth::isSuperAdmin()) {
            Session::flash('error', 'Only a Super Admin can change a Super Admin account.');
            $this->redirect('/admin/users');
        }
        $active = $user['is_active'] ? 0 : 1;
        $this->model->update($id, ['is_active' => $active]);
        Audit::log('toggle', 'users', ($active ? 'Activated' : 'Disabled') . " user {$user['email']}", 'user', $id);
        Session::flash('success', $active ? 'User activated.' : 'User disabled.');
        $this->redirect('/admin/users');
    }

    public function destroy(array $params = []): void
    {
        $this->requirePermission('users.manage');
        CSRF::check();
        if ((int)$params['id'] === Auth::id()) {
            Session::flash('error', 'You cannot delete your own account.');
            $this->redirect('/admin/users');
        }
        $user = $this->model->findById((int)$params['id']);
        if (!$user) {
            Session::flash('error', 'User account not found.');
            $this->redirect('/admin/users');
        }
        if (($user['role'] ?? '') === 'super_admin' && !Auth::isSuperAdmin()) {
            Session::flash('error', 'Only a Super Admin can delete a Super Admin account.');
            $this->redirect('/admin/users');
        }
        $this->model->delete((int)$params['id']);
        if ($user) {
            Audit::log('delete', 'users', "Deleted user {$user['email']}", 'user', (int)$params['id']);
        }
        Session::flash('success', 'User deleted.');
        $this->redirect('/admin/users');
    }

    private function validRole(string $role): string
    {
        $roles = array_column((new RoleModel())->findAll('id', 'ASC'), 'name');
        return in_array($role, $roles, true) ? $role : 'editor';
    }

    private function strongPassword(string $password): bool
    {
        return strlen($password) >= 12
            && preg_match('/[a-z]/', $password) === 1
            && preg_match('/[A-Z]/', $password) === 1
            && preg_match('/[0-9]/', $password) === 1;
    }
}
