<?php
declare(strict_types=1);

class ProjectsController extends Controller
{
    public function index(array $params = []): void
    {
        $category   = $this->get('category');
        $status     = $this->get('status');
        $projects   = [];
        $categories = [];
        $settings   = [];
        $seo        = null;
        $sections   = [];
        $stats      = [
            'showing'     => 0,
            'total'       => 0,
            'completed'   => 0,
            'in_progress' => 0,
            'categories'  => 0,
            'years'       => '',
        ];

        $statuses = [];

        $this->tryLoad(function () use ($category, $status, &$projects, &$categories, &$statuses, &$settings, &$seo, &$sections, &$stats): void {
            $model      = new ProjectModel();
            $model->ensureShowcase();
            $projects   = $model->getPublished($category ?: null, $status ?: null);
            $categories = $model->getCategories();
            $statuses   = $model->getStatuses();
            $settings   = (new SettingModel())->getAllAsMap();
            $seo        = (new SeoModel())->findBySlug('projects');
            $sections   = (new PageSectionModel())->getByPage('projects');
            $stats      = [
                'showing'     => count($projects),
                'total'       => $model->countPublished(),
                'completed'   => $model->countByStatus('completed'),
                'in_progress' => $model->countByStatus('in_progress'),
                'categories'  => count($categories),
                'years'       => $model->yearSpan(),
            ];
        });

        $this->render('projects/index', compact('projects', 'categories', 'statuses', 'category', 'status', 'settings', 'seo', 'stats', 'sections'));
    }

    public function show(array $params = []): void
    {
        $slug     = (string)($params['slug'] ?? '');
        $project  = null;
        $related  = [];
        $settings = [];
        $seo      = null;

        $this->tryLoad(function () use ($slug, &$project, &$related, &$settings, &$seo): void {
            $model   = new ProjectModel();
            $model->ensureShowcase();
            $found   = $model->findBySlug($slug);
            if ($found && !empty($found['is_published'])) {
                $project = $found;
                $related = $model->getRelated((int)$project['id'], $project['category'] ?? null, 3, $slug);
            }
            $settings = (new SettingModel())->getAllAsMap();
            if ($project) {
                $seo = (new SeoModel())->findBySlug('project-' . $project['slug']);
            }
        });

        if (!$project || empty($project['is_published'])) {
            foreach (projectShowcaseItems() as $item) {
                if (($item['slug'] ?? '') === $slug) {
                    $project = $item;
                    break;
                }
            }
        }

        if (!$project || empty($project['is_published'])) {
            $this->abort(404);
        }

        if ($related === []) {
            $related = array_slice(array_values(array_filter(
                projectShowcaseItems(),
                static fn(array $row): bool => ($row['slug'] ?? '') !== $slug
            )), 0, 3);
        }

        $this->render('projects/show', compact('project', 'related', 'settings', 'seo'));
    }
}
