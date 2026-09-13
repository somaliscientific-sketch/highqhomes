<?php
declare(strict_types=1);

class AdminMessagesController extends Controller
{
    private MessageModel $model;

    public function __construct()
    {
        $this->model = new MessageModel();
    }

    public function index(array $params = []): void
    {
        $this->requirePermission('messages.view');

        $filter = $this->get('filter', 'all');
        $q      = trim((string)$this->get('q', ''));
        $page   = (int)$this->get('page', 1);
        $stats  = $this->model->getStats();
        $paged  = $this->model->paginateFiltered($filter, $page, 20, $q);

        $this->render('admin/messages/index', array_merge($paged, compact('filter', 'q', 'stats')), 'admin');
    }

    public function show(array $params = []): void
    {
        $this->requirePermission('messages.view');

        $message = $this->model->findById((int)$params['id']);
        if (!$message) $this->abort(404);

        if (!$message['is_read']) {
            $this->model->markRead($message['id']);
            $message['is_read'] = 1;
        }

        $unread = $this->model->getUnreadCount();
        $stats  = $this->model->getStats();
        $this->render('admin/messages/show', compact('message', 'unread', 'stats'), 'admin');
    }

    public function destroy(array $params = []): void
    {
        $this->requirePermission('messages.manage');
        CSRF::check();
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'messages', 'Deleted contact message', 'message', (int)$params['id']);
        Session::flash('success', 'Message deleted.');
        $this->redirect('/admin/messages');
    }

    public function toggleStar(array $params = []): void
    {
        $this->requirePermission('messages.view');
        CSRF::check();
        $this->model->toggleStar((int)$params['id']);
        $this->back();
    }
}
