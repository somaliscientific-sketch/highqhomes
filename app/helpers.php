<?php
declare(strict_types=1);

function cmsLogoUrl(): string
{
    $path = PUBLIC_PATH . '/images/logo-cms-icon.png';
    $v = @filemtime($path) ?: time();
    return asset('images/logo-cms-icon.png') . '?v=' . $v;
}

function canManage(): bool
{
    return Auth::can('content.manage');
}

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function asset(string $path): string
{
    return APP_URL . '/public/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    return APP_URL . '/' . ltrim($path, '/');
}

function isHttps(): bool
{
    return Production::isHttps() || str_starts_with(APP_URL, 'https://');
}

function isProduction(): bool
{
    return APP_ENV === 'production' || Production::isLiveDomain();
}

function canonicalUrl(?string $path = null): string
{
    if ($path === null) {
        $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $basePath = parse_url(APP_URL, PHP_URL_PATH) ?: '';
        if ($basePath && str_starts_with($requestPath, $basePath)) {
            $requestPath = substr($requestPath, strlen($basePath)) ?: '/';
        }
        $path = $requestPath;
    }
    $path = '/' . ltrim((string)$path, '/');
    return rtrim(APP_URL, '/') . ($path === '/' ? '/' : $path);
}

function adminLoginPath(): string
{
    return Security::adminLoginPath();
}

function adminLoginUrl(): string
{
    return Security::adminLoginUrl();
}

function uploadUrl(string $path): string
{
    if (!$path) return '';
    if (str_starts_with($path, 'http')) return $path;
    return APP_URL . '/' . ltrim($path, '/');
}

function setting(string $key, string $default = ''): string
{
    static $map = null;
    if ($map === null) {
        try {
            $model = new SettingModel();
            $map = $model->getAllAsMap();
        } catch (\Throwable $e) {
            $map = [];
        }
    }
    return $map[$key] ?? $default;
}

function flash(string $type = 'success'): ?string
{
    return Session::getFlash($type);
}

function csrf(): string
{
    return CSRF::field();
}

function active(string $path, bool $exact = false): string
{
    static $current = null;
    if ($current === null) {
        $c = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $b = parse_url(APP_URL, PHP_URL_PATH) ?? '';
        if ($b && str_starts_with($c, $b)) {
            $c = substr($c, strlen($b));
        }
        $current = rtrim('/' . ltrim($c, '/'), '/') ?: '/';
    }

    $p = rtrim($path, '/') ?: '/';
    if ($exact)     return $current === $p ? 'active' : '';
    if ($p === '/') return $current === '/' ? 'active' : '';
    return str_starts_with($current, $p) ? 'active' : '';
}

function paginate(array $paginationData, string $baseUrl): string
{
    $total   = $paginationData['last_page'];
    $current = $paginationData['current'];
    if ($total <= 1) {
        return '';
    }

    $html = '<nav class="hq-pagination" aria-label="Pagination">';
    for ($i = 1; $i <= $total; $i++) {
        $active = $i === $current ? ' is-active' : '';
        $sep = str_contains($baseUrl, '?') ? '&' : '?';
        $href = $baseUrl . $sep . 'page=' . $i;
        $html .= '<a href="' . e($href) . '" class="hq-pagination__link' . $active . '">' . $i . '</a>';
    }
    $html .= '</nav>';
    return $html;
}

function timeAgo(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60)  return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    return floor($diff / 86400) . 'd ago';
}

function truncate(string $text, int $length = 150): string
{
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

function stars(int $rating): string
{
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $color = $i <= $rating ? '#E88B09' : '#d1d5db';
        $html .= "<i class=\"bi bi-star-fill\" style=\"color:{$color}\"></i>";
    }
    return $html;
}

function statNumber(string $value): string
{
    $num = preg_replace('/[^0-9.]/', '', $value);
    return $num !== '' ? $num : '0';
}

function cmsRow(array $sections, string $key): array
{
    return is_array($sections[$key] ?? null) ? $sections[$key] : [];
}

function cmsRowEnabled(array $sections, string $key, bool $fallback = true): bool
{
    if (!isset($sections[$key])) {
        return $fallback;
    }
    return !empty($sections[$key]['is_enabled']);
}

function cmsText(array $section, string $field, string $fallback = ''): string
{
    $value = trim((string)($section[$field] ?? ''));
    return $value !== '' ? $value : $fallback;
}

function cmsMediaUrl(?string $path, string $fallback = ''): string
{
    $path = trim((string)$path);
    if ($path === '') {
        return $fallback;
    }
    return str_starts_with($path, 'http') ? $path : uploadUrl($path);
}

function cmsMap(array $section): array
{
    $data = $section['data'] ?? null;
    return is_array($data) && !array_is_list($data) ? $data : [];
}

function cmsList(array $section): array
{
    $data = $section['data'] ?? null;
    if (!is_array($data) || $data === []) {
        return [];
    }
    if (array_is_list($data)) {
        return $data;
    }
    $items = $data['items'] ?? null;
    return is_array($items) && array_is_list($items) ? $items : [];
}

function mediaPathUrl(?string $path, string $fallback = ''): string
{
    $path = trim((string)$path);
    if ($path === '') {
        return $fallback;
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    $normalized = ltrim((string)preg_replace('#^/?public/#', '', $path), '/');
    if (str_starts_with($normalized, 'images/')) {
        return asset($normalized);
    }
    return uploadUrl($path);
}

function projectImageUrl(array $project, string $fallback = ''): string
{
    $fallback = $fallback ?: asset('images/builds/grey-villa-evening.jpg');
    if (empty($project['featured_image'])) {
        return $fallback;
    }
    return mediaPathUrl((string)$project['featured_image'], $fallback);
}

function projectCategoryLabel(?string $category): string
{
    return ucwords(str_replace('_', ' ', (string)($category ?: 'construction')));
}

function projectStatusLabel(?string $status): string
{
    return match ((string)($status ?: 'completed')) {
        'in_progress' => 'In progress',
        'planned'     => 'Upcoming',
        'completed'   => 'Completed',
        default       => ucwords(str_replace('_', ' ', (string)$status)),
    };
}

function isUpcomingProject(array $project): bool
{
    return ($project['status'] ?? '') === 'planned';
}

/**
 * On-site HighQ Homes portfolio used when the projects table is empty.
 *
 * @return list<array<string, mixed>>
 */
function projectShowcaseItems(?string $category = null, ?string $status = null): array
{
    $rows = [
        [
            'id' => 0, 'title' => 'Desert Courtyard Villa', 'slug' => 'desert-courtyard-villa',
            'category' => 'residential', 'status' => 'planned', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Two-storey courtyard villa', 'project_year' => 2026,
            'short_description' => 'A forthcoming two-storey villa for Garowe — terracotta, board-formed concrete, and deep shade — shown here as the design visualization before construction.',
            'description' => '<p>This is the next HighQ Homes residential design: a two-storey courtyard villa composed in terracotta, board-formed concrete, and white cantilevered volumes, with a vertical light slot and shaded balconies.</p><p>The image on this page is a design visualization, not a completed site photograph. It shows the architecture we are preparing to deliver in Garowe — climate-aware openings, a clear entrance sequence, and a finish palette that holds in Puntland sun and dust.</p><p>If you are planning a home like this, we will walk the plot, confirm what the land can support, and turn this language of form into a written programme — structure, envelope, and finishing under one team.</p>',
            'featured_image' => 'images/builds/upcoming-desert-villa.jpg',
            'gallery_images' => [],
            'is_featured' => 0, 'is_published' => 1, 'sort_order' => 0,
        ],
        [
            'id' => 0, 'title' => 'Evening Family Villa', 'slug' => 'evening-family-villa',
            'category' => 'residential', 'status' => 'completed', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Two-storey gated villa', 'project_year' => 2025,
            'short_description' => 'A two-storey grey villa with a gated compound, warm evening lighting, and a finished façade built for family living in Garowe.',
            'description' => '<p>This Garowe family villa was delivered as a complete residential build — structure, envelope, and finishing under one HighQ Homes team.</p><p>The compound is gated, the façade is finished in a durable grey render, and the two-storey plan is organised for privacy at the street and comfortable living inside. Lighting, boundary walls, and the entrance sequence were treated as part of the architecture, not as afterthoughts.</p><p>The result is a home that reads clearly at night and holds its finish in Puntland’s climate — a standard we apply from first setting-out through handover.</p>',
            'featured_image' => 'images/builds/grey-villa-evening.jpg',
            'gallery_images' => ['images/builds/grey-villa.jpg'],
            'is_featured' => 1, 'is_published' => 1, 'sort_order' => 1,
        ],
        [
            'id' => 0, 'title' => 'Twin Family Residences', 'slug' => 'twin-family-residences',
            'category' => 'residential', 'status' => 'completed', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Paired two-storey homes', 'project_year' => 2025,
            'short_description' => 'A pair of matching two-storey residences sharing a composed street frontage, with independent living for two households.',
            'description' => '<p>Twin residences give two families the presence of a single composed building while keeping each home independent.</p><p>HighQ Homes delivered the pair with aligned floor levels, a shared material language, and separate access so the street elevation stays calm and the living remains private. Windows, roof edges, and the boundary treatment were coordinated so neither house reads as an add-on.</p><p>This is a practical model for family plots in Garowe: one construction programme, two complete homes, one accountable finish standard.</p>',
            'featured_image' => 'images/builds/twin-residences.jpg',
            'gallery_images' => [],
            'is_featured' => 1, 'is_published' => 1, 'sort_order' => 2,
        ],
        [
            'id' => 0, 'title' => 'Modern Courtyard Villa', 'slug' => 'modern-courtyard-villa',
            'category' => 'residential', 'status' => 'completed', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Two-storey villa', 'project_year' => 2024,
            'short_description' => 'A contemporary two-storey villa with a strong street elevation, deep openings, and a courtyard plan for shade and privacy.',
            'description' => '<p>This villa is planned around shade, privacy, and a clear modern elevation — a courtyard sequence that cools the house and keeps family life off the street.</p><p>We delivered the structure, openings, and exterior finish as one package, with careful attention to proportions at the gate, the first-floor balcony line, and the roof edge. Interior rooms follow the courtyard so daylight arrives without exposing the home.</p><p>It is a HighQ Homes residential standard: contemporary form, local climate sense, and a finish you can inspect at handover.</p>',
            'featured_image' => 'images/builds/modern-villa.jpg',
            'gallery_images' => [],
            'is_featured' => 1, 'is_published' => 1, 'sort_order' => 3,
        ],
        [
            'id' => 0, 'title' => 'Stone Compound Residence', 'slug' => 'stone-compound-residence',
            'category' => 'residential', 'status' => 'completed', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Gated family compound', 'project_year' => 2024,
            'short_description' => 'A family residence set behind a stone compound wall, with a composed gate, landscaped approach, and durable exterior finishes.',
            'description' => '<p>The stone compound is the first room of this house: a gated approach, a planted setback, and a residence that sits calmly behind the wall.</p><p>HighQ Homes built the home and the compound as one project — masonry, openings, and exterior finishes specified to last. The gate and boundary are not temporary site works; they are part of the architecture clients see every day.</p><p>This is typical of our Garowe residential work: a secure compound, a finished elevation, and a family plan that stays private from the street.</p>',
            'featured_image' => 'images/builds/stone-residence.jpg',
            'gallery_images' => [],
            'is_featured' => 0, 'is_published' => 1, 'sort_order' => 4,
        ],
        [
            'id' => 0, 'title' => 'Two-Storey Family Residence', 'slug' => 'two-storey-family-residence',
            'category' => 'residential', 'status' => 'completed', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Two-storey residence', 'project_year' => 2024,
            'short_description' => 'A completed two-storey family home with a warm rendered façade, balanced openings, and a clear street presence.',
            'description' => '<p>This two-storey residence is a straightforward family house, finished with care: a warm render, balanced window rhythm, and a roof line that sits cleanly on the plot.</p><p>We delivered it from structure to finishing under one programme — no split between “builder” and “finisher”. Interior rooms are planned for daily family use; the exterior is specified for sun, dust, and long-term maintenance.</p><p>It is the kind of home HighQ Homes is known for in Garowe: honest construction, a composed elevation, and a handover you can walk with a checklist.</p>',
            'featured_image' => 'images/builds/yellow-residence.jpg',
            'gallery_images' => [],
            'is_featured' => 0, 'is_published' => 1, 'sort_order' => 5,
        ],
        [
            'id' => 0, 'title' => 'Green Roof Family Home', 'slug' => 'green-roof-family-home',
            'category' => 'residential', 'status' => 'completed', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Two-storey residence', 'project_year' => 2023,
            'short_description' => 'A two-storey family home with a distinctive green roof, deep eaves, and a finished compound that reads clearly from the street.',
            'description' => '<p>The green roof is the signature of this house — a practical shade device and a strong identity on the street.</p><p>HighQ Homes delivered the residence with coordinated eaves, openings, and exterior colour so the roof belongs to the building rather than sitting on it. The compound, approach, and façade were finished together so the first impression matches the completed interior.</p><p>Clients looking for a family home with a distinct profile — not a generic box — will recognise the standard: design intent carried through construction and finishing.</p>',
            'featured_image' => 'images/builds/green-roof-residence.jpg',
            'gallery_images' => [],
            'is_featured' => 0, 'is_published' => 1, 'sort_order' => 6,
        ],
        [
            'id' => 0, 'title' => 'Gated Villa Entrance', 'slug' => 'gated-villa-entrance',
            'category' => 'residential', 'status' => 'completed', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Villa compound & gate', 'project_year' => 2025,
            'short_description' => 'A custom gate and compound wall for a two-storey villa — the street face of a finished HighQ Homes residence.',
            'description' => '<p>The entrance is where a residential project meets the city. This gate and compound wall were designed and built with the villa, not added later.</p><p>We coordinated masonry, metalwork, and the house elevation so the street composition is one piece: solid boundary, a considered opening, and a two-storey home that sits correctly behind it. Daylight on the façade shows the same finish quality as the evening view of the related villa.</p><p>HighQ Homes treats compound works as architecture — security, proportion, and craft in a single delivery.</p>',
            'featured_image' => 'images/builds/grey-villa.jpg',
            'gallery_images' => ['images/builds/grey-villa-evening.jpg'],
            'is_featured' => 0, 'is_published' => 1, 'sort_order' => 7,
        ],
        [
            'id' => 0, 'title' => 'Residential Build in Progress', 'slug' => 'residential-build-in-progress',
            'category' => 'residential', 'status' => 'in_progress', 'location' => 'Garowe, Puntland',
            'client_name' => 'Private client', 'project_area' => 'Active residential site', 'project_year' => 2026,
            'short_description' => 'An active HighQ Homes residential site — structure rising with programmed inspections before each stage is signed off.',
            'description' => '<p>This active build is how our completed homes begin: a cleared plot, a set-out, and a structure rising under a written programme.</p><p>Clients receive milestone updates and quality checks before the next stage is released. Scaffolding, blockwork, and site access are managed so the finished house can meet the same standard as the villas already in this portfolio.</p><p>If you are planning a home in Garowe, this is the delivery model — visible progress, accountable stages, and a finish that will photograph like the completed projects beside it.</p>',
            'featured_image' => 'images/builds/active-build.jpg',
            'gallery_images' => [],
            'is_featured' => 1, 'is_published' => 1, 'sort_order' => 8,
        ],
    ];

    if ($category) {
        $rows = array_values(array_filter($rows, static fn(array $row): bool => ($row['category'] ?? '') === $category));
    }
    if ($status) {
        $rows = array_values(array_filter($rows, static fn(array $row): bool => ($row['status'] ?? '') === $status));
    }

    return $rows;
}

function serviceImageUrl(array $service, string $fallback = ''): string
{
    $fallback = $fallback ?: asset('images/builds/modern-villa.jpg');
    $image = (string)($service['image'] ?? '');
    if ($image === '' || str_contains($image, 'unsplash.com')) {
        return $fallback;
    }
    return mediaPathUrl($image, $fallback);
}

function isStaleServiceSlug(string $slug): bool
{
    static $stale = [
        'architecture',
        'exterior-design',
        'landscape-design',
        'site-planning',
        'interior-design',
        'furniture-design',
        'project-consulting',
        'construction',
        'renovation',
    ];

    return in_array($slug, $stale, true);
}

/**
 * Core HighQ Homes services used when the services table is empty.
 *
 * @return list<array<string, mixed>>
 */
function serviceShowcaseItems(): array
{
    return [
        [
            'id' => 101,
            'title' => 'Residential construction',
            'slug' => 'residential-construction',
            'short_description' => 'Family villas and two-storey homes in Garowe — structure, envelope, and finishing under one team.',
            'description' => '<p>We build family homes from foundation to handover: gated villas, two-storey residences, and compounds planned for how people live in Puntland.</p><p>One HighQ Homes team owns the structure, the façade, and the finish so nothing is split between trades. You get a written scope, milestone updates, and a walkthrough you can inspect before the keys are handed over.</p>',
            'icon' => 'bi-house-heart',
            'image' => 'images/builds/grey-villa-evening.jpg',
            'is_featured' => 1,
            'is_published' => 1,
            'sort_order' => 1,
        ],
        [
            'id' => 102,
            'title' => 'Architecture & planning',
            'slug' => 'architecture-planning',
            'short_description' => 'Drawings, plot layout, and a buildable plan before the first block is laid.',
            'description' => '<p>Architecture here is practical: shade, privacy, a calm street elevation, and a plan the site can support.</p><p>We prepare drawings, openings, and a milestone schedule so construction starts with a clear brief — not a sketch that changes on site.</p>',
            'icon' => 'bi-rulers',
            'image' => 'images/builds/modern-villa.jpg',
            'is_featured' => 1,
            'is_published' => 1,
            'sort_order' => 2,
        ],
        [
            'id' => 103,
            'title' => 'Finishing & interiors',
            'slug' => 'finishing-interiors',
            'short_description' => 'Durable interiors and exterior finishes specified for Puntland sun, dust, and daily family use.',
            'description' => '<p>Finishing is not an afterthought. Render, openings, joinery, and colour are specified so the completed house matches the drawing and lasts in local conditions.</p><p>We can deliver finishing as part of a new build or as a phased upgrade on an existing home.</p>',
            'icon' => 'bi-brush',
            'image' => 'images/builds/yellow-residence.jpg',
            'is_featured' => 1,
            'is_published' => 1,
            'sort_order' => 3,
        ],
        [
            'id' => 104,
            'title' => 'Compounds & site works',
            'slug' => 'compounds-site-works',
            'short_description' => 'Gates, boundary walls, and the approach that makes a house feel finished from the street.',
            'description' => '<p>The compound is the first room of the house. We design and build gates, walls, and the approach with the residence — not as leftover site works.</p><p>Security, proportion, and craft sit in one delivery so the street face matches the home behind it.</p>',
            'icon' => 'bi-house-lock',
            'image' => 'images/builds/stone-residence.jpg',
            'is_featured' => 0,
            'is_published' => 1,
            'sort_order' => 4,
        ],
        [
            'id' => 105,
            'title' => 'Multi-unit homes',
            'slug' => 'multi-unit-homes',
            'short_description' => 'Paired residences and family plots with independent living and a composed street frontage.',
            'description' => '<p>Twin houses and multi-unit plots give two households a single composed building while keeping each home independent.</p><p>We align levels, materials, and access so neither house reads as an add-on — one programme, two complete homes.</p>',
            'icon' => 'bi-buildings',
            'image' => 'images/builds/twin-residences.jpg',
            'is_featured' => 0,
            'is_published' => 1,
            'sort_order' => 5,
        ],
        [
            'id' => 106,
            'title' => 'Project delivery',
            'slug' => 'project-delivery',
            'short_description' => 'Milestone control, site updates, and quality checks from set-out to handover.',
            'description' => '<p>Active builds are how completed homes begin: a set-out, a programme, and inspections before the next stage is released.</p><p>Clients receive progress you can see and a finish standard that matches the villas already in our portfolio.</p>',
            'icon' => 'bi-clipboard-check',
            'image' => 'images/builds/active-build.jpg',
            'is_featured' => 0,
            'is_published' => 1,
            'sort_order' => 6,
        ],
    ];
}

function galleryImageUrl(array $item, string $fallback = ''): string
{
    $fallback = $fallback ?: asset('images/builds/grey-villa-evening.jpg');
    $image = (string)($item['image'] ?? '');
    if ($image === '' || str_contains($image, 'unsplash.com')) {
        return $fallback;
    }
    return mediaPathUrl($image, $fallback);
}

function isStaleGalleryImage(?string $image): bool
{
    $image = trim((string)$image);
    return $image === '' || str_contains($image, 'unsplash.com');
}

/**
 * Eight homepage hero slides: seven site photos plus the on-plot video.
 *
 * @return list<array<string, mixed>>
 */
function heroSliderCatalog(): array
{
    $base = [
        'button_text' => 'Get a quote',
        'button_link' => '/contact',
        'button_text_2' => 'View our work',
        'button_link_2' => '/projects',
        'image_focus' => 'center',
        'overlay_opacity' => 0.48,
        'is_published' => 1,
        'show_description' => 1,
        'transition_type' => 'inherit',
        'video' => null,
    ];

    $slides = [
        [
            'title' => 'Work you can inspect in Garowe.',
            'subtitle' => 'Active structure on the plot',
            'description' => 'On-site video of brickwork and openings going up — honest progress before paint and fittings.',
            'image' => 'images/builds/hero-site-poster.jpg',
            'video' => 'images/builds/hero-site-reel.mp4',
            'badge_text' => 'Garowe · On-site video',
            'image_focus' => 'right',
            'autoplay_duration' => 8,
            'transition_type' => 'fade',
        ],
        [
            'title' => 'A house you can walk at night.',
            'subtitle' => 'Finished residential build',
            'description' => 'Warm balcony light on a completed HighQ Homes street front — visit the work before you decide.',
            'image' => 'images/builds/hero-night-front.jpg',
            'badge_text' => 'Garowe · Night',
            'image_focus' => 'center',
        ],
        [
            'title' => 'The same street in daylight.',
            'subtitle' => 'Completed family home',
            'description' => 'Gate, balcony, and finish you can inspect on the plot — not a stock library.',
            'image' => 'images/builds/hero-amber-villa.jpg',
            'badge_text' => 'Garowe · Street villa',
        ],
        [
            'title' => 'A compound that reads as one home.',
            'subtitle' => 'Residential compound',
            'description' => 'Stone wall, courtyard, and a house planned for the plot and the climate.',
            'image' => 'images/builds/hero-stone-home.jpg',
            'badge_text' => 'Garowe · Compound',
        ],
        [
            'title' => 'Built for the plot and the climate.',
            'subtitle' => 'Family residence',
            'description' => 'A cream two-storey home with a walled yard — photographed on a HighQ Homes street.',
            'image' => 'images/builds/hero-cream-house.jpg',
            'badge_text' => 'Garowe · Residence',
        ],
        [
            'title' => 'Clean lines. A finish you can see.',
            'subtitle' => 'Modern villa elevation',
            'description' => 'A composed street elevation with a courtyard wall — one team from drawings to the gate.',
            'image' => 'images/builds/hero-courtyard-villa.jpg',
            'badge_text' => 'Garowe · Elevation',
            'image_focus' => 'bottom',
        ],
        [
            'title' => 'Progress you can walk on site.',
            'subtitle' => 'Residential build in progress',
            'description' => 'Roof, openings, and finishing underway — the crew on the plot, not a sales desk.',
            'image' => 'images/builds/hero-green-roof.jpg',
            'badge_text' => 'Garowe · In progress',
        ],
        [
            'title' => 'Two homes, one standard.',
            'subtitle' => 'Paired family residences',
            'description' => 'Twin street frontage with a shared compound — the same craft on both plots.',
            'image' => 'images/builds/hero-twin-homes.jpg',
            'badge_text' => 'Garowe · Twin homes',
        ],
    ];

    return array_map(static fn(array $slide): array => array_merge($base, $slide), $slides);
}

function isStaleHeroSlideImage(?string $image): bool
{
    $image = trim((string)$image);
    return $image === '' || str_contains($image, 'unsplash.com');
}

function heroSlideImageUrl(array $slide, int $index = 0): string
{
    $catalog = heroSliderCatalog();
    $fallbackPath = $catalog[$index % count($catalog)]['image'] ?? 'images/builds/hero-night-front.jpg';
    $fallback = asset($fallbackPath);
    $image = trim((string)($slide['image'] ?? ''));
    if (isStaleHeroSlideImage($image)) {
        return $fallback;
    }
    return mediaPathUrl($image, $fallback);
}

function heroSlideVideoUrl(array $slide): string
{
    $video = trim((string)($slide['video'] ?? ''));
    if ($video === '') {
        return '';
    }
    return mediaPathUrl($video);
}

function heroSlideAspect(array $slide): string
{
    if (trim((string)($slide['video'] ?? '')) !== '') {
        return 'landscape';
    }
    $image = strtolower((string)($slide['image'] ?? ''));
    foreach (['hero-night-front', 'hero-courtyard-villa'] as $key) {
        if (str_contains($image, $key)) {
            return 'portrait';
        }
    }
    return 'landscape';
}

/**
 * @return array{desk: string, mobile: string}
 */
function heroSlideFocus(array $slide, int $index = 0): array
{
    $image = strtolower((string)($slide['image'] ?? ''));
    $framed = [
        'hero-night-front' => ['desk' => '50% 42%', 'mobile' => '50% 38%'],
        'hero-amber-villa' => ['desk' => '46% 48%', 'mobile' => '48% 42%'],
        'hero-stone-home' => ['desk' => '48% 58%', 'mobile' => '50% 52%'],
        'hero-cream-house' => ['desk' => '42% 46%', 'mobile' => '48% 42%'],
        'hero-courtyard-villa' => ['desk' => '50% 72%', 'mobile' => '50% 68%'],
        'hero-green-roof' => ['desk' => '48% 52%', 'mobile' => '50% 48%'],
        'hero-twin-homes' => ['desk' => '50% 55%', 'mobile' => '50% 48%'],
        'hero-site-poster' => ['desk' => '78% 44%', 'mobile' => '72% 40%'],
        'hero-site-01' => ['desk' => '82% 48%', 'mobile' => '78% 40%'],
        'hero-street-villa' => ['desk' => '76% 54%', 'mobile' => '50% 42%'],
        'hero-night-villa' => ['desk' => '62% 32%', 'mobile' => '54% 28%'],
        'hero-compound-villa' => ['desk' => '68% 40%', 'mobile' => '50% 36%'],
    ];
    foreach ($framed as $key => $pos) {
        if (str_contains($image, $key)) {
            $raw = trim((string)($slide['image_focus'] ?? ''));
            if (str_contains($raw, '%')) {
                return ['desk' => $raw, 'mobile' => $pos['mobile']];
            }
            if ($raw === 'top') {
                return ['desk' => '70% 28%', 'mobile' => '50% 22%'];
            }
            if ($raw === 'bottom') {
                return ['desk' => '70% 72%', 'mobile' => '50% 58%'];
            }
            return $pos;
        }
    }

    $map = [
        'center' => ['desk' => '70% 42%', 'mobile' => '50% 34%'],
        'top' => ['desk' => '50% 28%', 'mobile' => '50% 22%'],
        'bottom' => ['desk' => '50% 72%', 'mobile' => '50% 58%'],
        'right' => ['desk' => '78% 46%', 'mobile' => '62% 38%'],
        'left' => ['desk' => '28% 46%', 'mobile' => '40% 38%'],
    ];
    $raw = trim((string)($slide['image_focus'] ?? 'center'));
    if (str_contains($raw, '%')) {
        return ['desk' => $raw, 'mobile' => $raw];
    }
    return $map[$raw] ?? $map['center'];
}

function isStaleHeroSlideCopy(string $title): bool
{
    $title = strtolower(trim($title));
    $stale = [
        'build your dream home with highq homes',
        'modern architecture. timeless design.',
        'built with quality. designed to last.',
        'your vision. our expertise.',
        'innovative homes built for garowe',
        'quality that lasts',
        'built with integrity',
        'a house you can walk in garowe.',
        'the same craft, after dark.',
        'a compound that reads as one home.',
        'work you can inspect in garowe.',
        'brickwork rising on the plot.',
        'a house taking shape today.',
        'see the walls before the paint.',
        'built on this street, not stock.',
        'openings, masonry, and clear progress.',
        'the same plot, a closer look.',
        'from the ground up in garowe.',
    ];
    return in_array($title, $stale, true);
}

/**
 * On-site HighQ Homes photos used when the gallery table is empty or still stock.
 *
 * @return list<array<string, mixed>>
 */
function galleryShowcaseItems(?string $category = null): array
{
    $rows = [
        [
            'id' => 201,
            'title' => 'Evening family villa',
            'description' => 'Two-storey gated villa photographed at dusk in Garowe.',
            'image' => 'images/builds/grey-villa-evening.jpg',
            'category' => 'residential',
            'alt_text' => 'Grey two-storey family villa with evening lighting in Garowe',
            'is_published' => 1,
            'sort_order' => 1,
        ],
        [
            'id' => 202,
            'title' => 'Twin family residences',
            'description' => 'Paired two-storey homes sharing a composed street frontage.',
            'image' => 'images/builds/twin-residences.jpg',
            'category' => 'residential',
            'alt_text' => 'Twin two-storey residences in Garowe',
            'is_published' => 1,
            'sort_order' => 2,
        ],
        [
            'id' => 203,
            'title' => 'Modern courtyard villa',
            'description' => 'Contemporary elevation with deep openings and a courtyard plan.',
            'image' => 'images/builds/modern-villa.jpg',
            'category' => 'residential',
            'alt_text' => 'Modern two-storey courtyard villa in Garowe',
            'is_published' => 1,
            'sort_order' => 3,
        ],
        [
            'id' => 204,
            'title' => 'Stone compound residence',
            'description' => 'Family home behind a stone wall, gate, and landscaped approach.',
            'image' => 'images/builds/stone-residence.jpg',
            'category' => 'exterior',
            'alt_text' => 'Stone compound wall and residence in Garowe',
            'is_published' => 1,
            'sort_order' => 4,
        ],
        [
            'id' => 205,
            'title' => 'Two-storey family home',
            'description' => 'Warm rendered façade and balanced openings on a finished plot.',
            'image' => 'images/builds/yellow-residence.jpg',
            'category' => 'residential',
            'alt_text' => 'Two-storey family residence with warm render in Garowe',
            'is_published' => 1,
            'sort_order' => 5,
        ],
        [
            'id' => 206,
            'title' => 'Green roof family home',
            'description' => 'Distinctive green roof, deep eaves, and a finished compound.',
            'image' => 'images/builds/green-roof-residence.jpg',
            'category' => 'residential',
            'alt_text' => 'Two-storey home with green roof in Garowe',
            'is_published' => 1,
            'sort_order' => 6,
        ],
        [
            'id' => 207,
            'title' => 'Gated villa entrance',
            'description' => 'Custom gate and compound wall built with the house.',
            'image' => 'images/builds/grey-villa.jpg',
            'category' => 'exterior',
            'alt_text' => 'Gated entrance and compound wall of a villa in Garowe',
            'is_published' => 1,
            'sort_order' => 7,
        ],
        [
            'id' => 208,
            'title' => 'Build in progress',
            'description' => 'Active residential site with inspections before each stage.',
            'image' => 'images/builds/active-build.jpg',
            'category' => 'construction',
            'alt_text' => 'HighQ Homes residential construction site in Garowe',
            'is_published' => 1,
            'sort_order' => 8,
        ],
        [
            'id' => 209,
            'title' => 'Street family residence',
            'description' => 'Completed home photographed from the street in Garowe.',
            'image' => 'images/builds/about-residence.jpg',
            'category' => 'residential',
            'alt_text' => 'Completed family residence photographed from the street in Garowe',
            'is_published' => 1,
            'sort_order' => 9,
        ],
    ];

    if ($category) {
        $rows = array_values(array_filter(
            $rows,
            static fn(array $row): bool => ($row['category'] ?? '') === $category
        ));
    }

    return $rows;
}

function galleryCategoryLabel(?string $category): string
{
    if ($category === null || $category === '') {
        return 'General';
    }

    $labels = [
        'residential'  => 'Residential',
        'commercial'   => 'Commercial',
        'mosque'       => 'Mosque',
        'interior'     => 'Interior',
        'exterior'     => 'Exterior',
        'construction' => 'Construction',
        'finishing'    => 'Finishing',
        'landscape'    => 'Landscape',
        'industrial'   => 'Industrial',
        'infrastructure' => 'Infrastructure',
    ];

    return $labels[$category] ?? ucfirst(str_replace(['_', '-'], ' ', $category));
}

function teamImageUrl(array $member, int $index = 0): string
{
    if (!empty($member['image'])) {
        $image = $member['image'];
        return str_starts_with($image, 'http') ? $image : uploadUrl($image);
    }

    $fallbacks = [
        'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=900&q=80',
        'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=900&q=80',
        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=900&q=80',
        'https://images.unsplash.com/photo-1519348331104-85de3239b9ab?w=900&q=80',
    ];

    return $fallbacks[$index % count($fallbacks)];
}

function brandIconMeta(string $variant, array $settings): array
{
    if ($variant === 'footer') {
        $custom = trim($settings['footer_logo'] ?? '');
        if ($custom !== '') {
            return [uploadUrl($custom), 'hq-brand__icon-img hq-brand__icon-img--footer'];
        }
        $path = PUBLIC_PATH . '/images/logo-footer-icon.png';
        $v = @filemtime($path) ?: time();
        return [asset('images/logo-footer-icon.png') . '?v=' . $v, 'hq-brand__icon-img hq-brand__icon-img--footer'];
    }

    if ($variant === 'mobile') {
        $cmsLogo = trim($settings['logo'] ?? '');
        if ($cmsLogo !== '') {
            return [uploadUrl($cmsLogo), 'hq-brand__icon-img hq-brand__icon-img--light'];
        }
        $path = PUBLIC_PATH . '/images/logo-header-icon.png';
        if (is_file($path)) {
            $v = @filemtime($path) ?: time();
            return [asset('images/logo-header-icon.png') . '?v=' . $v, 'hq-brand__icon-img hq-brand__icon-img--header'];
        }
        $svg = PUBLIC_PATH . '/images/logo-highq-white.svg';
        if (is_file($svg)) {
            $v = @filemtime($svg) ?: time();
            return [asset('images/logo-highq-white.svg') . '?v=' . $v, 'hq-brand__icon-img hq-brand__icon-img--header'];
        }
        return [asset('images/logo-header-icon.png'), 'hq-brand__icon-img hq-brand__icon-img--header hq-brand__icon-img--light'];
    }

    if ($variant === 'header') {
        $cmsLogo = trim($settings['logo'] ?? '');
        if ($cmsLogo !== '') {
            return [uploadUrl($cmsLogo), 'hq-brand__icon-img hq-brand__icon-img--header'];
        }
        $path = PUBLIC_PATH . '/images/logo-header-icon.png';
        $v = @filemtime($path) ?: time();
        return [asset('images/logo-header-icon.png') . '?v=' . $v, 'hq-brand__icon-img hq-brand__icon-img--header'];
    }

    $cmsLogo = trim($settings['logo'] ?? '');
    if ($cmsLogo !== '') {
        return [uploadUrl($cmsLogo), 'hq-brand__icon-img'];
    }

    $path = PUBLIC_PATH . '/images/logo-highq.png';
    $v = @filemtime($path) ?: time();
    return [asset('images/logo-highq.png') . '?v=' . $v, 'hq-brand__icon-img'];
}

function faviconHref(array $settings = []): string
{
    $fav = trim($settings['favicon'] ?? '');
    if ($fav === '') {
        $fav = setting('favicon', '');
    }
    return $fav !== '' ? uploadUrl($fav) : asset('favicon.svg');
}

function brandThemeStyle(array $settings = []): string
{
    $navy = trim($settings['primary_color'] ?? '');
    $gold = trim($settings['accent_color'] ?? '');
    $rules = [];
    if ($navy !== '' && preg_match('/^#[0-9A-Fa-f]{6}$/', $navy)) {
        $rules[] = '--hq-navy:' . $navy;
        $rules[] = '--hq-text:' . $navy;
    }
    if ($gold !== '' && preg_match('/^#[0-9A-Fa-f]{6}$/', $gold)) {
        $rules[] = '--hq-orange:' . $gold;
    }
    return $rules ? ':root{' . implode(';', $rules) . ';}' : '';
}

function menuUrl(string $url): string
{
    return str_starts_with($url, 'http') ? $url : url(ltrim($url, '/'));
}

function publicPagePath(string $url): string
{
    $path = parse_url($url, PHP_URL_PATH);
    $path = strtolower(trim((string)($path !== null && $path !== '' ? $path : $url), '/'));
    return $path === '' ? '/' : $path;
}

function isHiddenPublicPage(string $url): bool
{
    return in_array(publicPagePath($url), ['gallery', 'contact'], true);
}

function siteQuoteHref(array $settings = [], string $message = 'Hello HighQ Homes, I would like to discuss a project'): string
{
    $phone = $settings['whatsapp'] ?? $settings['phone'] ?? '252907734667';
    $wa = preg_replace('/[^0-9]/', '', (string)$phone) ?: '252907734667';
    return 'https://wa.me/' . $wa . '?text=' . rawurlencode($message);
}

function remapHiddenPublicHref(string $url, array $settings = []): string
{
    $path = publicPagePath($url);
    if ($path === 'gallery') {
        return url('projects');
    }
    if ($path === 'contact') {
        return siteQuoteHref($settings);
    }
    return str_starts_with($url, 'http') ? $url : (str_starts_with($url, '/') ? menuUrl($url) : $url);
}

function navMenus(string $location = 'primary'): array
{
    static $cache = [];

    if (isset($cache[$location])) {
        return $cache[$location];
    }

    try {
        $menus = (new MenuModel())->getPublished($location);
    } catch (\Throwable $e) {
        $menus = [];
    }

    if (empty($menus) && $location === 'footer') {
        try {
            $menus = (new MenuModel())->getPublished('primary');
        } catch (\Throwable $e) {
            $menus = [];
        }
    }

    if (empty($menus)) {
        $menus = $location === 'footer'
            ? [
                ['label' => 'Home', 'url' => '/', 'target' => '_self'],
                ['label' => 'About', 'url' => '/about', 'target' => '_self'],
                ['label' => 'Services', 'url' => '/services', 'target' => '_self'],
                ['label' => 'Projects', 'url' => '/projects', 'target' => '_self'],
            ]
            : [
                ['label' => 'Home', 'url' => '/', 'target' => '_self'],
                ['label' => 'About', 'url' => '/about', 'target' => '_self'],
                ['label' => 'Services', 'url' => '/services', 'target' => '_self'],
                ['label' => 'Projects', 'url' => '/projects', 'target' => '_self'],
            ];
    }

    $seen = [];
    $menus = array_values(array_filter($menus, static function (array $menu) use (&$seen): bool {
        $path = publicPagePath((string)($menu['url'] ?? ''));
        if (isHiddenPublicPage($path)) {
            return false;
        }
        if (isset($seen[$path])) {
            return false;
        }
        $seen[$path] = true;
        return true;
    }));

    return $cache[$location] = $menus;
}

function pageSections(string $pageKey): array
{
    static $cache = [];
    if (isset($cache[$pageKey])) {
        return $cache[$pageKey];
    }
    try {
        $model = new PageSectionModel();
        return $cache[$pageKey] = $model->getByPage($pageKey);
    } catch (\Throwable $e) {
        return $cache[$pageKey] = [];
    }
}

function cmsSection(string $pageKey, string $sectionKey): ?array
{
    $sections = pageSections($pageKey);
    return $sections[$sectionKey] ?? null;
}

function cmsSectionEnabled(string $pageKey, string $sectionKey, bool $default = true): bool
{
    $sec = cmsSection($pageKey, $sectionKey);
    if ($sec === null) {
        return $default;
    }
    return !empty($sec['is_enabled']);
}

function cmsSectionTitle(string $pageKey, string $sectionKey, string $default = ''): string
{
    $sec = cmsSection($pageKey, $sectionKey);
    return trim((string)($sec['title'] ?? '')) ?: $default;
}

function cmsSectionSubtitle(string $pageKey, string $sectionKey, string $default = ''): string
{
    $sec = cmsSection($pageKey, $sectionKey);
    return trim((string)($sec['subtitle'] ?? '')) ?: $default;
}

function cmsSectionContent(string $pageKey, string $sectionKey, string $default = ''): string
{
    $sec = cmsSection($pageKey, $sectionKey);
    return trim((string)($sec['content'] ?? '')) ?: $default;
}

function cmsSectionImage(string $pageKey, string $sectionKey, string $default = ''): string
{
    $sec = cmsSection($pageKey, $sectionKey);
    $url = trim((string)($sec['image_url'] ?? ''));
    if ($url === '') {
        return $default;
    }
    return str_starts_with($url, 'http') ? $url : uploadUrl($url);
}

function cmsSectionData(string $pageKey, string $sectionKey, mixed $default = null): mixed
{
    $sec = cmsSection($pageKey, $sectionKey);
    if ($sec === null || !isset($sec['data'])) {
        return $default;
    }
    return $sec['data'] !== null ? $sec['data'] : $default;
}
