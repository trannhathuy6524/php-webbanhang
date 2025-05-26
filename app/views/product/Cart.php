<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">🛒 Giỏ hàng của bạn</h1>

    <?php if (!empty($cart)): ?>
        <div class="row">
            <?php $total = 0; ?>
            <?php foreach ($cart as $id => $item): ?>
                <?php $subtotal = $item['price'] * $item['quantity']; ?>
                <?php $total += $subtotal; ?>

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if ($item['image']): ?>
                            <img src="/webbanhang/<?php echo $item['image']; ?>" class="card-img-top" alt="Product Image" style="object-fit: cover; height: 200px;">
                        <?php else: ?>
                            <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                Không có ảnh
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="card-text mb-1">Giá: <strong><?php echo number_format($item['price']); ?> VND</strong></p>
                            <p class="card-text mb-1">Số lượng: <strong><?php echo $item['quantity']; ?></strong></p>
                            <p class="card-text">Thành tiền: <strong class="text-success"><?php echo number_format($subtotal); ?> VND</strong></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="border-top pt-3 mt-3 text-end">
            <h4>Tổng cộng: <span class="text-primary"><?php echo number_format($total); ?> VND</span></h4>
            <a href="/webbanhang/Product" class="btn btn-secondary mt-2 me-2">← Tiếp tục mua sắm</a>
            <a href="/webbanhang/Product/checkout" class="btn btn-primary mt-2">Thanh toán ngay</a>
        </div>
    <?php else: ?>
        <div class="alert alert-info">🛍️ Giỏ hàng của bạn đang trống.</div>
        <a href="/webbanhang/Product" class="btn btn-success">Bắt đầu mua sắm</a>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
