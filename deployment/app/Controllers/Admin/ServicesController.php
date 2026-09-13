<?php
declare(strict_types=1);

class AdminServicesController extends Controller
{
    private ServiceModel $model;

    public function __construct()
    {
        $this->model = new ServiceModel();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $services = $this->model->findAll('sort_order', 'ASC');
        $this->render('admin/services/index', compact('services'), 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requireManage();
        $this->render('admin/services/form', ['service' => null, 'action' => url('admin/services/create')], 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $data         = $this->formData();
        $data['slug'] = $this->model->uniqueSlug($data['title']);

        if (!empty($_FILES['image']['name'])) {
            $data['image'] = Upload::image($_FILES['image'], 'services');
        }

        $id = $this->model->insert($data);
        $this->audit('create', 'services', "Created service \"{$data['title']}\"", 'service', $id);
        Session::flash('success', 'Service created successfully.');
        $this->redirect('/admin/services');
    }

    public function edit(array $params = []): void
    {
        $this->requireManage();
        $service = $this->model->findById((int)$params['id']);
        if (!$service) $this->abort(404);

        $this->render('admin/services/form', ['service' => $service, 'action' => url("admin/services/{$service['id']}/edit")], 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $id      = (int)$params['id'];
        $service = $this->model->findById($id);
        if (!$service) $this->abort(404);

        $data         = $this->formData();
        $data['slug'] = $this->model->uniqueSlug($data['title'], $id);

        if (!empty($_FILES['image']['name'])) {
            if ($service['image']) Upload::delete($service['image']);
            $data['image'] = Upload::image($_FILES['image'], 'services');
        }

        $this->model->update($id, $data);
        $this->audit('update', 'services', "Updated service \"{$data['title']}\"", 'service', $id);
        Session::flash('success', 'Service updated successfully.');
        $this->redirect('/admin/services');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $service = $this->model->findById((int)$params['id']);
        $title = $service['title'] ?? 'service';
        if ($service && $service['image']) Upload::delete($service['image']);
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'services', "Deleted service \"{$title}\"", 'service', (int)$params['id']);
        Session::flash('success', 'Service deleted.');
        $this->redirect('/admin/services');
    }

    public function toggle(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $service = $this->model->findById((int)$params['id']);
        if ($service) {
            $this->model->update($service['id'], ['is_published' => $service['is_published'] ? 0 : 1]);
            $this->audit('toggle', 'services', "Toggled service \"{$service['title']}\"", 'service', (int)$service['id']);
        }
        $this->redirect('/admin/services');
    }

    private function formData(): array
    {
        return [
            'title'             => $this->sanitize($this->post('title', '')),
            'short_description' => $this->sanitize($this->post('short_description', '')),
            'description'       => $this->post('description', ''),
            'icon'              => $this->sanitize($this->post('icon', 'bi-building')),
            'sort_order'        => (int)$this->post('sort_order', 0),
            'is_featured'       => $this->post('is_featured') ? 1 : 0,
            'is_published'      => $this->post('is_published') ? 1 : 0,
        ];
    }
}
