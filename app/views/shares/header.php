
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-wrapper {
            padding-left: 17%;
            padding-right: 17%;
        }
        
        .navbar-custom {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: #f8f9fa;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="navbar-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="#">Quản lý sản phẩm</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Product/">Danh sách sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Product/add">Thêm sản phẩm</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="/webbanhang/product/cart">
                                <i class="bi bi-cart3"></i> Giỏ hàng
                                <?php if (!empty($_SESSION['cart'])): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                        <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container mt-4">
        <!-- Nội dung trang sẽ được đặt ở đây -->
    </div>

    <!-- Thêm JavaScript Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>