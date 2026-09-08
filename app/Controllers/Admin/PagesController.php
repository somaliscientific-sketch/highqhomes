<?php
declare(strict_types=1);

class AdminPagesController extends Controller
{
    private PageModel $model;

    public function __construct()
    {
        $this->model = new PageModel();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $q = $this->sanitize($this->get('q', ''));
        $paged = $this->model->search($q, (int)$this->get('page', 1), 15);
        $this->render('admin/pages/index', array_merge($paged, compact('q')), 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requireManage();
        $this->render('admin/pages/form', ['page' => null, 'action' => url('admin/pages/create')], 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $data = $this->formData();
        $data['slug'] = $this->model->uniqueSlug($data['slug'] ?: $data['title']);
        if (!empty($_FILES['featured_image']['name'])) {
            $data['featured_image'] = Upload::image($_FILES['featured_image'], 'pages');
        }

        $id = $this->model->insert($data);
        $this->audit('create', 'pages', "Created page \"{$data['title']}\"", 'page', $id);
        Session::flash('success', 'Page created successfully.');
        $this->redirect('/admin/pages');
    }

    public function edit(array $params = []): void
    {
        $this->requireManage();
        $page = $this->model->findById((int)$params['id']);
        if (!$page) {
            $this->abort(404);
        }
        $this->render('admin/pages/form', ['page' => $page, 'action' => url("admin/pages/{$page['id']}/edit")], 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $id = (int)$params['id'];
        $page = $this->model->findById($id);
        if (!$page) {
            $this->abort(404);
        }

        $data = $this->formData();
        $data['slug'] = $this->model->uniqueSlug($data['slug'] ?: $data['title'], $id);
        if (!empty($_FILES['featured_image']['name'])) {
            if (!empty($page['featured_image'])) {
                Upload::delete($page['featured_image']);
            }
            $data['featured_image'] = Upload::image($_FILES['featured_image'], 'pages');
        }

        $this->model->update($id, $data);
        $this->audit('update', 'pages', "Updated page \"{$data['title']}\"", 'page', $id);
        Session::flash('success', 'Page updated successfully.');
        $this->redirect('/admin/pages');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $page = $this->model->findById((int)$params['id']);
        $title = $page['title'] ?? 'page';
        if ($page && !empty($page['featured_image'])) {
            Upload::delete($page['featured_image']);
        }
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'pages', "Deleted page \"{$title}\"", 'page', (int)$params['id']);
        Session::flash('success', 'Page deleted.');
        $this->redirect('/admin/pages');
    }

    public function toggle(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $page = $this->model->findById((int)$params['id']);
        if ($page) {
            $this->model->update((int)$page['id'], ['is_published' => $page['is_published'] ? 0 : 1]);
            $this->audit('toggle', 'pages', "Toggled page \"{$page['title']}\"", 'page', (int)$page['id']);
        }
        $this->redirect('/admin/pages');
    }

    private function formData(): array
    {
        $published = $this->post('is_published') ? 1 : 0;
        return [
            'title' => $this->sanitize($this->post('title', '')),
            'slug' => Model::slugify($this->sanitize($this->post('slug', ''))),
            'excerpt' => $this->sanitize($this->post('excerpt', '')),
            'content' => $this->post('content', ''),
            'template' => $this->sanitize($this->post('template', 'default')),
            'meta_title' => $this->sanitize($this->post('meta_title', '')),
            'meta_description' => $this->sanitize($this->post('meta_description', '')),
            'sort_order' => (int)$this->post('sort_order', 0),
            'is_published' => $published,
            'published_at' => $published ? date('Y-m-d H:i:s') : null,
        ];
    }
}
