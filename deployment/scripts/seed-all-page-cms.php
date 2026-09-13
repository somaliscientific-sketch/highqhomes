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
        'hero' => [
            'title' => 'Our Services',
            'subtitle' => 'Design, build & deliver with one trusted team',
            'content' => 'From architecture to finishing — clear scope, premium quality, and accountable delivery.',
            'data' => ['Licensed & Insured', 'Transparent Quotes', 'Premium Finishes', 'On-Time Delivery'],
            'sort_order' => 0,
        ],
        'intro' => [
            'title' => 'What we offer',
            'subtitle' => 'Full-service construction',
            'content' => 'Residential, commercial, and finishing services under one team.',
            'sort_order' => 1,
        ],
        'pillars' => [
            'title' => 'Delivery pillars',
            'subtitle' => '',
            'content' => '',
            'data' => [
                ['icon' => 'bi-diagram-3', 'title' => 'Integrated delivery', 'text' => 'Design, construction, and finishing coordinated under one accountable team.'],
                ['icon' => 'bi-clipboard-check', 'title' => 'Clear milestones', 'text' => 'Structured scope, progress updates, and quality inspections at every phase.'],
                ['icon' => 'bi-gem', 'title' => 'Premium standards', 'text' => 'Materials and workmanship selected for durability in local conditions.'],
                ['icon' => 'bi-headset', 'title' => 'Responsive support', 'text' => 'Direct communication from consultation through handover and after-care.'],
            ],
            'sort_order' => 2,
        ],
        'catalog' => [
            'title' => 'Service catalog',
            'subtitle' => 'Everything your project needs',
            'content' => 'Architecture, design, planning, and finishing — delivered with professional oversight from first sketch to final handover.',
            'sort_order' => 3,
        ],
        'scope' => [
            'title' => 'Project scope',
            'subtitle' => 'Built for residential & commercial clients',
            'content' => 'Whether you need a single design discipline or full design-build delivery, we scale our team and timeline to match your project.',
            'data' => [
                'cta_label' => 'View our work',
                'checklist' => [
                    'New builds with integrated architecture and construction',
                    'Renovations, extensions, and premium finishing upgrades',
                    'Site planning and landscape design for optimal land use',
                    'Bespoke interior and furniture solutions',
                ],
                'cards' => [
                    ['icon' => 'bi-building', 'title' => 'Residential builds', 'text' => 'Homes, villas, and gated communities with full design-build support.'],
                    ['icon' => 'bi-shop', 'title' => 'Commercial projects', 'text' => 'Offices, retail, and mixed-use spaces built to operational requirements.'],
                    ['icon' => 'bi-brush', 'title' => 'Finishing & upgrades', 'text' => 'Interior fit-outs, exterior refreshes, and phased renovation work.'],
                ],
            ],
            'sort_order' => 4,
        ],
        'process' => [
            'title' => 'How we deliver',
            'subtitle' => 'A clear path from first meeting to handover',
            'content' => '',
            'data' => [
                ['num' => '01', 'title' => 'Consultation', 'text' => 'Goals, site, budget, and timeline.'],
                ['num' => '02', 'title' => 'Design & Scope', 'text' => 'Drawings and transparent pricing.'],
                ['num' => '03', 'title' => 'Build & QA', 'text' => 'Execution with quality checks.'],
                ['num' => '04', 'title' => 'Handover', 'text' => 'Walkthrough and after-care.'],
            ],
            'sort_order' => 5,
        ],
        'faq' => [
            'title' => 'Questions',
            'subtitle' => 'Service FAQs',
            'content' => '',
            'data' => [
                ['q' => 'Do you offer design-only services?', 'a' => 'Yes. Architecture, exterior, interior, and site planning are available standalone or as integrated packages.', 'icon' => 'bi-rulers'],
                ['q' => 'Can I combine multiple services?', 'a' => 'Most clients choose one team for design, construction, and finishing — we coordinate everything end to end.', 'icon' => 'bi-layers'],
                ['q' => 'How are quotes structured?', 'a' => 'Milestone-based with clear scope, materials, and timeline before work begins.', 'icon' => 'bi-calculator'],
                ['q' => 'Do you handle renovations?', 'a' => 'Yes — new builds, renovations, premium finishing, and phased upgrades.', 'icon' => 'bi-tools'],
            ],
            'sort_order' => 6,
        ],
        'cta' => [
            'title' => 'Ready to start',
            'subtitle' => 'Request a structured quote',
            'content' => 'Tell us about your site and we will respond with clear next steps.',
            'data' => ['cta_primary' => 'Get a quote', 'cta_secondary' => 'Contact us'],
            'sort_order' => 7,
        ],
    ],
    'projects' => [
        'hero' => [
            'title' => 'Portfolio',
            'subtitle' => 'Projects delivered with confidence',
            'content' => 'Residential, commercial, and community builds across Puntland.',
            'sort_order' => 0,
        ],
        'intro' => [
            'title' => 'Our work',
            'subtitle' => 'Built to last. Designed to impress.',
            'content' => '',
            'sort_order' => 1,
        ],
        'pillars' => [
            'title' => 'How we build',
            'subtitle' => 'Delivery principles',
            'content' => '',
            'data' => [
                ['icon' => 'bi-clipboard-data', 'title' => 'Structured delivery', 'text' => 'Milestone planning, progress updates, and quality checks at every phase.'],
                ['icon' => 'bi-gem', 'title' => 'Premium finishes', 'text' => 'Materials and craftsmanship held to standards you can see and measure.'],
                ['icon' => 'bi-shield-check', 'title' => 'Transparent scope', 'text' => 'Clear pricing, documented scope, and accountable communication.'],
                ['icon' => 'bi-geo-alt', 'title' => 'Local expertise', 'text' => 'Deep experience building across Puntland — from Garowe to Bosaso.'],
            ],
            'sort_order' => 2,
        ],
        'catalog' => [
            'title' => 'Project portfolio',
            'subtitle' => 'Explore our builds',
            'content' => 'Filter by category or status to find projects similar to yours.',
            'sort_order' => 3,
        ],
        'approach' => [
            'title' => 'Approach',
            'subtitle' => 'From discovery to handover',
            'content' => '',
            'data' => [
                ['icon' => 'bi-search', 'title' => 'Discovery', 'text' => 'Site review, goals, and feasibility before design begins.'],
                ['icon' => 'bi-rulers', 'title' => 'Design & planning', 'text' => 'Drawings, approvals, and a milestone schedule you can track.'],
                ['icon' => 'bi-hammer', 'title' => 'Build execution', 'text' => 'Disciplined site work with structured QA inspections.'],
                ['icon' => 'bi-key', 'title' => 'Handover', 'text' => 'Final walkthrough, documentation, and after-care support.'],
            ],
            'sort_order' => 4,
        ],
        'cta' => [
            'title' => 'Start a project',
            'subtitle' => 'Share your vision',
            'content' => '',
            'data' => ['cta_primary' => 'Get a quote', 'cta_secondary' => 'Contact us'],
            'sort_order' => 5,
        ],
    ],
    'gallery' => [
        'hero' => [
            'title' => 'Our work',
            'subtitle' => 'Project gallery',
            'content' => 'Photos from builds across Puntland.',
            'sort_order' => 0,
        ],
        'intro' => [
            'title' => 'Visual portfolio',
            'subtitle' => 'Craftsmanship in every frame',
            'content' => '',
            'sort_order' => 1,
        ],
        'highlights' => [
            'title' => 'Highlights',
            'subtitle' => '',
            'content' => '',
            'data' => [
                ['icon' => 'bi-house-heart', 'title' => 'Residential', 'text' => 'Homes and villas finished to live-in quality.'],
                ['icon' => 'bi-building', 'title' => 'Commercial', 'text' => 'Workspaces and retail built for daily use.'],
                ['icon' => 'bi-brush', 'title' => 'Finishes', 'text' => 'Detail shots of coatings, interiors, and handover.'],
            ],
            'sort_order' => 2,
        ],
        'catalog' => [
            'title' => 'Photo collection',
            'subtitle' => 'Explore by category',
            'content' => 'Filter images by project type — click any photo to view full size.',
            'sort_order' => 3,
        ],
        'cta' => [
            'title' => 'Ready to build',
            'subtitle' => 'Start your project',
            'content' => '',
            'data' => ['cta_primary' => 'Get a quote', 'cta_secondary' => 'View projects'],
            'sort_order' => 4,
        ],
    ],
    'paints' => [
        'hero' => [
            'title' => 'Products',
            'subtitle' => 'Premium paints & coatings',
            'content' => 'Professional-grade finishes with expert guidance.',
            'sort_order' => 0,
        ],
        'intro' => [
            'title' => 'Finishing Excellence',
            'subtitle' => 'Colors & Coatings That Endure',
            'content' => '',
            'sort_order' => 1,
        ],
        'benefits' => [
            'title' => 'Benefits',
            'subtitle' => 'Why our coatings last',
            'content' => '',
            'data' => [
                ['icon' => 'bi-shield-check', 'title' => 'Weather resistant', 'text' => 'Formulations selected for heat, dust, and seasonal rain.'],
                ['icon' => 'bi-droplet-half', 'title' => 'Reliable coverage', 'text' => 'Predictable yield so you can order the right quantity.'],
                ['icon' => 'bi-brush', 'title' => 'Premium finish', 'text' => 'Color hold and surface quality that last after handover.'],
                ['icon' => 'bi-headset', 'title' => 'Expert advice', 'text' => 'Application guidance from the same team that builds the site.'],
            ],
            'sort_order' => 2,
        ],
        'catalog' => [
            'title' => 'Product catalog',
            'subtitle' => 'Browse our range',
            'content' => 'Filter by category or brand to find the right coating for your project.',
            'sort_order' => 3,
        ],
        'guide' => [
            'title' => 'Professional guidance',
            'subtitle' => 'Choosing the right coating',
            'content' => 'The right product depends on surface type, exposure, and finish expectations. Our team helps you select coatings that perform in local conditions.',
            'data' => [
                'checklist' => [
                    'Exterior walls — weather-resistant, UV-stable, breathable coatings',
                    'Textured finishes — hide imperfections with durable cementitious systems',
                    'Interior spaces — washable, low-odour finishes for living and commercial areas',
                    'Surface preparation — priming and substrate guidance before application',
                ],
                'cards' => [
                    ['icon' => 'bi-droplet-half', 'title' => 'Coverage & yield', 'text' => 'Calculate m² per unit with our team before ordering.'],
                    ['icon' => 'bi-sun', 'title' => 'Climate suitability', 'text' => 'Products selected for heat, dust, and seasonal rain.'],
                    ['icon' => 'bi-tools', 'title' => 'Application method', 'text' => 'Trowel, roller, or spray — we advise the best approach.'],
                ],
            ],
            'sort_order' => 4,
        ],
        'cta' => [
            'title' => 'Need advice',
            'subtitle' => 'Ask our finishing team',
            'content' => '',
            'data' => ['cta_primary' => 'WhatsApp enquiry', 'cta_secondary' => 'Contact form'],
            'sort_order' => 5,
        ],
    ],
    'contact' => [
        'hero' => [
            'title' => 'Contact Us',
            'subtitle' => 'Let\'s plan your next project',
            'content' => 'Share your site details, drawings, or goals.',
            'sort_order' => 0,
        ],
        'form' => [
            'title' => 'Project inquiry',
            'subtitle' => 'Send us a message',
            'content' => 'Tell us about your build, renovation, or design scope. Your message goes directly to our team inbox.',
            'data' => ['submit_label' => 'Send message'],
            'sort_order' => 1,
        ],
        'process' => [
            'title' => 'What happens next',
            'subtitle' => 'Review, clarify, plan',
            'content' => '',
            'data' => [
                ['num' => '01', 'title' => 'Review', 'text' => 'We assess project type, location, and scope.'],
                ['num' => '02', 'title' => 'Clarify', 'text' => 'We follow up for drawings or key site details.'],
                ['num' => '03', 'title' => 'Plan', 'text' => 'You receive the next step for design or estimate.'],
            ],
            'sort_order' => 2,
        ],
        'map' => [
            'title' => 'Visit us',
            'subtitle' => 'Our office',
            'content' => '',
            'sort_order' => 3,
        ],
        'cta' => [
            'title' => 'Prefer WhatsApp',
            'subtitle' => 'Talk to us now',
            'content' => 'Message the team for a faster first response on quotes and site visits.',
            'data' => ['cta_primary' => 'WhatsApp', 'cta_secondary' => 'Call us'],
            'sort_order' => 4,
        ],
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
