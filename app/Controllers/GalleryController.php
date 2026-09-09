<?php
declare(strict_types=1);

class GalleryController extends Controller
{
    public function index(array $params = []): void
    {
        $category   = $this->get('category');
        $items      = [];
        $categories = [];
        $settings   = [];
        $seo        = null;
        $sections   = [];
        $stats      = ['total' => 0, 'showing' => 0, 'categories' => 0];

        $this->tryLoad(function () use ($category, &$items, &$categories, &$settings, &$seo, &$sections, &$stats): void {
            $model      = new GalleryModel();
            $items      = $model->getPublished($category ?: null);
            $categories = $model->getCategories();
            $settings   = (new SettingModel())->getAllAsMap();
            $seo        = (new SeoModel())->findBySlug('gallery');
            $sections   = (new PageSectionModel())->getByPage('gallery');
            $stats      = [
                'total'      => $model->countPublished(),
                'showing'    => count($items),
                'categories' => count($categories),
            ];
        });

        $this->render('gallery/index', compact('items', 'categories', 'category', 'settings', 'seo', 'stats', 'sections'));
    }
}
