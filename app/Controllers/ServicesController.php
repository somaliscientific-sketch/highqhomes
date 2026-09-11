<?php
declare(strict_types=1);

class ServicesController extends Controller
{
    public function index(array $params = []): void
    {
        $services  = [];
        $settings  = [];
        $seo       = null;
        $sections  = [];
        $stats     = ['total' => 0, 'featured' => 0];
        $statItems = [];
        $foundedYear = 2016;

        $this->tryLoad(function () use (&$services, &$settings, &$seo, &$sections, &$stats, &$statItems, $foundedYear): void {
            $model = new ServiceModel();
            $model->ensureShowcase();
            $services = $model->getPublished();
            $settings = (new SettingModel())->getAllAsMap();
            $seo      = (new SeoModel())->findBySlug('services');
            $sections = (new PageSectionModel())->getByPage('services');
            $stats    = [
                'total'    => $model->countPublished(),
                'featured' => $model->countFeatured(),
            ];

            $projectModel = new ProjectModel();
            $projectModel->ensureShowcase();
            $years = max(1, (int)date('Y') - $foundedYear);
            $statItems = [
                ['num' => (string)$years, 'suffix' => '', 'label' => 'Years in Garowe'],
                ['num' => (string)$projectModel->countPublished(), 'suffix' => '', 'label' => 'Projects shown'],
                ['num' => (string)$projectModel->countByStatus('completed'), 'suffix' => '', 'label' => 'Completed'],
                ['num' => (string)count($services), 'suffix' => '', 'label' => 'Services'],
            ];
        });

        $services = array_values(array_filter(
            $services,
            static fn(array $row): bool => !isStaleServiceSlug((string)($row['slug'] ?? ''))
        ));

        if ($services === []) {
            $services = serviceShowcaseItems();
            $stats = [
                'total'    => count($services),
                'featured' => count(array_filter($services, static fn(array $row): bool => !empty($row['is_featured']))),
            ];
        }

        if ($statItems === []) {
            $all = function_exists('projectShowcaseItems') ? projectShowcaseItems() : [];
            $years = max(1, (int)date('Y') - $foundedYear);
            $statItems = [
                ['num' => (string)$years, 'suffix' => '', 'label' => 'Years in Garowe'],
                ['num' => (string)count($all), 'suffix' => '', 'label' => 'Projects shown'],
                ['num' => (string)count(array_filter($all, static fn(array $row): bool => ($row['status'] ?? '') === 'completed')), 'suffix' => '', 'label' => 'Completed'],
                ['num' => (string)count($services), 'suffix' => '', 'label' => 'Services'],
            ];
        }

        $this->render('services/index', compact('services', 'settings', 'seo', 'sections', 'statItems', 'stats'));
    }

    public function show(array $params = []): void
    {
        $slug        = (string)($params['slug'] ?? '');
        $service     = null;
        $allServices = [];
        $related     = [];
        $settings    = [];
        $seo         = null;

        $this->tryLoad(function () use ($slug, &$service, &$allServices, &$related, &$settings, &$seo): void {
            $model = new ServiceModel();
            $model->ensureShowcase();
            $found = $model->findBySlug($slug);
            if ($found && !empty($found['is_published'])) {
                $service     = $found;
                $allServices = $model->getPublished();
                $related     = $model->getRelated((int)$service['id'], 3, $slug);
            }
            $settings = (new SettingModel())->getAllAsMap();
            if ($service) {
                $seo = (new SeoModel())->findBySlug('service-' . $service['slug']);
            }
        });

        if (!$service || empty($service['is_published'])) {
            foreach (serviceShowcaseItems() as $item) {
                if (($item['slug'] ?? '') === $slug) {
                    $service = $item;
                    break;
                }
            }
        }

        if (!$service || empty($service['is_published'])) {
            $this->abort(404);
        }

        if ($allServices === []) {
            $allServices = serviceShowcaseItems();
        }
        if ($related === []) {
            $related = array_slice(array_values(array_filter(
                serviceShowcaseItems(),
                static fn(array $row): bool => ($row['slug'] ?? '') !== $slug
            )), 0, 3);
        }

        $this->render('services/show', compact('service', 'allServices', 'related', 'settings', 'seo'));
    }
}
