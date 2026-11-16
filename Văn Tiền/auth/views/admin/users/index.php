<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Quản lý tài khoản <small class="text-muted fs-6">(<?= (int) $total ?>)</small></h3>
    <a class="btn btn-primary" href="/admin/users/create">+ Thêm tài khoản</a>
</div>

<?php if (!empty($success)): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr><th>#</th><th>Username</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Trạng thái</th><th class="text-end">Thao tác</th></tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <?php $isSelf = (int) $u['id'] === $currentId; ?>
            <tr>
                <td><?= (int) $u['id'] ?></td>
                <td><?= e($u['username']) ?></td>
                <td><?= e($u['full_name']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><span class="badge bg-secondary"><?= e($u['role']) ?></span></td>
                <td>
                    <?php if ($u['status'] === 'active'): ?>
                        <span class="badge bg-success">Hoạt động</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Đã khóa</span>
                    <?php endif; ?>
                </td>
                <td class="text-end text-nowrap">
                    <a class="btn btn-sm btn-outline-primary" href="/admin/users/<?= (int) $u['id'] ?>/edit">Sửa</a>
                    <?php if (!$isSelf): ?>
                        <form class="d-inline" method="post" action="/admin/users/<?= (int) $u['id'] ?>/toggle">
                            <?= Csrf::field() ?>
                            <button class="btn btn-sm btn-outline-warning"><?= $u['status'] === 'active' ? 'Khóa' : 'Mở khóa' ?></button>
                        </form>
                        <form class="d-inline" method="post" action="/admin/users/<?= (int) $u['id'] ?>/delete"
                              onsubmit="return confirm('Xóa tài khoản này?')">
                            <?= Csrf::field() ?>
                            <button class="btn btn-sm btn-outline-danger">Xóa</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($pages > 1): ?>
    <nav aria-label="Phân trang">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="/admin/users?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
