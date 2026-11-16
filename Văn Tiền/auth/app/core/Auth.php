<?php

class Auth
{
    private static ?array $user = null;

    public static function attempt(string $login, string $password): ?string
    {
        $user = (new User())->findByLogin($login);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return 'Tên đăng nhập hoặc mật khẩu không đúng.';
        }

        if ($user['status'] !== 'active') {
            return 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.';
        }

        session_regenerate_id(true);
        unset($_SESSION['_csrf']);
        $_SESSION['user_id'] = (int) $user['id'];
        self::$user = null;

        return null;
    }

    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }

        session_destroy();
        self::$user = null;
    }

    public static function user(): ?array
    {
        if (self::$user !== null) {
            return self::$user;
        }

        if (empty($_SESSION['user_id'])) {
            return null;
        }

        $user = (new User())->findById((int) $_SESSION['user_id']);

        if ($user === null || $user['status'] !== 'active') {
            self::logout();

            return null;
        }

        unset($user['password_hash']);
        self::$user = $user;

        return $user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        return self::check() ? (int) self::user()['id'] : null;
    }

    public static function hasRole(string ...$roles): bool
    {
        return self::check() && in_array(self::user()['role'], $roles, true);
    }

    public static function role(): ?string
    {
        return self::check() ? self::user()['role'] : null;
    }

    public static function canEditArticle(array $article): bool
    {
        if (self::hasRole('admin')) {
            return true;
        }

        return self::hasRole('editor') && (int) $article['author_id'] === self::id();
    }

    public static function canSetStatus(string $status): bool
    {
        if (self::hasRole('admin')) {
            return in_array($status, ['draft', 'pending', 'published', 'archived'], true);
        }

        return self::hasRole('editor') && in_array($status, ['draft', 'pending'], true);
    }
}
