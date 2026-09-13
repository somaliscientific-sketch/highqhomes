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
        ];

        $this->tryLoad(function () use ($category, $status, &$projects, &$categories, &$settings, &$seo, &$sections, &$stats): void {
            $model      = new ProjectModel();
            $projects   = $model->getPublished($category ?: null, $status ?: null);
            $categories = $model->getCategories();
            $settings   = (new SettingModel())->getAllAsMap();
            $seo        = (new SeoModel())->findBySlug('projects');
            $sections   = (new PageSectionModel())->getByPage('projects');
            $stats      = [
                'showing'     => count($projects),
                'total'       => $model->countPublished(),
                'completed'   => $model->countByStatus('completed'),
                'in_progress' => $model->countByStatus('in_progress'),
                'categories'  => count($categories),
            ];
        });

        $this->render('projects/index', compact('projects', 'categories', 'category', 'status', 'settings', 'seo', 'stats', 'sections'));
    }

    public function show(array $params = []): void
    {
        $model   = new ProjectModel();
        $project = $model->findBySlug($params['slug'] ?? '');

        if (!$project || !$project['is_published']) {
            $this->abort(404);
        }

        $related  = $model->getRelated((int)$project['id'], $project['category'] ?? null, 3);
        $settings = (new SettingModel())->getAllAsMap();
        $seo      = (new SeoModel())->findBySlug('project-' . $project['slug']);

        $this->render('projects/show', compact('project', 'related', 'settings', 'seo'));
    }
}
