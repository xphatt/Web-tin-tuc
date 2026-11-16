<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh">
<div class="container" style="max-width:400px">
    <form method="post" action="/admin/login" class="card shadow-sm p-4">
        <h4 class="mb-3 text-center">Đăng nhập quản trị</h4>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2"><?= e($error) ?></div>
        <?php endif; ?>
        <?= Csrf::field() ?>
        <div class="mb-3">
            <label class="form-label" for="login">Username hoặc email</label>
            <input class="form-control" id="login" name="login" required autofocus
                   value="<?= e($old['login'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Mật khẩu</label>
            <input class="form-control" type="password" id="password" name="password" required>
        </div>
        <button class="btn btn-primary w-100">Đăng nhập</button>
    </form>
</div>
</body>
</html>
