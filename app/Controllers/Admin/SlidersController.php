<?php
declare(strict_types=1);

class AdminSlidersController extends Controller
{
    private const TRANSITIONS = ['inherit', 'fade', 'slide', 'kenburns'];
    private const GLOBAL_TRANSITIONS = ['fade', 'slide', 'kenburns'];
    private const EXAMPLE_TITLES = [
        'Build Your Dream Home With HighQ Homes',
        'Modern Architecture. Timeless Design.',
        'Built With Quality. Designed To Last.',
        'Your Vision. Our Expertise.',
    ];
    private const EXAMPLE_SUBTITLES = [
        'Premium Construction & Architecture',
        'Residential and commercial builds',
        'Craftsmanship you can trust',
        'From concept to handover',
    ];

    private SliderModel $model;

    public function __construct()
    {
        $this->model = new SliderModel();
        $this->model->ensureSchema();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $sliders = $this->model->findAll('sort_order', 'ASC');
        $settings = (new SettingModel())->getAllAsMap();
        $heroOptions = $this->carouselOptions($settings);
        $this->render('admin/sliders/index', compact('sliders', 'heroOptions'), 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requireManage();
        $index = $this->model->count() % 4;
        $slider = [
            'title'            => self::EXAMPLE_TITLES[$index],
            'subtitle'         => self::EXAMPLE_SUBTITLES[$index],
            'description'      => 'From blueprint to handover — disciplined planning, transparent timelines, and craftsmanship in every detail.',
            'button_text'      => 'Get a Free Quote',
            'button_link'      => '/contact',
            'button_text_2'    => 'View Our Work',
            'button_link_2'    => '/projects',
            'is_published'     => 1,
            'show_description' => 1,
            'image_focus'      => 'center',
            'content_style'    => 'standard',
            'text_align'       => 'center',
            'overlay_opacity'  => 0.6,
            'transition_type'  => 'inherit',
        ];
        $this->render('admin/sliders/form', [
            'slider' => $slider,
            'isEdit' => false,
            'action' => url('admin/sliders/create'),
        ], 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $data = $this->formData();
        $data['sort_order'] = $this->model->nextSortOrder();
        if ($data['title'] === '') {
            Session::flash('error', 'A slide title is required.');
            $this->redirect('/admin/sliders/create');
        }

        try {
            $data = $this->applyUploads($data, null);
        } catch (\Throwable $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('/admin/sliders/create');
        }

        $id = $this->model->insert($this->model->onlyColumns($data));
        $this->audit('create', 'sliders', 'Created hero slide "' . $data['title'] . '"', 'slider', $id);
        Session::flash('success', 'Hero slide created. It will appear on the homepage if it is published.');
        $this->redirect('/admin/sliders');
    }

    public function edit(array $params = []): void
    {
        $this->requireManage();
        $slider = $this->model->findById((int)$params['id']);
        if (!$slider) {
            $this->abort(404);
        }
        $this->render('admin/sliders/form', [
            'slider' => $slider,
            'isEdit' => true,
            'action' => url('admin/sliders/' . $slider['id'] . '/edit'),
        ], 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $id = (int)$params['id'];
        $slider = $this->model->findById($id);
        if (!$slider) {
            $this->abort(404);
        }

        $data = $this->formData();
        if ($this->model->hasColumn('updated_at')) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        if ($data['title'] === '') {
            Session::flash('error', 'A slide title is required.');
            $this->redirect('/admin/sliders/' . $id . '/edit');
        }

        try {
            $data = $this->applyUploads($data, $slider);
        } catch (\Throwable $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('/admin/sliders/' . $id . '/edit');
        }

        $this->model->update($id, $this->model->onlyColumns($data));
        $this->audit('update', 'sliders', 'Updated hero slide "' . $data['title'] . '"', 'slider', $id);
        Session::flash('success', 'Hero slide saved.');
        $this->redirect('/admin/sliders');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $slider = $this->model->findById((int)$params['id']);
        if ($slider) {
            $this->deleteImageIfUnused((string)($slider['image'] ?? ''), (int)$slider['id']);
            $this->deleteImageIfUnused((string)($slider['mobile_image'] ?? ''), (int)$slider['id']);
            $this->model->delete((int)$params['id']);
            $this->audit('delete', 'sliders', 'Deleted hero slide "' . ($slider['title'] ?? '') . '"', 'slider', (int)$params['id']);
        }
        Session::flash('success', 'Hero slide removed.');
        $this->redirect('/admin/sliders');
    }

    public function toggle(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $slider = $this->model->findById((int)$params['id']);
        if ($slider) {
            $next = empty($slider['is_published']) ? 1 : 0;
            $this->model->update((int)$slider['id'], $this->model->onlyColumns(['is_published' => $next]));
            $this->audit('toggle', 'sliders', 'Toggled hero slide visibility', 'slider', (int)$slider['id']);
            Session::flash('success', $next ? 'Slide is now live on the homepage.' : 'Slide unpublished.');
        }
        $this->redirect('/admin/sliders');
    }

    public function duplicate(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $slider = $this->model->findById((int)$params['id']);
        if (!$slider) {
            $this->abort(404);
        }

        unset($slider['id'], $slider['created_at'], $slider['updated_at']);
        $slider['title'] = trim((string)$slider['title'] . ' (copy)');
        $slider['is_published'] = 0;
        $slider['sort_order'] = $this->model->nextSortOrder();
        $slider['image'] = $this->copyUpload((string)($slider['image'] ?? ''));
        $slider['mobile_image'] = $this->copyUpload((string)($slider['mobile_image'] ?? ''));

        $id = $this->model->insert($this->model->onlyColumns($slider));
        $this->audit('create', 'sliders', 'Duplicated hero slide', 'slider', $id);
        Session::flash('success', 'Slide duplicated as a draft. Review it before publishing.');
        $this->redirect('/admin/sliders/' . $id . '/edit');
    }

    public function reorder(array $params = []): void
    {
        $this->requireManage();
        if (!CSRF::checkApi()) {
            $this->json(['ok' => false, 'error' => 'Security token expired. Refresh and try again.'], 419);
        }

        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids)) {
            $this->json(['ok' => false, 'error' => 'Invalid slide order.'], 422);
        }
        $ids = array_values(array_filter(array_map('intval', $ids)));
        $this->model->reorder($ids);
        $this->audit('update', 'sliders', 'Reordered hero slides');
        $this->json(['ok' => true, 'csrf' => CSRF::token()]);
    }

    public function updateSettings(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $transition = $this->post('hero_carousel_transition', 'kenburns');
        if (!in_array($transition, self::GLOBAL_TRANSITIONS, true)) {
            $transition = 'kenburns';
        }

        (new SettingModel())->setBulk([
            'hero_carousel_autoplay'    => $this->post('hero_carousel_autoplay') ? '1' : '0',
            'hero_carousel_interval'    => (string)max(3, min(15, (int)$this->post('hero_carousel_interval', 6))),
            'hero_carousel_dots'        => $this->post('hero_carousel_dots') ? '1' : '0',
            'hero_carousel_pause_hover' => $this->post('hero_carousel_pause_hover') ? '1' : '0',
            'hero_carousel_transition'  => $transition,
        ]);
        $this->audit('update', 'sliders', 'Updated hero carousel display options');
        Session::flash('success', 'Hero slider settings saved.');
        $this->redirect('/admin/sliders');
    }

    /** @return array<string, mixed> */
    private function carouselOptions(array $settings): array
    {
        $transition = (string)($settings['hero_carousel_transition'] ?? 'kenburns');
        if (!in_array($transition, self::GLOBAL_TRANSITIONS, true)) {
            $transition = 'kenburns';
        }
        return [
            'autoplay'    => ($settings['hero_carousel_autoplay'] ?? '1') === '1',
            'interval'    => max(3, min(15, (int)($settings['hero_carousel_interval'] ?? 6))),
            'dots'        => ($settings['hero_carousel_dots'] ?? '1') === '1',
            'pause_hover' => ($settings['hero_carousel_pause_hover'] ?? '1') === '1',
            'transition'  => $transition,
        ];
    }

    /** @return array<string, mixed> */
    private function formData(): array
    {
        $transition = $this->post('transition_type', 'inherit');
        if (!in_array($transition, self::TRANSITIONS, true)) {
            $transition = 'inherit';
        }
        $focus = $this->post('image_focus', 'center');
        if (!in_array($focus, ['center', 'top', 'bottom'], true)) {
            $focus = 'center';
        }
        $style = $this->post('content_style', 'standard');
        if (!in_array($style, ['standard', 'minimal', 'bold'], true)) {
            $style = 'standard';
        }
        $align = $this->post('text_align', 'center');
        if (!in_array($align, ['left', 'center', 'right'], true)) {
            $align = 'center';
        }
        $duration = (int)$this->post('autoplay_duration', 0);
        $start = trim((string)$this->post('start_date', ''));
        $end = trim((string)$this->post('end_date', ''));

        return [
            'title'             => $this->sanitize((string)$this->post('title', '')),
            'subtitle'          => $this->sanitize((string)$this->post('subtitle', '')),
            'description'       => $this->sanitize((string)$this->post('description', '')),
            'button_text'       => $this->sanitize((string)$this->post('button_text', '')),
            'button_link'       => $this->sanitizeUrl((string)$this->post('button_link', '')),
            'button_text_2'     => $this->sanitize((string)$this->post('button_text_2', '')),
            'button_link_2'     => $this->sanitizeUrl((string)$this->post('button_link_2', '')),
            'overlay_opacity'   => min(1, max(0, (float)$this->post('overlay_opacity', 0.6))),
            'text_align'        => $align,
            'show_description'  => $this->post('show_description') ? 1 : 0,
            'image_focus'       => $focus,
            'content_style'     => $style,
            'badge_text'        => $this->sanitize((string)$this->post('badge_text', '')) ?: null,
            'autoplay_duration' => $duration >= 3 && $duration <= 20 ? $duration : null,
            'transition_type'   => $transition,
            'start_date'        => $this->normalizeDate($start),
            'end_date'          => $this->normalizeDate($end),
            'is_published'      => $this->post('is_published') ? 1 : 0,
        ];
    }

    private function sanitizeUrl(string $url): string
    {
        $url = trim(strip_tags($url));
        if ($url === '') {
            return '';
        }
        if (preg_match('#^(https?:)?//#i', $url) || str_starts_with($url, '/')) {
            return $url;
        }
        return '/' . ltrim($url, '/');
    }

    private function normalizeDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }
        $value = str_replace('T', ' ', $value);
        $ts = strtotime($value);
        return $ts ? date('Y-m-d H:i:s', $ts) : null;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed>|null $existing
     * @return array<string, mixed>
     */
    private function applyUploads(array $data, ?array $existing): array
    {
        if (!empty($_FILES['image']['name'])) {
            if (!empty($existing['image'])) {
                $this->deleteImageIfUnused((string)$existing['image'], isset($existing['id']) ? (int)$existing['id'] : null);
            }
            $data['image'] = Upload::image($_FILES['image'], 'sliders');
        } elseif ($this->post('remove_image') && $existing) {
            $this->deleteImageIfUnused((string)($existing['image'] ?? ''), (int)$existing['id']);
            $data['image'] = null;
        }

        if (!empty($_FILES['mobile_image']['name'])) {
            if (!empty($existing['mobile_image'])) {
                $this->deleteImageIfUnused((string)$existing['mobile_image'], isset($existing['id']) ? (int)$existing['id'] : null);
            }
            $data['mobile_image'] = Upload::image($_FILES['mobile_image'], 'sliders');
        } elseif ($this->post('remove_mobile_image') && $existing) {
            $this->deleteImageIfUnused((string)($existing['mobile_image'] ?? ''), (int)$existing['id']);
            $data['mobile_image'] = null;
        }

        return $data;
    }

    private function deleteImageIfUnused(string $path, ?int $exceptId = null): void
    {
        if ($path === '' || str_starts_with($path, 'http')) {
            return;
        }
        if ($this->model->hasColumn('mobile_image') && $this->model->countUsingImage($path, $exceptId) > 0) {
            return;
        }
        Upload::delete($path);
    }

    private function copyUpload(string $path): ?string
    {
        if ($path === '') {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        $src = ROOT_PATH . '/' . ltrim($path, '/');
        if (!is_file($src)) {
            return $path;
        }
        $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION) ?: 'jpg');
        $ext = preg_replace('/[^a-z0-9]/', '', $ext) ?: 'jpg';
        $destRel = 'uploads/sliders/' . uniqid('file_', true) . '.' . $ext;
        $dest = ROOT_PATH . '/' . $destRel;
        $dir = dirname($dest);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        copy($src, $dest);
        return $destRel;
    }
}
