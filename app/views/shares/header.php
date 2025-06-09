<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container rounded shadow-sm my-3">
        <a class="navbar-brand" href="#">Quản lý sản phẩm</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- GỘP TẤT CẢ NAV ITEM VÀO ĐÂY -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Bên trái -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/Product/">Danh sách sản phẩm</a>
                </li>
                <?php if (SessionHelper::isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/add">Thêm sản phẩm</a>
                    </li>
                    <li>
                        <a class="nav-link" href="/webbanhang/Category/">Danh mục sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Category/add">Thêm danh mục</a>
                    </li>
                    <li>
                        <a class="nav-link" href="/webbanhang/Order/">Quản lý đơn hàng</a>
                    </li>
                    <li>
                        <a class="nav-link" href="/webbanhang/Account/manage">Quản lý tài khoản</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/ApiClient">API Client</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Bên phải -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <a class="nav-link"><?php echo $_SESSION['username']; ?></a>
                    <?php else: ?>
                        <a class="nav-link" href="/webbanhang/account/login">Đăng nhập</a>
                    <?php endif; ?>
                </li>
                <li class="nav-item">
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <a class="nav-link" href="/webbanhang/account/logout">Đăng xuất</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Nội dung chính sẽ được đặt ở đây -->
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
