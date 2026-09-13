<?php
declare(strict_types=1);

class AdminTestimonialsController extends Controller
{
    private TestimonialModel $model;

    public function __construct()
    {
        $this->model = new TestimonialModel();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $testimonials = $this->model->findAll('created_at', 'DESC');
        $this->render('admin/testimonials/index', compact('testimonials'), 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requireManage();
        $this->render('admin/testimonials/form', ['testimonial' => null, 'action' => url('admin/testimonials/create')], 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $data = $this->formData();

        if (!empty($_FILES['image']['name'])) {
            $data['image'] = Upload::image($_FILES['image'], 'team');
        }

        $id = $this->model->insert($data);
        $this->audit('create', 'testimonials', "Added testimonial from \"{$data['client_name']}\"", 'testimonial', $id);
        Session::flash('success', 'Testimonial added.');
        $this->redirect('/admin/testimonials');
    }

    public function edit(array $params = []): void
    {
        $this->requireManage();
        $testimonial = $this->model->findById((int)$params['id']);
        if (!$testimonial) $this->abort(404);

        $this->render('admin/testimonials/form', ['testimonial' => $testimonial, 'action' => url("admin/testimonials/{$testimonial['id']}/edit")], 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $id          = (int)$params['id'];
        $testimonial = $this->model->findById($id);
        if (!$testimonial) $this->abort(404);

        $data = $this->formData();

        if (!empty($_FILES['image']['name'])) {
            if ($testimonial['image']) Upload::delete($testimonial['image']);
            $data['image'] = Upload::image($_FILES['image'], 'team');
        }

        $this->model->update($id, $data);
        $this->audit('update', 'testimonials', "Updated testimonial from \"{$data['client_name']}\"", 'testimonial', $id);
        Session::flash('success', 'Testimonial updated.');
        $this->redirect('/admin/testimonials');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'testimonials', 'Deleted testimonial', 'testimonial', (int)$params['id']);
        Session::flash('success', 'Testimonial deleted.');
        $this->redirect('/admin/testimonials');
    }

    private function formData(): array
    {
        return [
            'client_name' => $this->sanitize($this->post('client_name', '')),
            'position'    => $this->sanitize($this->post('position', '')),
            'company'     => $this->sanitize($this->post('company', '')),
            'content'     => $this->sanitize($this->post('content', '')),
            'rating'      => min(5, max(1, (int)$this->post('rating', 5))),
            'is_featured' => $this->post('is_featured') ? 1 : 0,
            'is_published'=> $this->post('is_published') ? 1 : 0,
        ];
    }
}
