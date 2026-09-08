<?php
declare(strict_types=1);

/**
 * Insert missing Page Sections for every public page. Existing rows stay untouched.
 */

require_once __DIR__ . '/../config/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Models/PageSectionModel.php';

$model = new PageSectionModel();

$catalog = [
    'services' => [
        'hero' => ['title' => 'Our Services', 'subtitle' => 'Design, build & deliver with one trusted team', 'content' => 'From architecture to finishing — clear scope, premium quality, and accountable delivery.', 'sort_order' => 0],
        'intro' => ['title' => 'What we offer', 'subtitle' => 'Full-service construction', 'content' => 'Residential, commercial, and finishing services under one team.', 'sort_order' => 1],
        'process' => ['title' => 'How we deliver', 'subtitle' => 'A clear path from first meeting to handover', 'content' => '', 'sort_order' => 2],
        'faq' => ['title' => 'Questions', 'subtitle' => 'Service FAQs', 'content' => '', 'sort_order' => 3],
        'cta' => ['title' => 'Ready to start', 'subtitle' => 'Request a structured quote', 'content' => 'Tell us about your site and we will respond with clear next steps.', 'sort_order' => 4],
    ],
    'projects' => [
        'hero' => ['title' => 'Portfolio', 'subtitle' => 'Projects delivered with confidence', 'content' => 'Residential, commercial, and community builds across Puntland.', 'sort_order' => 0],
        'intro' => ['title' => 'Our work', 'subtitle' => 'Built to last. Designed to impress.', 'content' => '', 'sort_order' => 1],
        'pillars' => ['title' => 'How we build', 'subtitle' => 'Delivery principles', 'content' => '', 'sort_order' => 2],
        'approach' => ['title' => 'Approach', 'subtitle' => 'From discovery to handover', 'content' => '', 'sort_order' => 3],
        'cta' => ['title' => 'Start a project', 'subtitle' => 'Share your vision', 'content' => '', 'sort_order' => 4],
    ],
    'gallery' => [
        'hero' => ['title' => 'Our work', 'subtitle' => 'Project gallery', 'content' => 'Photos from builds across Puntland.', 'sort_order' => 0],
        'intro' => ['title' => 'Visual portfolio', 'subtitle' => 'Craftsmanship in every frame', 'content' => '', 'sort_order' => 1],
        'highlights' => ['title' => 'Highlights', 'subtitle' => '', 'content' => '', 'sort_order' => 2],
        'cta' => ['title' => 'Ready to build', 'subtitle' => 'Start your project', 'content' => '', 'sort_order' => 3],
    ],
    'paints' => [
        'hero' => ['title' => 'Products', 'subtitle' => 'Premium paints & coatings', 'content' => 'Professional-grade finishes with expert guidance.', 'sort_order' => 0],
        'intro' => ['title' => 'Finishing Excellence', 'subtitle' => 'Colors & Coatings That Endure', 'content' => '', 'sort_order' => 1],
        'benefits' => ['title' => 'Benefits', 'subtitle' => 'Why our coatings last', 'content' => '', 'sort_order' => 2],
        'cta' => ['title' => 'Need advice', 'subtitle' => 'Ask our finishing team', 'content' => '', 'sort_order' => 3],
    ],
    'contact' => [
        'hero' => ['title' => 'Contact Us', 'subtitle' => 'Let\'s plan your next project', 'content' => 'Share your site details, drawings, or goals.', 'sort_order' => 0],
        'process' => ['title' => 'What happens next', 'subtitle' => 'Review, clarify, plan', 'content' => '', 'sort_order' => 1],
        'cta' => ['title' => 'Prefer WhatsApp', 'subtitle' => 'Talk to us now', 'content' => '', 'sort_order' => 2],
    ],
];

$created = [];
foreach ($catalog as $page => $sections) {
    $existing = $model->getByPage($page);
    foreach ($sections as $key => $row) {
        if (isset($existing[$key])) {
            continue;
        }
        $model->upsert([
            'page_key' => $page,
            'section_key' => $key,
            'title' => $row['title'] ?? '',
            'subtitle' => $row['subtitle'] ?? '',
            'content' => $row['content'] ?? '',
            'data' => $row['data'] ?? null,
            'image_url' => $row['image_url'] ?? null,
            'is_enabled' => 1,
            'sort_order' => $row['sort_order'] ?? 0,
        ]);
        $created[] = $page . '.' . $key;
    }
}

echo $created === []
    ? "All public page CMS sections already exist.\n"
    : 'Created: ' . implode(', ', $created) . "\n";
