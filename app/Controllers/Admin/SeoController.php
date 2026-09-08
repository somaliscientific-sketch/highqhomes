<?php
declare(strict_types=1);

class AdminSeoController extends Controller
{
    private SeoModel $model;

    public function __construct()
    {
        $this->model = new SeoModel();
    }

    public function index(array $params = []): void
    {
        $this->requirePermission('seo.manage');

        $pages = [
            'home'     => 'Home',
            'about'    => 'About',
            'services' => 'Services',
            'projects' => 'Projects',
            'paints'   => 'Paints',
            'gallery'  => 'Gallery',
            'contact'  => 'Contact',
        ];

        $slug    = $this->get('page', 'home');
        $current = $this->model->findBySlug($slug) ?? ['page_slug' => $slug];
        $settings = (new SettingModel())->getAllAsMap();

        $this->render('admin/seo/index', compact('pages', 'slug', 'current', 'settings'), 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requirePermission('seo.manage');
        CSRF::check();

        $slug = $this->post('page_slug', 'home');
        $existing = $this->model->findBySlug($slug);

        $data = [
            'meta_title'       => $this->sanitize($this->post('meta_title', '')),
            'meta_description' => $this->sanitize($this->post('meta_description', '')),
            'meta_keywords'    => $this->sanitize($this->post('meta_keywords', '')),
            'og_title'         => $this->sanitize($this->post('og_title', '')),
            'og_description'   => $this->sanitize($this->post('og_description', '')),
            'schema_markup'    => $this->post('schema_markup', ''),
        ];

        if (!empty($_FILES['og_image']['name'])) {
            if (!empty($existing['og_image'])) {
                Upload::delete($existing['og_image']);
            }
            $data['og_image'] = Upload::image($_FILES['og_image'], 'seo');
        }

        $this->model->upsert($slug, $data);

        $ga = $this->sanitize($this->post('google_analytics', ''));
        (new SettingModel())->setValue('google_analytics', $ga);

        Audit::log('update', 'seo', "Updated SEO for {$slug} page", 'seo', null);
        Session::flash('success', 'SEO settings saved.');
        $this->redirect('/admin/seo?page=' . $slug);
    }
}
