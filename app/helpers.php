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
    return ucwords(str_replace('_', ' ', (string)($status ?: 'completed')));
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
    $fallback = $fallback ?: 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=800&q=80';
    if (empty($service['image'])) {
        return $fallback;
    }
    $image = $service['image'];
    return str_starts_with($image, 'http') ? $image : uploadUrl($image);
}

function galleryImageUrl(array $item, string $fallback = ''): string
{
    $fallback = $fallback ?: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&q=80';
    if (empty($item['image'])) {
        return $fallback;
    }
    $image = $item['image'];
    return str_starts_with($image, 'http') ? $image : uploadUrl($image);
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
                ['label' => 'Gallery', 'url' => '/gallery', 'target' => '_self'],
                ['label' => 'Contact', 'url' => '/contact', 'target' => '_self'],
            ]
            : [
                ['label' => 'Home', 'url' => '/', 'target' => '_self'],
                ['label' => 'About', 'url' => '/about', 'target' => '_self'],
                ['label' => 'Services', 'url' => '/services', 'target' => '_self'],
                ['label' => 'Projects', 'url' => '/projects', 'target' => '_self'],
                ['label' => 'Gallery', 'url' => '/gallery', 'target' => '_self'],
                ['label' => 'Contact', 'url' => '/contact', 'target' => '_self'],
            ];
    }

    $seen = [];
    $menus = array_values(array_filter($menus, static function (array $menu) use (&$seen): bool {
        $path = trim(strtolower(parse_url($menu['url'] ?? '', PHP_URL_PATH) ?: (string)($menu['url'] ?? '')), '/') ?: '/';
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
