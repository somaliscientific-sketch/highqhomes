<?php
declare(strict_types=1);

class AdminIdentityController extends Controller
{
    private const IMAGE_KEYS = ['logo', 'footer_logo', 'favicon'];

    private SettingModel $model;

    public function __construct()
    {
        $this->model = new SettingModel();
    }

    public function index(array $params = []): void
    {
        $this->requireAdmin();
        $identity = $this->identityValues();
        $this->render('admin/identity/index', compact('identity'), 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireAdmin();
        CSRF::check();

        $map = [
            'site_name'     => $this->sanitize($this->post('site_name', '')),
            'tagline'       => $this->sanitize($this->post('tagline', '')),
            'legal_name'    => $this->sanitize($this->post('legal_name', '')),
            'primary_color' => $this->sanitizeColor($this->post('primary_color', '#021D45'), '#021D45'),
            'accent_color'  => $this->sanitizeColor($this->post('accent_color', '#E88B09'), '#E88B09'),
        ];

        foreach (self::IMAGE_KEYS as $key) {
            if (!empty($_FILES[$key]['name'])) {
                $old = $this->model->getValue($key);
                if ($old) {
                    Upload::delete($old);
                }
                $folder = $key === 'favicon' ? 'favicon' : 'logo';
                $map[$key] = Upload::image($_FILES[$key], $folder);
            }
        }

        $this->model->setBulk($map);
        Audit::log('update', 'identity', 'Updated site identity');
        Session::flash('success', 'Site identity saved.');
        $this->redirect('/admin/identity');
    }

    /** @return array<string, string> */
    private function identityValues(): array
    {
        $all = $this->model->getAllAsMap();
        $keys = ['site_name', 'tagline', 'legal_name', 'logo', 'footer_logo', 'favicon', 'primary_color', 'accent_color'];
        $out = [];
        foreach ($keys as $key) {
            $out[$key] = $all[$key] ?? '';
        }
        if ($out['primary_color'] === '') {
            $out['primary_color'] = '#021D45';
        }
        if ($out['accent_color'] === '') {
            $out['accent_color'] = '#E88B09';
        }
        return $out;
    }

    private function sanitizeColor(string $value, string $default): string
    {
        $value = trim($value);
        return preg_match('/^#[0-9A-Fa-f]{6}$/', $value) ? strtoupper($value) : $default;
    }
}
