<?php

class UserController
{
    private User $users;

    public function __construct()
    {
        AuthMiddleware::requireRole('admin');
        $this->users = new User();
    }

    public function index(): void
    {
        $perPage = 10;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        [$users, $total] = $this->users->paginate($page, $perPage);
        $pages = max(1, (int) ceil($total / $perPage));

        if ($page > $pages) {
            redirect('/admin/users?page=' . $pages);
        }

        $this->render('admin/users/index', [
            'users'     => $users,
            'total'     => $total,
            'page'      => $page,
            'pages'     => $pages,
            'currentId' => Auth::id(),
            'success'   => flash_get('success'),
            'error'     => flash_get('error'),
        ]);
    }

    public function create(): void
    {
        $this->render('admin/users/form', [
            'user'   => flash_get('old', []),
            'errors' => flash_get('errors', []),
            'isEdit' => false,
        ]);
    }

    public function store(): void
    {
        Csrf::check();

        $data = $this->input();
        $password = post_string('password', false);
        $errors = $this->validate($data, $password, 0, true);

        if ($errors) {
            $this->backWithErrors('/admin/users/create', $errors, $data);
        }

        $this->users->create($data, $password);
        flash_set('success', 'Đã tạo tài khoản.');
        redirect('/admin/users');
    }

    public function edit(int $id): void
    {
        $user = $this->findOr404($id);
        $old = flash_get('old', []);

        $this->render('admin/users/form', [
            'user'   => array_merge($user, $old),
            'errors' => flash_get('errors', []),
            'isEdit' => true,
        ]);
    }

    public function update(int $id): void
    {
        Csrf::check();
        $this->findOr404($id);

        $data = $this->input();
        $password = post_string('password', false);
        $errors = $this->validate($data, $password, $id, false);

        if ($id === Auth::id() && $data['role'] !== 'admin') {
            $errors['role'] = 'Bạn không thể tự hạ quyền của chính mình.';
        }

        if ($errors) {
            $this->backWithErrors("/admin/users/{$id}/edit", $errors, $data);
        }

        $this->users->update($id, $data);

        if ($password !== '') {
            $this->users->updatePassword($id, $password);
        }

        flash_set('success', 'Đã cập nhật tài khoản.');
        redirect('/admin/users');
    }

    public function toggleStatus(int $id): void
    {
        Csrf::check();
        $user = $this->findOr404($id);

        if ($id === Auth::id()) {
            flash_set('error', 'Bạn không thể tự khóa chính mình.');
            redirect('/admin/users');
        }

        $isActive = $user['status'] === 'active';
        $this->users->setStatus($id, $isActive ? 'locked' : 'active');
        flash_set('success', $isActive ? 'Đã khóa tài khoản.' : 'Đã mở khóa tài khoản.');
        redirect('/admin/users');
    }

    public function destroy(int $id): void
    {
        Csrf::check();
        $this->findOr404($id);

        if ($id === Auth::id()) {
            flash_set('error', 'Bạn không thể tự xóa chính mình.');
            redirect('/admin/users');
        }

        if ($this->users->countArticles($id) > 0) {
            flash_set('error', 'Tài khoản này đã có bài viết nên không thể xóa. Hãy khóa tài khoản thay vì xóa.');
            redirect('/admin/users');
        }

        try {
            $this->users->delete($id);
            flash_set('success', 'Đã xóa tài khoản.');
        } catch (PDOException $e) {
            flash_set('error', 'Không xóa được vì tài khoản còn bài viết. Hãy khóa tài khoản thay vì xóa.');
        }

        redirect('/admin/users');
    }

    private function input(): array
    {
        return [
            'username'  => post_string('username'),
            'email'     => post_string('email'),
            'full_name' => post_string('full_name'),
            'role'      => post_string('role'),
        ];
    }

    private function validate(array $d, string $password, int $id, bool $passwordRequired): array
    {
        $errors = [];

        if (!preg_match('/^[A-Za-z0-9_.]{3,30}$/', $d['username'])) {
            $errors['username'] = 'Username 3-30 ký tự (chữ, số, dấu _ hoặc .).';
        } elseif ($this->users->usernameExists($d['username'], $id)) {
            $errors['username'] = 'Username đã tồn tại.';
        }

        if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        } elseif ($this->users->emailExists($d['email'], $id)) {
            $errors['email'] = 'Email đã tồn tại.';
        }

        if ($d['full_name'] === '' || mb_strlen($d['full_name']) > 100) {
            $errors['full_name'] = 'Họ tên bắt buộc, tối đa 100 ký tự.';
        }

        if (!in_array($d['role'], User::ROLES, true)) {
            $errors['role'] = 'Vai trò không hợp lệ.';
        }

        if (($passwordRequired || $password !== '') && strlen($password) < 8) {
            $errors['password'] = 'Mật khẩu tối thiểu 8 ký tự.';
        }

        return $errors;
    }

    private function backWithErrors(string $url, array $errors, array $old): void
    {
        flash_set('errors', $errors);
        flash_set('old', $old);
        redirect($url);
    }

    private function findOr404(int $id): array
    {
        $user = $this->users->findById($id);

        if ($user === null) {
            http_response_code(404);
            exit('Không tìm thấy tài khoản.');
        }

        return $user;
    }

    private function render(string $view, array $data): void
    {
        if (class_exists('View')) {
            View::render($view, $data);

            return;
        }

        extract($data);
        require __DIR__ . '/../../../views/' . $view . '.php';
    }
}
