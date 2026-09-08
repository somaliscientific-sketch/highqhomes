<?php
declare(strict_types=1);

class AdminTeamController extends Controller
{
    private TeamModel $model;

    public function __construct()
    {
        $this->model = new TeamModel();
    }

    public function index(array $params = []): void
    {
        $this->requireView();
        $members = $this->model->findAll('sort_order', 'ASC');
        $this->render('admin/team/index', compact('members'), 'admin');
    }

    public function create(array $params = []): void
    {
        $this->requireManage();
        $this->render('admin/team/form', ['member' => null, 'action' => url('admin/team/create')], 'admin');
    }

    public function store(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $data = $this->formData();

        if (!empty($_FILES['image']['name'])) {
            $data['image'] = Upload::image($_FILES['image'], 'team');
        }

        $id = $this->model->insert($data);
        $this->audit('create', 'team', "Added team member \"{$data['name']}\"", 'team', $id);
        Session::flash('success', 'Team member added.');
        $this->redirect('/admin/team');
    }

    public function edit(array $params = []): void
    {
        $this->requireManage();
        $member = $this->model->findById((int)$params['id']);
        if (!$member) $this->abort(404);

        $this->render('admin/team/form', ['member' => $member, 'action' => url("admin/team/{$member['id']}/edit")], 'admin');
    }

    public function update(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();

        $id     = (int)$params['id'];
        $member = $this->model->findById($id);
        if (!$member) $this->abort(404);

        $data = $this->formData();

        if (!empty($_FILES['image']['name'])) {
            if ($member['image']) Upload::delete($member['image']);
            $data['image'] = Upload::image($_FILES['image'], 'team');
        }

        $this->model->update($id, $data);
        $this->audit('update', 'team', "Updated team member \"{$data['name']}\"", 'team', $id);
        Session::flash('success', 'Team member updated.');
        $this->redirect('/admin/team');
    }

    public function destroy(array $params = []): void
    {
        $this->requireManage();
        CSRF::check();
        $member = $this->model->findById((int)$params['id']);
        $name = $member['name'] ?? 'member';
        if ($member && $member['image']) Upload::delete($member['image']);
        $this->model->delete((int)$params['id']);
        $this->audit('delete', 'team', "Removed team member \"{$name}\"", 'team', (int)$params['id']);
        Session::flash('success', 'Team member removed.');
        $this->redirect('/admin/team');
    }

    private function formData(): array
    {
        return [
            'name'         => $this->sanitize($this->post('name', '')),
            'position'     => $this->sanitize($this->post('position', '')),
            'bio'          => $this->sanitize($this->post('bio', '')),
            'email'        => trim($this->post('email', '')),
            'phone'        => $this->sanitize($this->post('phone', '')),
            'linkedin_url' => trim($this->post('linkedin_url', '')),
            'twitter_url'  => trim($this->post('twitter_url', '')),
            'sort_order'   => (int)$this->post('sort_order', 0),
            'is_published' => $this->post('is_published') ? 1 : 0,
        ];
    }
}
