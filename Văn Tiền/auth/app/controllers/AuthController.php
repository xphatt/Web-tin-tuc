<?php

class AuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            redirect('/admin');
        }

        $error = flash_get('error');
        $old = flash_get('old', []);

        require __DIR__ . '/../../views/admin/login.php';
    }

    public function login(): void
    {
        Csrf::check();

        $login = post_string('login');
        $password = post_string('password', false);

        $error = ($login === '' || $password === '')
            ? 'Vui lòng nhập đầy đủ thông tin đăng nhập.'
            : Auth::attempt($login, $password);

        if ($error !== null) {
            flash_set('error', $error);
            flash_set('old', ['login' => $login]);
            redirect('/admin/login');
        }

        redirect('/admin');
    }

    public function logout(): void
    {
        Csrf::check();
        Auth::logout();
        redirect('/admin/login');
    }
}
