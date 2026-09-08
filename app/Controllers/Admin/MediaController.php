<?php
declare(strict_types=1);

class AdminMediaController extends Controller
{
    private MediaModel $model;

    public function __construct()
    {
        $this->model = new MediaModel();
    }

    public function index(array $params = []): void
    {
        $this->requirePermission('media.manage');
        $q = $this->sanitize($this->get('q', ''));
        $folder = $this->sanitize($this->get('folder', ''));
        $type = $this->sanitize($this->get('type', ''));
        $paged = $this->model->search($q, (int)$this->get('page', 1), 24, $folder ?: null, $type ?: null);
        $folders = $this->model->folders();
        $this->render('admin/media/index', array_merge($paged, compact('q', 'folder', 'type', 'folders')), 'admin');
    }

    public function upload(array $params = []): void
    {
        $this->requirePermission('media.manage');
        CSRF::check();
        if (empty($_FILES['files'])) {
            Session::flash('error', 'Select at least one file.');
            $this->redirect('/admin/media');
        }

        $folder = Model::slugify($this->sanitize($this->post('folder', 'media'))) ?: 'media';
        $uploaded = 0;
        $files = $_FILES['files'];
        $count = is_array($files['tmp_name']) ? count($files['tmp_name']) : 1;

        for ($idx = 0; $idx < $count; $idx++) {
            $tmp = is_array($files['tmp_name']) ? $files['tmp_name'][$idx] : $files['tmp_name'];
            $error = is_array($files['error']) ? $files['error'][$idx] : $files['error'];
            if ($error !== UPLOAD_ERR_OK) {
                continue;
            }
            $file = [
                'tmp_name' => $tmp,
                'name'     => is_array($files['name']) ? $files['name'][$idx] : $files['name'],
                'size'     => is_array($files['size']) ? $files['size'][$idx] : $files['size'],
                'error'    => 0,
            ];
            try {
                $mime = Upload::mime($file);
                $path = str_starts_with($mime, 'image/')
                    ? Upload::image($file, $folder)
                    : Upload::file($file, $folder);
                $this->model->insert([
                    'title'         => pathinfo($file['name'], PATHINFO_FILENAME),
                    'original_name' => $file['name'],
                    'file_path'     => $path,
                    'file_type'     => Upload::categoryFromMime($mime),
                    'mime_type'     => $mime,
                    'file_size'     => (int)$file['size'],
                    'folder'        => $folder,
                    'uploaded_by'   => Auth::id(),
                ]);
                $uploaded++;
            } catch (\Throwable $e) {
                continue;
            }
        }

        Session::flash('success', "{$uploaded} file(s) uploaded.");
        if ($uploaded > 0) {
            $this->audit('upload', 'media', "Uploaded {$uploaded} file(s) to {$folder}");
        }
        $this->redirect('/admin/media' . ($folder ? '?folder=' . urlencode($folder) : ''));
    }

    public function update(array $params = []): void
    {
        $this->requirePermission('media.manage');
        CSRF::check();
        $item = $this->model->findById((int)$params['id']);
        if (!$item) {
            $this->abort(404);
        }

        $this->model->update((int)$item['id'], [
            'title'    => $this->sanitize($this->post('title', (string)($item['title'] ?? ''))),
            'alt_text' => $this->sanitize($this->post('alt_text', '')),
            'caption'  => $this->sanitize($this->post('caption', '')),
            'folder'   => Model::slugify($this->sanitize($this->post('folder', (string)($item['folder'] ?? 'media')))) ?: 'media',
        ]);
        $this->audit('update', 'media', "Updated media \"{$item['title']}\"", 'media', (int)$item['id']);

        Session::flash('success', 'Media details updated.');
        $this->redirect('/admin/media');
    }

    public function replace(array $params = []): void
    {
        $this->requirePermission('media.manage');
        CSRF::check();
        $item = $this->model->findById((int)$params['id']);
        if (!$item || empty($_FILES['file']['name'])) {
            Session::flash('error', 'Select a replacement file.');
            $this->redirect('/admin/media');
        }

        try {
            $mime = Upload::mime($_FILES['file']);
            $folder = (string)($item['folder'] ?? 'media');
            $path = str_starts_with($mime, 'image/')
                ? Upload::image($_FILES['file'], $folder)
                : Upload::file($_FILES['file'], $folder);
            Upload::delete($item['file_path']);
            $this->model->update((int)$item['id'], [
                'file_path'     => $path,
                'original_name' => $_FILES['file']['name'],
                'mime_type'     => $mime,
                'file_type'     => Upload::categoryFromMime($mime),
                'file_size'     => (int)$_FILES['file']['size'],
            ]);
            $this->audit('replace', 'media', "Replaced media \"{$item['title']}\"", 'media', (int)$item['id']);
            Session::flash('success', 'File replaced successfully.');
        } catch (\Throwable $e) {
            Session::flash('error', $e->getMessage());
        }
        $this->redirect('/admin/media');
    }

    public function destroy(array $params = []): void
    {
        $this->requirePermission('media.manage');
        CSRF::check();
        $item = $this->model->findById((int)$params['id']);
        if ($item) {
            if ($this->model->usageCount((int)$item['id']) > 0) {
                Session::flash('error', 'This file is in use and cannot be deleted. Remove references first.');
                $this->redirect('/admin/media');
            }
            Upload::delete($item['file_path']);
            $this->model->clearUsage((int)$item['id']);
            $this->model->delete((int)$item['id']);
            $this->audit('delete', 'media', "Deleted media \"{$item['title']}\"", 'media', (int)$item['id']);
        }
        Session::flash('success', 'Media item deleted.');
        $this->redirect('/admin/media');
    }

    public function show(array $params = []): void
    {
        $this->requirePermission('media.manage');
        $item = $this->model->findById((int)$params['id']);
        if (!$item) {
            $this->abort(404);
        }
        $usage = $this->model->usage((int)$item['id']);
        $this->render('admin/media/show', compact('item', 'usage'), 'admin');
    }

    public function picker(array $params = []): void
    {
        $this->requirePermission('media.manage');
        $q = $this->sanitize($this->get('q', ''));
        $type = $this->sanitize($this->get('type', 'image'));
        $paged = $this->model->search($q, (int)$this->get('page', 1), 30, null, $type ?: null);
        $this->json([
            'items' => array_map(static fn ($item) => [
                'id'        => $item['id'],
                'title'     => $item['title'],
                'url'       => uploadUrl($item['file_path']),
                'path'      => $item['file_path'],
                'mime_type' => $item['mime_type'] ?? $item['file_type'],
                'alt_text'  => $item['alt_text'],
            ], $paged['items']),
            'pagination' => [
                'current'   => $paged['current'],
                'last_page' => $paged['last_page'],
            ],
        ]);
    }
}
