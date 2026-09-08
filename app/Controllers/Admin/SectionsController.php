<?php
declare(strict_types=1);

class AdminSectionsController extends Controller
{
    private PageSectionModel $model;

    public function __construct()
    {
        $this->model = new PageSectionModel();
    }

    public function index(array $params = []): void
    {
        $this->requirePermission('sections.manage');
        $pageKey = $this->sanitize($this->get('page', 'home'));
        if (!array_key_exists($pageKey, $this->model->pageKeys())) {
            $pageKey = 'home';
        }
        $sections = $this->model->getByPage($pageKey);
        $pages = $this->model->pageKeys();
        $this->render('admin/sections/index', compact('sections', 'pageKey', 'pages'), 'admin');
    }

    public function edit(array $params = []): void
    {
        $this->requirePermission('sections.manage');
        $section = $this->model->findById((int)$params['id']);
        if (!$section) {
            $this->abort(404);
        }
        $this->render('admin/sections/form', compact('section'), 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requirePermission('sections.manage');
        CSRF::check();
        $section = $this->model->findById((int)$params['id']);
        if (!$section) {
            $this->abort(404);
        }

        $dataRaw = trim((string)$this->post('data_json', ''));
        $data = null;
        if ($dataRaw !== '') {
            $decoded = json_decode($dataRaw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Session::flash('error', 'Invalid JSON in section data.');
                $this->redirect('/admin/sections/' . $section['id'] . '/edit');
            }
            $data = $decoded;
        }

        $this->model->update((int)$section['id'], [
            'title'      => $this->sanitize($this->post('title', '')),
            'subtitle'   => $this->sanitize($this->post('subtitle', '')),
            'content'    => $this->post('content', ''),
            'data'       => $data !== null ? json_encode($data, JSON_UNESCAPED_UNICODE) : null,
            'image_url'  => $this->sanitize($this->post('image_url', '')),
            'is_enabled' => $this->post('is_enabled') ? 1 : 0,
            'sort_order' => (int)$this->post('sort_order', 0),
        ]);
        $this->audit('update', 'sections', "Updated section \"{$section['section_key']}\" on {$section['page_key']}", 'section', (int)$section['id']);

        Session::flash('success', 'Section updated.');
        $this->redirect('/admin/sections?page=' . urlencode($section['page_key']));
    }

    public function toggle(array $params = []): void
    {
        $this->requirePermission('sections.manage');
        CSRF::check();
        $section = $this->model->findById((int)$params['id']);
        if ($section) {
            $this->model->update((int)$section['id'], [
                'is_enabled' => $section['is_enabled'] ? 0 : 1,
            ]);
            $this->audit('toggle', 'sections', "Toggled section \"{$section['section_key']}\"", 'section', (int)$section['id']);
        }
        $this->back();
    }
}
