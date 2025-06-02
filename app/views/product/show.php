<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row g-5">
        <!-- Phần hình ảnh sản phẩm -->
        <div class="col-md-5">
            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <?php if ($product->image && file_exists($product->image)): ?>
                    <img src="/webbanhang/<?php echo $product->image; ?>" 
                         class="img-fluid w-100" 
                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                         style="object-fit: contain; height: 400px;">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-secondary text-white" style="height: 400px;">
                        <p class="fs-4 mb-0">Không có ảnh</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Phần thông tin sản phẩm -->
        <div class="col-md-7">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-light rounded px-3 py-2 shadow-sm">
                    <li class="breadcrumb-item"><a href="/webbanhang/Product" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/webbanhang/Product" class="text-decoration-none">Sản phẩm</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></li>
                </ol>
            </nav>

            <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h2>

            <div class="mb-4">
                <h3 class="text-danger fw-bold"><?php echo number_format($product->price, 0, '', ','); ?> VND</h3>
            </div>

            <div class="mb-4">
                <h5 class="border-bottom pb-2 fw-semibold">Mô tả sản phẩm</h5>
                <p class="text-muted"><?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?></p>
            </div>

            <div class="mb-4">
                <p><strong>Danh mục:</strong> 
                    <?php 
                    if (isset($product->category_name) && !is_null($product->category_name)) {
                        echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8');
                    } elseif (isset($product->category_id)) {
                        echo "Danh mục #" . $product->category_id;
                    } else {
                        echo "Không có danh mục";
                    }
                    ?>
                </p>
            </div>

            <div class="mb-4">
                <form action="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" method="post" class="d-flex flex-wrap gap-3">
                    <a href="/webbanhang/Product" class="btn btn-outline-secondary rounded-pill px-4">
                        ← Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ hàng
                    </button>

                    <?php if (SessionHelper::isAdmin()): ?>
                        <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning rounded-pill px-4">
                            <i class="bi bi-pencil me-1"></i> Sửa
                        </a>
                        <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger rounded-pill px-4"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            <i class="bi bi-trash me-1"></i> Xóa
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
