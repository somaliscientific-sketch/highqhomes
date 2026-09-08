<?php
declare(strict_types=1);

class ServicesController extends Controller
{
    public function index(array $params = []): void
    {
        $model    = new ServiceModel();
        $services = $model->getPublished();
        $settings = (new SettingModel())->getAllAsMap();
        $seo      = (new SeoModel())->findBySlug('services');
        $sections = (new PageSectionModel())->getByPage('services');

        $statItems = [
            ['num' => statNumber((string)($settings['stat_projects'] ?? '8')), 'suffix' => '', 'label' => 'Profiled Projects', 'icon' => 'bi-buildings'],
            ['num' => statNumber((string)($settings['stat_years'] ?? '10')), 'suffix' => '', 'label' => 'Years Operating', 'icon' => 'bi-award'],
            ['num' => statNumber((string)($settings['stat_clients'] ?? '3')), 'suffix' => '', 'label' => 'Completed Projects', 'icon' => 'bi-check2-circle'],
            ['num' => statNumber((string)($settings['stat_satisfaction'] ?? '5')), 'suffix' => '', 'label' => 'Current Projects', 'icon' => 'bi-building-gear'],
        ];

        $stats = [
            'total'    => $model->countPublished(),
            'featured' => $model->countFeatured(),
        ];

        $this->render('services/index', compact('services', 'settings', 'seo', 'sections', 'statItems', 'stats'));
    }

    public function show(array $params = []): void
    {
        $model   = new ServiceModel();
        $service = $model->findBySlug($params['slug'] ?? '');

        if (!$service || !$service['is_published']) {
            $this->abort(404);
        }

        $allServices = $model->getPublished();
        $related     = $model->getRelated((int)$service['id'], 3);
        $settings    = (new SettingModel())->getAllAsMap();
        $seo         = (new SeoModel())->findBySlug('service-' . $service['slug']);

        $this->render('services/show', compact('service', 'allServices', 'related', 'settings', 'seo'));
    }
}
