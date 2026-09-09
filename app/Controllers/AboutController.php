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

        $this->tryLoad(function () use (&$testimonials, &$team, &$settings, &$seo, &$sections): void {
            $testimonials = (new TestimonialModel())->getFeatured(3);
            $team         = (new TeamModel())->getPublished();
            $settings     = (new SettingModel())->getAllAsMap();
            $seo          = (new SeoModel())->findBySlug('about');
            $sections     = (new PageSectionModel())->getByPage('about');
        });

        $statItems = [
            ['num' => statNumber((string)($settings['stat_years'] ?? '10')), 'suffix' => '', 'label' => 'Years Operating', 'icon' => 'bi-award'],
            ['num' => statNumber((string)($settings['stat_projects'] ?? '8')), 'suffix' => '', 'label' => 'Profiled Projects', 'icon' => 'bi-buildings'],
            ['num' => statNumber((string)($settings['stat_clients'] ?? '3')), 'suffix' => '', 'label' => 'Completed Projects', 'icon' => 'bi-check2-circle'],
            ['num' => statNumber((string)($settings['stat_satisfaction'] ?? '5')), 'suffix' => '', 'label' => 'Current Projects', 'icon' => 'bi-building-gear'],
        ];

        $this->render('about/index', compact('team', 'testimonials', 'settings', 'seo', 'sections', 'statItems'));
    }
}
