<?php
declare(strict_types=1);

class SeoPublicController extends Controller
{
    public function robots(array $params = []): void
    {
        header('Content-Type: text/plain; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');
        $login = ltrim(ADMIN_LOGIN_PATH, '/');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /{$login}\n";
        echo "Sitemap: " . APP_URL . "/sitemap.xml\n";
        exit;
    }

    public function sitemap(array $params = []): void
    {
        header('Content-Type: application/xml; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');

        $urls = [
            ['loc' => APP_URL . '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => APP_URL . '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => APP_URL . '/services', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => APP_URL . '/projects', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => APP_URL . '/paints', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => APP_URL . '/gallery', 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => APP_URL . '/contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        try {
            foreach ((new ProjectModel())->getPublished() as $row) {
                if (!empty($row['slug'])) {
                    $urls[] = ['loc' => APP_URL . '/projects/' . $row['slug'], 'priority' => '0.6', 'changefreq' => 'monthly'];
                }
            }
            foreach ((new ServiceModel())->getPublished() as $row) {
                if (!empty($row['slug'])) {
                    $urls[] = ['loc' => APP_URL . '/services/' . $row['slug'], 'priority' => '0.6', 'changefreq' => 'monthly'];
                }
            }
            foreach ((new PaintModel())->getPublished() as $row) {
                if (!empty($row['slug'])) {
                    $urls[] = ['loc' => APP_URL . '/paints/' . $row['slug'], 'priority' => '0.5', 'changefreq' => 'monthly'];
                }
            }
            foreach ((new PageModel())->getPublished(50) as $row) {
                if (!empty($row['slug'])) {
                    $urls[] = ['loc' => APP_URL . '/' . $row['slug'], 'priority' => '0.4', 'changefreq' => 'monthly'];
                }
            }
        } catch (\Throwable $e) {
            Production::log('sitemap: ' . $e->getMessage());
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $item) {
            echo '  <url>';
            echo '<loc>' . htmlspecialchars($item['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</loc>';
            echo '<changefreq>' . $item['changefreq'] . '</changefreq>';
            echo '<priority>' . $item['priority'] . '</priority>';
            echo "</url>\n";
        }
        echo '</urlset>';
        exit;
    }
}
