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

function projectImageUrl(array $project, string $fallback = ''): string
{
    $fallback = $fallback ?: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=900&q=80';
    if (empty($project['featured_image'])) {
        return $fallback;
    }
    $image = $project['featured_image'];
    return str_starts_with($image, 'http') ? $image : uploadUrl($image);
}

function projectCategoryLabel(?string $category): string
{
    return ucwords(str_replace('_', ' ', (string)($category ?: 'construction')));
}

function projectStatusLabel(?string $status): string
{
    return ucwords(str_replace('_', ' ', (string)($status ?: 'completed')));
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
