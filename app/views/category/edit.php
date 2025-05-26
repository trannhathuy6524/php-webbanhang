<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0">✏️ Sửa danh mục</h3>
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

            <form method="POST" action="/webbanhang/Category/update" onsubmit="return validateForm();">
                <input type="hidden" name="id" value="<?php echo $category->id; ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục:</label>
                    <input type="text" id="name" name="name" class="form-control rounded-3"
                           value="<?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả (tùy chọn):</label>
                    <textarea id="description" name="description" class="form-control rounded-3" rows="4"><?php echo htmlspecialchars($category->description ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" 
                               <?php echo (!isset($category->status) || $category->status == 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="statusActive">
                            Hiển thị
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0"
                               <?php echo (isset($category->status) && $category->status == 0) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="statusInactive">
                            Ẩn
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Thông tin chi tiết:</label>
                    <div class="card bg-light p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Ngày tạo:</strong> 
                                    <?php echo isset($category->created_at) ? date('d/m/Y H:i', strtotime($category->created_at)) : 'N/A'; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Lần cập nhật cuối:</strong> 
                                    <?php echo isset($category->updated_at) ? date('d/m/Y H:i', strtotime($category->updated_at)) : 'N/A'; ?>
                                </p>
                            </div>
                        </div>
                        <p class="mb-0 mt-2"><strong>Số sản phẩm:</strong> 
                            <?php echo isset($product_count) ? $product_count : 0; ?>
                        </p>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/webbanhang/Category" class="btn btn-outline-secondary">← Quay lại</a>
                    <div>
                        <a href="/webbanhang/Category/delete/<?php echo $category->id; ?>" 
                           class="btn btn-outline-danger me-2"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Tất cả sản phẩm thuộc danh mục này cũng sẽ bị xóa.');">
                           🗑️ Xóa
                        </a>
                        <button type="submit" class="btn btn-success">💾 Lưu thay đổi</button>
                    </div>
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