<?php
declare(strict_types=1);

class AdminDashboardController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireView();

        $stats = [
            'sliders'      => (new SliderModel())->count(),
            'services'     => (new ServiceModel())->count(),
            'projects'     => (new ProjectModel())->count(),
            'gallery'      => (new GalleryModel())->count(),
            'team'         => (new TeamModel())->count(),
            'testimonials' => (new TestimonialModel())->count(),
            'paints'       => (new PaintModel())->count(),
            'messages'     => (new MessageModel())->count(),
            'unread'       => (new MessageModel())->getUnreadCount(),
            'pages'        => (new PageModel())->count(),
            'media'        => (new MediaModel())->count(),
            'users'        => (new UserModel())->count(),
            'sections'     => $this->safeCount('page_sections'),
        ];

        $recentMessages = (new MessageModel())->getRecent(5);
        $recentProjects = (new ProjectModel())->findAll('created_at', 'DESC', 5);
        $recentLogs = Auth::can('logs.view')
            ? (new AdminLogModel())->paginateFiltered(1, 8, [])['rows']
            : [];

        $this->render('admin/dashboard', compact('stats', 'recentMessages', 'recentProjects', 'recentLogs'), 'admin');
    }

    private function safeCount(string $table): int
    {
        try {
            $stmt = Database::getInstance()->query("SELECT COUNT(*) FROM {$table}");
            return (int)$stmt->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
