<?php
$action = $isEdit ? '/admin/users/' . (int) $user['id'] . '/update' : '/admin/users/store';
$err = static fn (string $f): string => isset($errors[$f])
    ? '<div class="invalid-feedback d-block">' . e($errors[$f]) . '</div>' : '';
?>
<h3 class="mb-3"><?= $isEdit ? 'Sửa tài khoản' : 'Thêm tài khoản' ?></h3>

<form method="post" action="<?= e($action) ?>" style="max-width:520px">
    <?= Csrf::field() ?>
    <div class="mb-3">
        <label class="form-label" for="username">Username</label>
        <input class="form-control" id="username" name="username" required value="<?= e($user['username'] ?? '') ?>">
        <?= $err('username') ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="full_name">Họ tên</label>
        <input class="form-control" id="full_name" name="full_name" required value="<?= e($user['full_name'] ?? '') ?>">
        <?= $err('full_name') ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" id="email" name="email" required value="<?= e($user['email'] ?? '') ?>">
        <?= $err('email') ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="role">Vai trò</label>
        <select class="form-select" id="role" name="role">
            <?php foreach (['admin' => 'Admin', 'editor' => 'Editor'] as $v => $label): ?>
                <option value="<?= e($v) ?>" <?= ($user['role'] ?? 'editor') === $v ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <?= $err('role') ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">
            <?= $isEdit ? 'Mật khẩu mới (bỏ trống nếu không đổi)' : 'Mật khẩu (tối thiểu 8 ký tự)' ?>
        </label>
        <input class="form-control" type="password" id="password" name="password" <?= $isEdit ? '' : 'required' ?> autocomplete="new-password">
        <?= $err('password') ?>
    </div>
    <button class="btn btn-primary">Lưu</button>
    <a class="btn btn-link" href="/admin/users">Hủy</a>
</form>
