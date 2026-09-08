<?php
declare(strict_types=1);

class PageController extends Controller
{
    public function show(array $params = []): void
    {
        $slug = $this->sanitize($params['slug'] ?? '');
        $page = (new PageModel())->findPublishedBySlug($slug);
        if (!$page) {
            $this->abort(404);
        }

        $settings = (new SettingModel())->getAllAsMap();
        $seo = [
            'meta_title' => $page['meta_title'] ?: $page['title'],
            'meta_description' => $page['meta_description'] ?: $page['excerpt'],
        ];

        $this->render('pages/show', compact('page', 'settings', 'seo'));
    }
}
