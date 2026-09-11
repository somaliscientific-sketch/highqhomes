<?php
declare(strict_types=1);

class AboutController extends Controller
{
    public function index(array $params = []): void
    {
        $testimonials = [];
        $team         = [];
        $settings     = [];
        $seo          = null;
        $sections     = [];
        $foundedYear  = 2016;
        $statItems    = [];

        $this->tryLoad(function () use (&$testimonials, &$team, &$settings, &$seo, &$sections, &$foundedYear, &$statItems): void {
            $testimonials = (new TestimonialModel())->getFeatured(3);
            $team         = (new TeamModel())->getPublished();
            $settings     = (new SettingModel())->getAllAsMap();
            $seo          = (new SeoModel())->findBySlug('about');
            $sections     = (new PageSectionModel())->getByPage('about');

            $projectModel = new ProjectModel();
            $projectModel->ensureShowcase();
            $years = max(1, (int)date('Y') - $foundedYear);
            $statItems = [
                ['num' => (string)$years, 'suffix' => '', 'label' => 'Years in Garowe', 'icon' => 'bi-award'],
                ['num' => (string)$projectModel->countPublished(), 'suffix' => '', 'label' => 'Projects shown', 'icon' => 'bi-buildings'],
                ['num' => (string)$projectModel->countByStatus('completed'), 'suffix' => '', 'label' => 'Completed', 'icon' => 'bi-check2-circle'],
                ['num' => (string)$projectModel->countByStatus('in_progress'), 'suffix' => '', 'label' => 'In progress', 'icon' => 'bi-building-gear'],
            ];
        });

        if ($statItems === []) {
            $all = function_exists('projectShowcaseItems') ? projectShowcaseItems() : [];
            $years = max(1, (int)date('Y') - $foundedYear);
            $statItems = [
                ['num' => (string)$years, 'suffix' => '', 'label' => 'Years in Garowe', 'icon' => 'bi-award'],
                ['num' => (string)count($all), 'suffix' => '', 'label' => 'Projects shown', 'icon' => 'bi-buildings'],
                ['num' => (string)count(array_filter($all, static fn(array $row): bool => ($row['status'] ?? '') === 'completed')), 'suffix' => '', 'label' => 'Completed', 'icon' => 'bi-check2-circle'],
                ['num' => (string)count(array_filter($all, static fn(array $row): bool => ($row['status'] ?? '') === 'in_progress')), 'suffix' => '', 'label' => 'In progress', 'icon' => 'bi-building-gear'],
            ];
        }

        $this->render('about/index', compact('team', 'testimonials', 'settings', 'seo', 'sections', 'statItems'));
    }
}
