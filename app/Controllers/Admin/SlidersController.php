<?php
declare(strict_types=1);

class AdminSlidersController extends Controller
{
    private const HERO_SLOTS = 4;

    private SliderModel $model;

    public function __construct()
    {
        $this->model = new SliderModel();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $allSliders = $this->model->findAll('sort_order', 'ASC');
        $heroSlots  = $this->mapHeroSlots($allSliders);
        $activeTab  = max(1, min(self::HERO_SLOTS, (int)($_GET['tab'] ?? 1)));
        $settings   = (new SettingModel())->getAllAsMap();
        $heroOptions = [
            'autoplay'    => ($settings['hero_carousel_autoplay'] ?? '1') === '1',
            'interval'    => max(3, min(15, (int)($settings['hero_carousel_interval'] ?? 6))),
            'dots'        => ($settings['hero_carousel_dots'] ?? '1') === '1',
            'pause_hover' => ($settings['hero_carousel_pause_hover'] ?? '1') === '1',
        ];
        $this->render('admin/sliders/index', compact('heroSlots', 'activeTab', 'heroOptions'), 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requireManage();
        $tab = max(1, min(self::HERO_SLOTS, (int)($_GET['tab'] ?? 1)));
        $this->redirect('/admin/sliders?tab=' . $tab);
    }

    public function store(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $slot = max(1, min(self::HERO_SLOTS, (int)$this->post('hero_slot', 1)));
        $data = $this->formData($slot);

        if (!empty($_FILES['image']['name'])) {
            $data['image'] = Upload::image($_FILES['image'], 'sliders');
        }

        $existing = $this->slotSlider($slot);
        if ($existing) {
            if (!empty($_FILES['image']['name']) && $existing['image']) {
                Upload::delete($existing['image']);
            }
            $this->model->update((int)$existing['id'], $data);
            $this->audit('update', 'sliders', "Updated hero section {$slot}", 'slider', (int)$existing['id']);
            $this->tabRedirect($slot, 'Hero section ' . $slot . ' updated.');
            return;
        }

        $id = $this->model->insert($data);
        $this->audit('create', 'sliders', "Created hero section {$slot}", 'slider', $id);
        $this->tabRedirect($slot, 'Hero section ' . $slot . ' created.');
    }

    public function edit(array $params = []): void
    {
        $this->requireManage();
        $slider = $this->model->findById((int)$params['id']);
        if (!$slider) {
            $this->abort(404);
        }

        $tab = max(1, min(self::HERO_SLOTS, (int)$slider['sort_order'] + 1));
        $this->redirect('/admin/sliders?tab=' . $tab);
    }

    public function update(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $id     = (int)$params['id'];
        $slider = $this->model->findById($id);
        if (!$slider) {
            $this->abort(404);
        }

        $slot = max(1, min(self::HERO_SLOTS, (int)$this->post('hero_slot', (int)$slider['sort_order'] + 1)));
        $data = $this->formData($slot);

        if (!empty($_FILES['image']['name'])) {
            if ($slider['image']) {
                Upload::delete($slider['image']);
            }
            $data['image'] = Upload::image($_FILES['image'], 'sliders');
        }

        $this->model->update($id, $data);
        $this->audit('update', 'sliders', "Updated hero section {$slot}", 'slider', $id);
        $this->tabRedirect($slot, 'Hero section ' . $slot . ' saved.');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $slider = $this->model->findById((int)$params['id']);
        $slot = max(1, min(self::HERO_SLOTS, (int)$this->post('hero_slot', $slider ? (int)$slider['sort_order'] + 1 : 1)));
        if ($slider && $slider['image']) {
            Upload::delete($slider['image']);
        }
        if ($slider) {
            $this->model->delete((int)$params['id']);
            $this->audit('delete', 'sliders', "Cleared hero section {$slot}", 'slider', (int)$params['id']);
        }
        $this->tabRedirect($slot, 'Hero section ' . $slot . ' cleared.');
    }

    public function toggle(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $slider = $this->model->findById((int)$params['id']);
        $slot = max(1, min(self::HERO_SLOTS, (int)$this->post('hero_slot', $slider ? (int)$slider['sort_order'] + 1 : 1)));
        if ($slider) {
            $this->model->update($slider['id'], ['is_published' => $slider['is_published'] ? 0 : 1]);
            $this->audit('toggle', 'sliders', 'Toggled hero section ' . $slot . ' visibility', 'slider', (int)$slider['id']);
        }
        $this->tabRedirect($slot, 'Hero section ' . $slot . ' visibility updated.');
    }

    public function updateSettings(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        (new SettingModel())->setBulk([
            'hero_carousel_autoplay'    => $this->post('hero_carousel_autoplay') ? '1' : '0',
            'hero_carousel_interval'    => (string)max(3, min(15, (int)$this->post('hero_carousel_interval', 6))),
            'hero_carousel_dots'        => $this->post('hero_carousel_dots') ? '1' : '0',
            'hero_carousel_pause_hover' => $this->post('hero_carousel_pause_hover') ? '1' : '0',
        ]);
        $this->audit('update', 'sliders', 'Updated hero carousel display options');

        $tab = max(1, min(self::HERO_SLOTS, (int)$this->post('hero_tab', 1)));
        $this->tabRedirect($tab, 'Hero display options saved.');
    }

    /** @return array<int, array|null> */
    private function mapHeroSlots(array $sliders): array
    {
        $slots = array_fill(0, self::HERO_SLOTS, null);
        foreach ($sliders as $slider) {
            $order = (int)$slider['sort_order'];
            if ($order >= 0 && $order < self::HERO_SLOTS && $slots[$order] === null) {
                $slots[$order] = $slider;
            }
        }
        return $slots;
    }

    private function slotSlider(int $slot): ?array
    {
        $slots = $this->mapHeroSlots($this->model->findAll('sort_order', 'ASC'));
        return $slots[$slot - 1] ?? null;
    }

    private function tabRedirect(int $slot, string $message): void
    {
        Session::flash('success', $message);
        $tab = max(1, min(self::HERO_SLOTS, $slot));
        $this->redirect('/admin/sliders?tab=' . $tab);
    }

    private function formData(int $slot): array
    {
        return [
            'title'           => $this->sanitize($this->post('title', '')),
            'subtitle'        => $this->sanitize($this->post('subtitle', '')),
            'description'     => $this->sanitize($this->post('description', '')),
            'button_text'     => $this->sanitize($this->post('button_text', '')),
            'button_link'     => $this->sanitize($this->post('button_link', '')),
            'button_text_2'   => $this->sanitize($this->post('button_text_2', '')),
            'button_link_2'   => $this->sanitize($this->post('button_link_2', '')),
            'overlay_opacity' => (float)$this->post('overlay_opacity', 0.6),
            'text_align'      => $this->post('text_align', 'center'),
            'show_description'=> $this->post('show_description') ? 1 : 0,
            'image_focus'     => in_array($this->post('image_focus', 'center'), ['center', 'top', 'bottom'], true) ? $this->post('image_focus', 'center') : 'center',
            'content_style'   => in_array($this->post('content_style', 'standard'), ['standard', 'minimal', 'bold'], true) ? $this->post('content_style', 'standard') : 'standard',
            'badge_text'      => $this->sanitize($this->post('badge_text', '')) ?: null,
            'sort_order'      => max(0, min(self::HERO_SLOTS - 1, $slot - 1)),
            'is_published'    => $this->post('is_published') ? 1 : 0,
        ];
    }
}
