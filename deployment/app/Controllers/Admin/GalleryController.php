<?php
declare(strict_types=1);

class AdminGalleryController extends Controller
{
    private GalleryModel $model;

    public function __construct()
    {
        $this->model = new GalleryModel();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $paged      = $this->model->paginate((int)$this->get('page', 1), 30, 'sort_order', 'ASC');
        $categories = $this->model->getCategories();
        $this->render('admin/gallery/index', array_merge($paged, compact('categories')), 'admin');
    }

    public function upload(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        if (empty($_FILES['images'])) {
            Session::flash('error', 'No images selected.');
            $this->redirect('/admin/gallery');
        }

        $category = $this->sanitize($this->post('category', ''));
        $uploaded = 0;

        foreach ($_FILES['images']['tmp_name'] as $idx => $tmp) {
            if ($_FILES['images']['error'][$idx] !== UPLOAD_ERR_OK) continue;
            $file = [
                'tmp_name' => $tmp,
                'size'     => $_FILES['images']['size'][$idx],
                'error'    => 0,
            ];
            try {
                $url = Upload::image($file, 'gallery');
                $this->model->insert([
                    'title'        => pathinfo($_FILES['images']['name'][$idx], PATHINFO_FILENAME),
                    'image'        => $url,
                    'category'     => $category,
                    'is_published' => 1,
                    'sort_order'   => 0,
                ]);
                $uploaded++;
            } catch (\Throwable $e) {
                // skip invalid files
            }
        }

        Session::flash('success', "{$uploaded} image(s) uploaded successfully.");
        if ($uploaded > 0) {
            $this->audit('upload', 'gallery', "Uploaded {$uploaded} gallery image(s)");
        }
        $this->redirect('/admin/gallery');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $item = $this->model->findById((int)$params['id']);
        $title = $item['title'] ?? 'image';
        if ($item && $item['image']) Upload::delete($item['image']);
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'gallery', "Deleted gallery image \"{$title}\"", 'gallery', (int)$params['id']);
        Session::flash('success', 'Image deleted.');
        $this->redirect('/admin/gallery');
    }

    public function toggle(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $item = $this->model->findById((int)$params['id']);
        if ($item) {
            $this->model->update($item['id'], ['is_published' => $item['is_published'] ? 0 : 1]);
            $this->audit('toggle', 'gallery', "Toggled gallery image \"{$item['title']}\"", 'gallery', (int)$item['id']);
        }
        $this->redirect('/admin/gallery');
    }
}
