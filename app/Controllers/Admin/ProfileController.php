<?php
declare(strict_types=1);

class AdminProfileController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAuth();
        $user = (new UserModel())->findById((int)Auth::id());
        if (!$user) {
            Auth::logout();
            $this->redirect(adminLoginPath());
        }
        $sessionHours = (int)setting('security_session_hours', '2');
        $this->render('admin/profile/index', compact('user', 'sessionHours'), 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireAuth();
        CSRF::check();

        $name  = $this->sanitize($this->post('name', ''));
        $email = strtolower(trim((string)$this->post('email', '')));

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid name and email.');
            $this->redirect('/admin/profile');
        }

        $model = new UserModel();
        $id = (int)Auth::id();
        $existing = $model->findByEmail($email);
        if ($existing && (int)$existing['id'] !== $id) {
            Session::flash('error', 'That email is already in use.');
            $this->redirect('/admin/profile');
        }

        $model->update($id, ['name' => $name, 'email' => $email]);
        $current = Auth::user() ?? [];
        Auth::login([
            'id'    => $id,
            'name'  => $name,
            'email' => $email,
            'role'  => $current['role'] ?? '',
        ]);
        Audit::log('update', 'profile', 'Updated account profile', 'user', $id);
        Session::flash('success', 'Profile updated.');
        $this->redirect('/admin/profile');
    }

    public function updatePassword(array $params = []): void
    {
        $this->requireAuth();
        CSRF::check();

        $current = (string)$this->post('current_password', '');
        $new     = (string)$this->post('new_password', '');
        $confirm = (string)$this->post('new_password_confirmation', '');

        $model = new UserModel();
        $user  = $model->findById((int)Auth::id());
        if (!$user || !password_verify($current, $user['password'])) {
            Session::flash('error', 'Current password is incorrect.');
            $this->redirect('/admin/profile');
        }

        if (strlen($new) < 10) {
            Session::flash('error', 'New password must be at least 10 characters.');
            $this->redirect('/admin/profile');
        }

        if ($new !== $confirm) {
            Session::flash('error', 'New passwords do not match.');
            $this->redirect('/admin/profile');
        }

        $model->update((int)$user['id'], [
            'password' => password_hash($new, PASSWORD_DEFAULT),
        ]);

        Audit::log('password_change', 'profile', 'Changed own password', 'user', (int)$user['id'], $user);
        Session::flash('success', 'Password updated. Please sign in with your new password.');
        Auth::logout();
        $this->redirect(adminLoginPath());
    }
}
