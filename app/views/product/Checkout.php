<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">💳 Thanh toán</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="/webbanhang/Product/processCheckout">
                <div class="mb-3">
                    <label for="name" class="form-label">Họ tên:</label>
                    <input type="text" id="name" name="name" class="form-control" required placeholder="Nhập họ tên">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại:</label>
                    <input type="text" id="phone" name="phone" class="form-control" required placeholder="Nhập số điện thoại">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ:</label>
                    <textarea id="address" name="address" class="form-control" rows="3" required placeholder="Nhập địa chỉ giao hàng"></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Xác nhận thanh toán</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="/webbanhang/Product/cart" class="btn btn-link">← Quay lại giỏ hàng</a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
