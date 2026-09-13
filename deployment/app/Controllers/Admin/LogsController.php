<?php
declare(strict_types=1);

class AdminLogsController extends Controller
{
    private AdminLogModel $model;

    public function __construct()
    {
        $this->model = new AdminLogModel();
    }

    public function index(array $params = []): void
    {
        $this->requirePermission('logs.view');

        $filters = [
            'module'  => $this->get('module', ''),
            'action'  => $this->get('action', ''),
            'user_id' => $this->get('user_id', ''),
            'q'       => trim($this->get('q', '')),
        ];
        $page = max(1, (int)$this->get('page', 1));
        $result = $this->model->paginateFiltered($page, 40, array_filter($filters));
        $modules = $this->model->distinctModules();
        $actions = $this->model->distinctActions();
        $users = (new UserModel())->findAll('name', 'ASC');

        $this->render('admin/logs/index', compact('result', 'filters', 'modules', 'actions', 'users'), 'admin');
    }
}
