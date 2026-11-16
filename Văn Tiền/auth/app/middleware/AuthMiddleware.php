<?php

class AuthMiddleware
{
    public static function requireLogin(): void
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        if (!Auth::check()) {
            redirect('/admin/login');
        }
    }

    public static function requireRole(string ...$roles): void
    {
        self::requireLogin();

        if (!Auth::hasRole(...$roles)) {
            http_response_code(403);
            exit('403 - Bạn không có quyền truy cập trang này.');
        }
    }
}
