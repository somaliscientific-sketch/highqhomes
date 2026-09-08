<?php
declare(strict_types=1);

/**
 * Ensure every homepage UI block has a Page Sections row.
 * Existing customized rows are left untouched.
 */

require_once __DIR__ . '/../config/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Models/PageSectionModel.php';

$model = new PageSectionModel();
$existing = $model->getByPage('home');

$defaults = [
    'hero' => [
        'title' => 'Trusted Builder',
        'subtitle' => 'Since 2016',
        'content' => 'Hero slides are edited in Homepage Hero. Use this section to show or hide the homepage banner and the live badge text.',
        'data' => [
            ['num' => '8', 'suffix' => '', 'label' => 'Projects', 'icon' => 'bi-buildings'],
            ['num' => '3', 'suffix' => '', 'label' => 'Completed', 'icon' => 'bi-check2-circle'],
            ['num' => '10', 'suffix' => '', 'label' => 'Years', 'icon' => 'bi-award'],
        ],
        'sort_order' => 0,
    ],
    'hero_trust' => [
        'title' => 'Trust Indicators',
        'subtitle' => '',
        'content' => '',
        'data' => ['Licensed & Insured', 'On-Time Delivery', 'Premium Finishes', 'Structured Quality Checks', 'Transparent Pricing', 'Dedicated Client Support'],
        'sort_order' => 1,
    ],
    'about_highlights' => [
        'title' => 'Who We Are',
        'subtitle' => 'Built on Integrity. Delivered with Precision.',
        'content' => 'HighQ Homes is a full-service construction partner turning ambitious plans into durable, beautifully finished spaces.',
        'data' => [
            ['icon' => 'bi-award', 'title' => 'Proven Track Record', 'text' => 'Years of successful residential and commercial delivery across Somalia.'],
            ['icon' => 'bi-people', 'title' => 'One Accountable Team', 'text' => 'Design, construction, and finishing coordinated under one roof.'],
            ['icon' => 'bi-graph-up-arrow', 'title' => 'Value-Driven Builds', 'text' => 'Durable materials and smart planning that protect your investment.'],
            ['icon' => 'bi-hand-thumbs-up', 'title' => 'Client-First Approach', 'text' => 'Clear communication from first meeting through final handover.'],
        ],
        'sort_order' => 2,
    ],
    'capabilities' => [
        'title' => 'What We Build',
        'subtitle' => 'Spaces Crafted With Purpose',
        'content' => 'From family homes to commercial landmarks — every project is planned, built, and finished to premium standards.',
        'sort_order' => 3,
    ],
    'projects' => [
        'title' => 'Portfolio',
        'subtitle' => 'Signature Work That Defines Our Standard',
        'content' => 'Explore featured builds and recent completions — each project reflects our commitment to quality, clarity, and premium finish.',
        'data' => [
            'cta_primary' => 'Full Portfolio',
            'cta_secondary' => 'View Gallery',
            'recent_kicker' => 'Recent Sites',
            'recent_title' => 'Fresh Completions & Active Builds',
            'band_title' => 'Ready to start your next build?',
            'band_text' => 'Share your vision and receive a structured consultation from our team.',
        ],
        'sort_order' => 4,
    ],
    'why_us' => [
        'title' => 'Why Choose Us',
        'subtitle' => 'The HighQ Homes Difference',
        'content' => 'Six principles that guide every project — from first consultation to final handover.',
        'sort_order' => 5,
    ],
    'process' => [
        'title' => 'Our Process',
        'subtitle' => 'From Vision to Handover',
        'content' => 'A structured, transparent path designed to reduce risk and deliver exceptional results.',
        'sort_order' => 6,
    ],
    'excellence' => [
        'title' => 'Built to Last',
        'subtitle' => 'Construction Excellence You Can Measure',
        'content' => 'Every HighQ Homes project is managed with the same standard — rigorous planning, accountable execution, and finishes that stand up to daily use and time.',
        'sort_order' => 7,
    ],
    'connect' => [
        'title' => 'Start With Confidence',
        'subtitle' => 'Your Project Deserves a Builder You Can Trust',
        'content' => 'HighQ Homes combines disciplined project management, skilled craftsmanship, and transparent communication — so you stay informed from the first conversation to final handover.',
        'sort_order' => 8,
    ],
    'stats' => [
        'title' => 'Achievements',
        'subtitle' => 'Proof of Excellence',
        'content' => 'Real outcomes from real projects.',
        'data' => [
            ['num' => '10', 'suffix' => '', 'label' => 'Years Operating', 'icon' => 'bi-award'],
            ['num' => '8', 'suffix' => '', 'label' => 'Profiled Projects', 'icon' => 'bi-buildings'],
            ['num' => '3', 'suffix' => '', 'label' => 'Completed Projects', 'icon' => 'bi-check2-circle'],
            ['num' => '5', 'suffix' => '', 'label' => 'Current Projects', 'icon' => 'bi-building-gear'],
            ['num' => '2016', 'suffix' => '', 'label' => 'Established', 'icon' => 'bi-calendar2-check'],
        ],
        'sort_order' => 9,
    ],
    'testimonials' => [
        'title' => 'Testimonials',
        'subtitle' => 'What Our Clients Say',
        'content' => 'Trusted by owners, developers, and community partners across every project type.',
        'data' => [
            'chips' => ['Client-rated excellence', 'Verified project delivery', 'Residential & commercial'],
        ],
        'sort_order' => 10,
    ],
    'faq' => [
        'title' => 'Questions & Answers',
        'subtitle' => 'Everything You Need to Know',
        'content' => 'Clear answers to the questions clients ask most — so you can plan your project with confidence.',
        'sort_order' => 11,
    ],
    'cta' => [
        'title' => 'Ready When You Are',
        'subtitle' => 'Let\'s Build Something Exceptional',
        'content' => 'Share your vision with our team for confident planning from groundbreaking to handover.',
        'image_url' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&q=80',
        'data' => [
            'button_text' => 'WhatsApp Us',
            'button_text_2' => 'Contact Us',
        ],
        'sort_order' => 12,
    ],
];

$created = [];
foreach ($defaults as $key => $row) {
    if (isset($existing[$key])) {
        continue;
    }
    $model->upsert([
        'page_key' => 'home',
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
}

echo $created === []
    ? "Homepage CMS sections already exist.\n"
    : 'Created homepage CMS sections: ' . implode(', ', $created) . "\n";
