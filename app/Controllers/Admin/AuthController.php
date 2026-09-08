<?php
declare(strict_types=1);

class AdminAuthController extends Controller
{
    public function login(array $params = []): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user) {
                Audit::log('logout', 'auth', 'Signed out to access login page', 'user', (int)$user['id'], $user);
            }
            Auth::logout();
            Session::flash('success', 'You have been signed out. Please sign in again.');
        }

        $this->render('admin/login', [], 'auth');
    }

    /** Legacy /admin/login — blocked (404, no redirect to avoid leaking the secure URL). */
    public function blocked(array $params = []): void
    {
        usleep(random_int(100000, 300000));
        $this->abort(404);
    }

    public function authenticate(array $params = []): void
    {
        if (!Security::isAdminLoginRequest()) {
            $this->abort(404);
        }

        CSRF::check();

        if ($this->post('website', '') !== '') {
            http_response_code(403);
            Session::flash('error', 'Unable to sign in.');
            $this->redirect(adminLoginPath());
        }

        $email    = strtolower(trim($this->post('email', '')));
        $password = (string)$this->post('password', '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
            Session::flash('error', 'Invalid email or password.');
            $this->redirect(adminLoginPath());
        }

        if ($password === '' || strlen($password) > 128) {
            Session::flash('error', 'Invalid email or password.');
            $this->redirect(adminLoginPath());
        }

        if (!Security::loginAllowed($email)) {
            $mins = max(1, (int)ceil(Security::loginLockRemaining($email) / 60));
            Session::flash('error', "Too many failed attempts. Try again in {$mins} minute(s).");
            $this->redirect(adminLoginPath());
        }

        $model = new UserModel();
        $user  = $model->findByEmail($email);

        if (!$user || !$user['is_active'] || !password_verify($password, $user['password'])) {
            Security::recordFailedLogin($email);
            Audit::log('login_failed', 'auth', "Failed login attempt for {$email}");
            usleep(random_int(200000, 600000));
            Session::flash('error', 'Invalid email or password.');
            $this->redirect(adminLoginPath());
        }

        Security::clearLoginAttempts($email);
        if (password_needs_rehash((string)$user['password'], PASSWORD_DEFAULT)) {
            $model->update((int)$user['id'], [
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        }
        $model->updateLastLogin((int)$user['id']);

        if (Auth::check()) {
            Auth::logout();
        }

        Auth::login($user);
        Audit::log('login', 'auth', 'Signed in successfully', 'user', (int)$user['id'], $user);

        $this->redirect('/admin/dashboard');
    }

    public function logout(array $params = []): void
    {
        CSRF::check();
        $user = Auth::user();
        if ($user) {
            Audit::log('logout', 'auth', 'Signed out', 'user', (int)$user['id'], $user);
        }
        Session::flash('success', 'You have been signed out securely.');
        Auth::logout();
        $this->redirect(adminLoginPath());
    }
}
