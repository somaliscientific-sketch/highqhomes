<?php
declare(strict_types=1);

/**
 * Ensure every About page UI block has a Page Sections row.
 * Existing customized titles/copy are left untouched.
 * Empty data/content on existing rows is filled so the editor has controls.
 */

require_once __DIR__ . '/../config/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Models/PageSectionModel.php';

$model = new PageSectionModel();
$existing = $model->getByPage('about');
$existingTimeline = [];
if (isset($existing['history']['data']['timeline']) && is_array($existing['history']['data']['timeline'])) {
    $existingTimeline = $existing['history']['data']['timeline'];
}

$defaults = [
    'hero' => [
        'title' => 'Who We Are',
        'subtitle' => 'Built on integrity. Delivered with precision.',
        'content' => 'Premium construction across Puntland, Somalia.',
        'image_url' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&q=80',
        'data' => [
            'chips' => [
                ['icon' => 'bi-calendar2-check', 'label' => 'Established 2016'],
                ['icon' => 'bi-geo-alt', 'label' => 'Garowe, Puntland'],
                ['icon' => 'bi-award', 'label' => 'Residential & commercial'],
            ],
            'snapshot_title' => 'Company snapshot',
            'snapshot_link' => 'Read our story',
            'button_text' => 'View our work',
            'button_text_2' => 'Get a quote',
        ],
        'sort_order' => 0,
    ],
    'history' => [
        'title' => 'Our History',
        'subtitle' => 'The HighQ Homes story',
        'content' => 'HighQ Homes was founded in Garowe with a clear purpose — deliver world-class residential and commercial construction with honest service, broad vision, and great value.',
        'image_url' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80',
        'data' => [
            'facts' => [
                ['label' => 'Founded', 'value' => '2016'],
                ['label' => 'Headquarters', 'value' => 'Garowe, Puntland'],
                ['label' => 'Focus', 'value' => 'Residential & Commercial'],
            ],
            'cta_label' => 'Talk to our team',
            'badge_label' => 'Established',
        ],
        'sort_order' => 1,
    ],
    'timeline' => [
        'title' => 'Timeline',
        'subtitle' => 'Milestones that shaped HighQ Homes',
        'content' => 'Key moments from founding to the work we deliver today.',
        'data' => $existingTimeline !== [] ? $existingTimeline : [
            ['year' => '2016', 'text' => 'HighQ Homes established in Garowe with a mission to raise construction standards across Puntland.'],
            ['year' => '2021', 'text' => 'First residential and commercial projects delivered with structured quality control.'],
            ['year' => '2023', 'text' => 'Expanded integrated design, construction, and finishing services under one team.'],
            ['year' => 'Today', 'text' => 'Trusted partner for premium builds — from planning to handover.'],
        ],
        'sort_order' => 2,
    ],
    'stats' => [
        'title' => 'Highlights',
        'subtitle' => 'Company snapshot',
        'content' => 'Proof points from our work across Puntland.',
        'data' => [
            ['num' => '10', 'suffix' => '', 'label' => 'Years Operating', 'icon' => 'bi-award'],
            ['num' => '8', 'suffix' => '', 'label' => 'Profiled Projects', 'icon' => 'bi-buildings'],
            ['num' => '3', 'suffix' => '', 'label' => 'Completed Projects', 'icon' => 'bi-check2-circle'],
            ['num' => '5', 'suffix' => '', 'label' => 'Current Projects', 'icon' => 'bi-building-gear'],
        ],
        'sort_order' => 3,
    ],
    'mission' => [
        'title' => 'Purpose & Direction',
        'subtitle' => 'Mission & Vision',
        'content' => 'What we stand for and where we are going.',
        'data' => [
            ['icon' => 'bi-bullseye', 'eyebrow' => 'Purpose', 'title' => 'Our mission', 'text' => 'Deliver exceptional construction with premium materials, skilled craftsmanship, and unwavering integrity.'],
            ['icon' => 'bi-compass', 'eyebrow' => 'Direction', 'title' => 'Our vision', 'text' => 'Lead East Africa in sustainable, innovative construction that transforms communities.'],
        ],
        'sort_order' => 4,
    ],
    'values' => [
        'title' => 'Our Values',
        'subtitle' => 'What guides every project',
        'content' => 'Principles that shape how we plan, build, and support every client relationship.',
        'data' => [
            ['icon' => 'bi-shield-check', 'title' => 'Integrity First', 'text' => 'Honest timelines, transparent pricing, and accountable communication.'],
            ['icon' => 'bi-gem', 'title' => 'Premium Quality', 'text' => 'Materials and workmanship held to standards you can measure.'],
            ['icon' => 'bi-people', 'title' => 'Client Partnership', 'text' => 'Responsive support before, during, and after handover.'],
            ['icon' => 'bi-compass', 'title' => 'Local Expertise', 'text' => 'Deep knowledge of Puntland sites, regulations, and community needs.'],
        ],
        'sort_order' => 5,
    ],
    'process' => [
        'title' => 'How We Work',
        'subtitle' => 'Simple steps, clear delivery',
        'content' => 'A structured path that keeps your project transparent, controlled, and on schedule.',
        'sort_order' => 6,
    ],
    'team' => [
        'title' => 'Leadership',
        'subtitle' => 'The team behind every build',
        'content' => 'Experienced leaders guiding strategy, quality, and client care on every project.',
        'sort_order' => 7,
    ],
    'testimonials' => [
        'title' => 'Client voices',
        'subtitle' => 'Trusted by owners & developers',
        'content' => 'What clients say about working with HighQ Homes from first meeting to handover.',
        'sort_order' => 8,
    ],
    'cta' => [
        'title' => 'Start your project',
        'subtitle' => 'Ready to build with HighQ Homes?',
        'content' => 'Share your vision — we\'ll guide you from planning to handover with clarity, quality, and care.',
        'data' => [
            'button_text' => 'Get a quote',
            'button_text_2' => 'Contact us',
        ],
        'sort_order' => 9,
    ],
];

$created = [];
$filled = [];

foreach ($defaults as $key => $row) {
    if (!isset($existing[$key])) {
        $model->upsert([
            'page_key' => 'about',
            'section_key' => $key,
            'title' => $row['title'] ?? '',
            'subtitle' => $row['subtitle'] ?? '',
            'content' => $row['content'] ?? '',
            'data' => $row['data'] ?? null,
            'image_url' => $row['image_url'] ?? null,
            'is_enabled' => 1,
            'sort_order' => $row['sort_order'] ?? 0,
        ]);
        $created[] = $key;
        continue;
    }

    $current = $existing[$key];
    $updates = [];
    $currentData = $current['data'] ?? null;
    if (($currentData === null || $currentData === [] || $currentData === '') && isset($row['data'])) {
        $updates['data'] = json_encode($row['data'], JSON_UNESCAPED_UNICODE);
    }
    if (trim((string)($current['content'] ?? '')) === '' && !empty($row['content'])) {
        $updates['content'] = $row['content'];
    }
    if ($updates !== []) {
        $model->update((int)$current['id'], $updates);
        $filled[] = $key;
    }
}

$parts = [];
if ($created !== []) {
    $parts[] = 'created ' . implode(', ', $created);
}
if ($filled !== []) {
    $parts[] = 'filled empty fields on ' . implode(', ', $filled);
}
echo $parts === []
    ? "About CMS sections already complete.\n"
    : 'About CMS: ' . implode('; ', $parts) . "\n";
