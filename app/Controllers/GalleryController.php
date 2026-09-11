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
        $stats      = ['total' => 0, 'showing' => 0, 'categories' => 0, 'completed' => 0];

        $this->tryLoad(function () use ($category, &$items, &$categories, &$settings, &$seo, &$sections, &$stats): void {
            $model = new GalleryModel();
            $model->ensureShowcase();
            $items      = $model->getPublished($category ?: null);
            $categories = $model->getCategories();
            $settings   = (new SettingModel())->getAllAsMap();
            $seo        = (new SeoModel())->findBySlug('gallery');
            $sections   = (new PageSectionModel())->getByPage('gallery');
            $all        = $category ? $model->getPublished() : $items;
            $stats      = [
                'total'      => $model->countPublished(),
                'showing'    => count($items),
                'categories' => count($categories),
                'completed'  => count(array_filter(
                    $all,
                    static fn(array $row): bool => ($row['category'] ?? '') !== 'construction'
                )),
            ];
        });

        $items = array_values(array_filter(
            $items,
            static fn(array $row): bool => !isStaleGalleryImage($row['image'] ?? null)
        ));

        if ($items === []) {
            $items = galleryShowcaseItems($category ?: null);
            $all = galleryShowcaseItems();
            $categories = array_values(array_unique(array_map(
                static fn(array $row): string => (string)($row['category'] ?? ''),
                $all
            )));
            $stats = [
                'total'      => count($all),
                'showing'    => count($items),
                'categories' => count($categories),
                'completed'  => count(array_filter(
                    $all,
                    static fn(array $row): bool => ($row['category'] ?? '') !== 'construction'
                )),
            ];
        }

        if ($categories === []) {
            $categories = array_values(array_unique(array_map(
                static fn(array $row): string => (string)($row['category'] ?? ''),
                galleryShowcaseItems()
            )));
        }

        $this->render('gallery/index', compact('items', 'categories', 'category', 'settings', 'seo', 'stats', 'sections'));
    }
}
