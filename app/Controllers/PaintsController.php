<?php
declare(strict_types=1);

class PaintsController extends Controller
{
    public function index(array $params = []): void
    {
        $model    = new PaintModel();
        $category = trim((string)$this->get('category', ''));
        $brand    = trim((string)$this->get('brand', ''));
        $paints   = $model->getFiltered($category !== '' ? $category : null, $brand !== '' ? $brand : null);
        $all      = $model->getPublished();
        $categories = $model->getCategories();
        $brands     = $model->getBrands();
        $settings   = (new SettingModel())->getAllAsMap();
        $seo        = (new SeoModel())->findBySlug('paints');

        $stats = [
            'total'      => count($all),
            'categories' => count($categories),
            'brands'     => count($brands),
            'showing'    => count($paints),
        ];

        $this->render('paints/index', compact(
            'paints',
            'all',
            'categories',
            'brands',
            'category',
            'brand',
            'stats',
            'settings',
            'seo'
        ));
    }

    public function show(array $params = []): void
    {
        $model = new PaintModel();
        $paint = $model->findBySlug($params['slug'] ?? '');

        if (!$paint || !$paint['is_published']) {
            $this->abort(404);
        }

        $settings = (new SettingModel())->getAllAsMap();
        $seo      = (new SeoModel())->findBySlug('paint-' . $paint['slug']);
        $related  = $model->getRelated((int)$paint['id'], $paint['category'] ?? null, 3);

        $this->render('paints/show', compact('paint', 'related', 'settings', 'seo'));
    }
}
