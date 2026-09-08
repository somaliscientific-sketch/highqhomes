<?php
declare(strict_types=1);

class AdminSettingsController extends Controller
{
    private SettingModel $model;

    public function __construct()
    {
        $this->model = new SettingModel();
    }

    public function index(array $params = []): void
    {
        $this->requireAdmin();

        $groups = $this->model->getManageableGroups();
        $group  = $this->get('group', 'general');
        if (!in_array($group, $groups, true)) {
            $group = $groups[0] ?? 'general';
        }
        $fields = $this->model->getByGroup($group);

        $this->render('admin/settings/index', compact('groups', 'group', 'fields'), 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireAdmin();
        CSRF::check();

        $groups = $this->model->getManageableGroups();
        $group  = $this->post('group', 'general');
        if (!in_array($group, $groups, true)) {
            Session::flash('error', 'Invalid settings group.');
            $this->redirect('/admin/settings');
        }
        $fields = $this->model->getByGroup($group);
        $map    = [];

        foreach ($fields as $field) {
            $key = $field['key'];

            if ($field['type'] === 'image' && !empty($_FILES[$key]['name'])) {
                // Delete old image
                $old = $this->model->getValue($key);
                if ($old) Upload::delete($old);
                $map[$key] = Upload::image($_FILES[$key], 'logo');
            } elseif ($field['type'] === 'boolean') {
                $map[$key] = isset($_POST[$key]) ? '1' : '0';
            } else {
                $map[$key] = $this->sanitize($this->post($key, ''));
            }
        }

        $this->model->setBulk($map);
        Audit::log('update', 'settings', "Updated {$group} settings");
        Session::flash('success', 'Settings saved successfully.');
        $this->redirect('/admin/settings?group=' . $group);
    }
}
