<?php
declare(strict_types=1);

class HomeController extends Controller
{
    public function index(array $params = []): void
    {
        $featuredProjects = [];
        $latestProjects   = [];
        $sliders          = [];
        $testimonials     = [];
        $settings         = [];
        $seo              = null;
        $sections         = [];

        $this->tryLoad(function () use (&$featuredProjects, &$latestProjects, &$sliders, &$testimonials, &$settings, &$seo, &$sections): void {
            $projectModel     = new ProjectModel();
            $featuredProjects = $projectModel->getFeatured(6);
            $latestProjects   = $projectModel->getLatest(6);
            if (count($latestProjects) < 3) {
                $latestProjects = $projectModel->getLatest(6, false);
            }

            $sliders      = (new SliderModel())->getPublished();
            $testimonials = (new TestimonialModel())->getFeatured(6);
            $settings     = (new SettingModel())->getAllAsMap();
            $seo          = (new SeoModel())->findBySlug('home');
            $sections     = (new PageSectionModel())->getByPage('home');
        });

        $this->render('home/index', compact(
            'sliders',
            'featuredProjects',
            'latestProjects',
            'testimonials',
            'settings',
            'seo',
            'sections'
        ));
    }
}
