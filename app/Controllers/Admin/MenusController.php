<?php
declare(strict_types=1);

class AdminMenusController extends Controller
{
    private MenuModel $model;

    public function __construct()
    {
        $this->model = new MenuModel();
    }

    public function index(array $params = []): void
    {
        $this->requirePermission('menus.manage');
        $menus = $this->model->findAll('sort_order', 'ASC');
        $this->render('admin/menus/index', compact('menus'), 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requirePermission('menus.manage');
        $this->render('admin/menus/form', ['menu' => null, 'action' => url('admin/menus/create')], 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requirePermission('menus.manage');
        CSRF::check();
        $this->model->insert($this->formData());
        $label = $this->sanitize($this->post('label', ''));
        $this->audit('create', 'menus', "Created menu item \"{$label}\"");
        Session::flash('success', 'Menu item created.');
        $this->redirect('/admin/menus');
    }

    public function edit(array $params = []): void
    {
        $this->requirePermission('menus.manage');
        $menu = $this->model->findById((int)$params['id']);
        if (!$menu) {
            $this->abort(404);
        }
        $this->render('admin/menus/form', ['menu' => $menu, 'action' => url("admin/menus/{$menu['id']}/edit")], 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requirePermission('menus.manage');
        CSRF::check();
        $this->model->update((int)$params['id'], $this->formData());
        $label = $this->sanitize($this->post('label', ''));
        $this->audit('update', 'menus', "Updated menu item \"{$label}\"", 'menu', (int)$params['id']);
        Session::flash('success', 'Menu item updated.');
        $this->redirect('/admin/menus');
    }

    public function destroy(array $params = []): void
    {
        $this->requirePermission('menus.manage');
        CSRF::check();
        $menu = $this->model->findById((int)$params['id']);
        $label = $menu['label'] ?? 'item';
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'menus', "Deleted menu item \"{$label}\"", 'menu', (int)$params['id']);
        Session::flash('success', 'Menu item deleted.');
        $this->redirect('/admin/menus');
    }

    public function toggle(array $params = []): void
    {
        $this->requirePermission('menus.manage');
        CSRF::check();
        $menu = $this->model->findById((int)$params['id']);
        if ($menu) {
            $this->model->update((int)$menu['id'], ['is_published' => $menu['is_published'] ? 0 : 1]);
            $this->audit('toggle', 'menus', "Toggled menu item \"{$menu['label']}\"", 'menu', (int)$menu['id']);
        }
        $this->redirect('/admin/menus');
    }

    private function formData(): array
    {
        return [
            'label' => $this->sanitize($this->post('label', '')),
            'url' => $this->sanitize($this->post('url', '/')),
            'location' => in_array($this->post('location'), ['primary', 'footer'], true) ? $this->post('location') : 'primary',
            'target' => $this->post('target') === '_blank' ? '_blank' : '_self',
            'sort_order' => (int)$this->post('sort_order', 0),
            'is_published' => $this->post('is_published') ? 1 : 0,
        ];
    }
}
