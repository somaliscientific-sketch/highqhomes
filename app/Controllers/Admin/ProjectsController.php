<?php
declare(strict_types=1);

class AdminProjectsController extends Controller
{
    private ProjectModel $model;

    public function __construct()
    {
        $this->model = new ProjectModel();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $paged = $this->model->paginate((int)$this->get('page', 1), 20, 'created_at', 'DESC');
        $this->render('admin/projects/index', $paged, 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requireManage();
        $this->render('admin/projects/form', ['project' => null, 'action' => url('admin/projects/create')], 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $data         = $this->formData();
        $data['slug'] = $this->model->uniqueSlug($data['title']);
        $data['gallery_images'] = '[]';

        if (!empty($_FILES['featured_image']['name'])) {
            $data['featured_image'] = Upload::image($_FILES['featured_image'], 'projects');
        }

        if (!empty($_FILES['gallery']['name'][0])) {
            $urls = [];
            foreach ($_FILES['gallery']['tmp_name'] as $idx => $tmp) {
                if ($_FILES['gallery']['error'][$idx] === UPLOAD_ERR_OK) {
                    $file = [
                        'tmp_name' => $tmp,
                        'size'     => $_FILES['gallery']['size'][$idx],
                        'error'    => $_FILES['gallery']['error'][$idx],
                    ];
                    $urls[] = Upload::image($file, 'projects');
                }
            }
            $data['gallery_images'] = json_encode($urls);
        }

        $id = $this->model->insert($data);
        $this->audit('create', 'projects', "Created project \"{$data['title']}\"", 'project', $id);
        Session::flash('success', 'Project created successfully.');
        $this->redirect('/admin/projects');
    }

    public function edit(array $params = []): void
    {
        $this->requireManage();
        $project = $this->model->findById((int)$params['id']);
        if (!$project) $this->abort(404);

        if (!empty($project['gallery_images']) && is_string($project['gallery_images'])) {
            $project['gallery_images'] = json_decode($project['gallery_images'], true) ?? [];
        }

        $this->render('admin/projects/form', ['project' => $project, 'action' => url("admin/projects/{$project['id']}/edit")], 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $id      = (int)$params['id'];
        $project = $this->model->findById($id);
        if (!$project) $this->abort(404);

        $data         = $this->formData();
        $data['slug'] = $this->model->uniqueSlug($data['title'], $id);

        if (!empty($_FILES['featured_image']['name'])) {
            if ($project['featured_image']) Upload::delete($project['featured_image']);
            $data['featured_image'] = Upload::image($_FILES['featured_image'], 'projects');
        }

        $existing = json_decode($project['gallery_images'] ?? '[]', true) ?? [];

        if (!empty($_FILES['gallery']['name'][0])) {
            foreach ($_FILES['gallery']['tmp_name'] as $idx => $tmp) {
                if ($_FILES['gallery']['error'][$idx] === UPLOAD_ERR_OK) {
                    $file = ['tmp_name' => $tmp, 'size' => $_FILES['gallery']['size'][$idx], 'error' => 0];
                    $existing[] = Upload::image($file, 'projects');
                }
            }
        }
        $data['gallery_images'] = json_encode($existing);

        $this->model->update($id, $data);
        $this->audit('update', 'projects', "Updated project \"{$data['title']}\"", 'project', $id);
        Session::flash('success', 'Project updated successfully.');
        $this->redirect('/admin/projects');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $project = $this->model->findById((int)$params['id']);
        $title = $project['title'] ?? 'project';
        if ($project) {
            if ($project['featured_image']) Upload::delete($project['featured_image']);
            foreach (json_decode($project['gallery_images'] ?? '[]', true) ?? [] as $img) {
                Upload::delete($img);
            }
        }
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'projects', "Deleted project \"{$title}\"", 'project', (int)$params['id']);
        Session::flash('success', 'Project deleted.');
        $this->redirect('/admin/projects');
    }

    public function toggle(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $project = $this->model->findById((int)$params['id']);
        if ($project) {
            $this->model->update($project['id'], ['is_published' => $project['is_published'] ? 0 : 1]);
            $this->audit('toggle', 'projects', "Toggled project \"{$project['title']}\"", 'project', (int)$project['id']);
        }
        $this->redirect('/admin/projects');
    }

    private function formData(): array
    {
        return [
            'title'          => $this->sanitize($this->post('title', '')),
            'category'       => $this->post('category', 'residential'),
            'status'         => $this->post('status', 'completed'),
            'location'       => $this->sanitize($this->post('location', '')),
            'client_name'    => $this->sanitize($this->post('client_name', '')),
            'project_area'   => $this->sanitize($this->post('project_area', '')),
            'project_year'   => (int)$this->post('project_year', date('Y')),
            'short_description' => $this->sanitize($this->post('short_description', '')),
            'description'    => $this->post('description', ''),
            'sort_order'     => (int)$this->post('sort_order', 0),
            'is_featured'    => $this->post('is_featured') ? 1 : 0,
            'is_published'   => $this->post('is_published') ? 1 : 0,
        ];
    }
}
