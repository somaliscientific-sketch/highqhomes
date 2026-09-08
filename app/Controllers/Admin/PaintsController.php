<?php
declare(strict_types=1);

class AdminPaintsController extends Controller
{
    private PaintModel $model;

    public function __construct()
    {
        $this->model = new PaintModel();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $paints = $this->model->findAll('sort_order', 'ASC');
        $this->render('admin/paints/index', compact('paints'), 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requireManage();
        $this->render('admin/paints/form', ['paint' => null, 'action' => url('admin/paints/create')], 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $data         = $this->formData();
        $data['slug'] = $this->model->uniqueSlug($data['name']);

        if (!empty($_FILES['featured_image']['name'])) {
            $data['featured_image'] = Upload::image($_FILES['featured_image'], 'paints');
        }

        $id = $this->model->insert($data);
        $this->audit('create', 'paints', "Created paint product \"{$data['name']}\"", 'paint', $id);
        Session::flash('success', 'Paint product created.');
        $this->redirect('/admin/paints');
    }

    public function edit(array $params = []): void
    {
        $this->requireManage();
        $paint = $this->model->findById((int)$params['id']);
        if (!$paint) $this->abort(404);

        $this->render('admin/paints/form', ['paint' => $paint, 'action' => url("admin/paints/{$paint['id']}/edit")], 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $id    = (int)$params['id'];
        $paint = $this->model->findById($id);
        if (!$paint) $this->abort(404);

        $data         = $this->formData();
        $data['slug'] = $this->model->uniqueSlug($data['name'], $id);

        if (!empty($_FILES['featured_image']['name'])) {
            if ($paint['featured_image']) Upload::delete($paint['featured_image']);
            $data['featured_image'] = Upload::image($_FILES['featured_image'], 'paints');
        }

        $this->model->update($id, $data);
        $this->audit('update', 'paints', "Updated paint product \"{$data['name']}\"", 'paint', $id);
        Session::flash('success', 'Paint product updated.');
        $this->redirect('/admin/paints');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $paint = $this->model->findById((int)$params['id']);
        $name = $paint['name'] ?? 'product';
        if ($paint && $paint['featured_image']) Upload::delete($paint['featured_image']);
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'paints', "Deleted paint product \"{$name}\"", 'paint', (int)$params['id']);
        Session::flash('success', 'Paint product deleted.');
        $this->redirect('/admin/paints');
    }

    private function formData(): array
    {
        // Parse features: one per line
        $featuresRaw = explode("\n", $this->post('features_text', ''));
        $features    = array_values(array_filter(array_map('trim', $featuresRaw)));

        // Parse specs: Key: Value per line
        $specsRaw = explode("\n", $this->post('specs_text', ''));
        $specs    = [];
        foreach ($specsRaw as $line) {
            if (str_contains($line, ':')) {
                [$k, $v] = explode(':', $line, 2);
                $k = trim($k);
                $v = trim($v);
                if ($k) $specs[$k] = $v;
            }
        }

        return [
            'name'           => $this->sanitize($this->post('name', '')),
            'brand'          => $this->sanitize($this->post('brand', '')),
            'category'       => $this->sanitize($this->post('category', '')),
            'short_description' => $this->sanitize($this->post('short_description', '')),
            'description'    => $this->post('description', ''),
            'features'       => json_encode($features),
            'specifications' => json_encode($specs),
            'price'          => $this->sanitize($this->post('price', '')),
            'unit'           => $this->sanitize($this->post('unit', '')),
            'sort_order'     => (int)$this->post('sort_order', 0),
            'is_published'   => $this->post('is_published') ? 1 : 0,
        ];
    }
}
