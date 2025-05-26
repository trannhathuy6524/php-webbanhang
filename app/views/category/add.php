<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Thêm danh mục mới</h3>
        </div>
        <div class="card-body">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/webbanhang/Category/save" onsubmit="return validateForm();">
                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục:</label>
                    <input type="text" id="name" name="name" class="form-control rounded-3" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả (tùy chọn):</label>
                    <textarea id="description" name="description" class="form-control rounded-3" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" checked>
                        <label class="form-check-label" for="statusActive">
                            Hiển thị
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0">
                        <label class="form-check-label" for="statusInactive">
                            Ẩn
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/webbanhang/Category" class="btn btn-outline-secondary">← Quay lại</a>
                    <button type="submit" class="btn btn-success">💾 Thêm danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm() {
    const name = document.getElementById('name').value.trim();
    
    if (name === '') {
        alert('Vui lòng nhập tên danh mục');
        return false;
    }
    
    return true;
}
</script>

<?php include 'app/views/shares/footer.php'; ?>