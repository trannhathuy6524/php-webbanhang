
<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Quản lý tài khoản</h1>
        <a href="/webbanhang/Account/addAccountForm" class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i>Thêm tài khoản mới
        </a>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" width="5%">ID</th>
                        <th scope="col" width="20%">Tên đăng nhập</th>
                        <th scope="col" width="25%">Họ tên</th>
                        <th scope="col" width="20%">Email</th>
                        <th scope="col" width="15%">Quyền</th>
                        <th scope="col" width="15%" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accounts as $account): ?>
                        <tr>
                            <th scope="row"><?php echo $account->id; ?></th>
                            <td><?php echo htmlspecialchars($account->username, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($account->fullname, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($account->email ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php if ($account->role == 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Người dùng</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="/webbanhang/Account/editAccount/<?php echo $account->id; ?>" class="btn btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i>
                                        Sửa
                                    </a>
                                    <a href="/webbanhang/Account/deleteAccount/<?php echo $account->id; ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                        <i class="bi bi-trash"></i>
                                        Xóa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>