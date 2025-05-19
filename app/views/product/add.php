
<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Thêm sản phẩm mới</h3>
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

            <form method="POST" action="/webbanhang/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();">
                <div class="mb-3">
                    <label for="name" class="form-label">Tên sản phẩm:</label>
                    <input type="text" id="name" name="name" class="form-control rounded-3" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả:</label>
                    <textarea id="description" name="description" class="form-control rounded-3" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Giá:</label>
                    <input type="number" id="price" name="price" class="form-control rounded-3" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Hình ảnh sản phẩm:</label>
                    <div class="input-group mb-2">
                        <input type="file" class="form-control rounded-3" id="image" name="image" accept="image/*">
                        <label class="input-group-text rounded-3" for="image">Tải lên</label>
                    </div>
                    <div id="imagePreview" class="mt-2 text-center d-none">
                        <img src="" class="img-thumbnail mb-2" style="max-height: 200px;" id="previewImg">
                        <button type="button" class="btn btn-sm btn-outline-danger d-block mx-auto" id="removeImage">
                            <i class="bi bi-x-circle"></i> Xóa ảnh
                        </button>
                    </div>
                    <div class="form-text">Chấp nhận các định dạng: JPG, JPEG, PNG, GIF. Kích thước tối đa: 2MB</div>
                </div>

                <div class="mb-4">
                    <label for="category_id" class="form-label">Danh mục:</label>
                    <select id="category_id" name="category_id" class="form-select rounded-3" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>">
                                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/webbanhang/Product" class="btn btn-outline-secondary">← Quay lại</a>
                    <button type="submit" class="btn btn-success">💾 Thêm sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Hiển thị preview ảnh khi upload
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Kiểm tra kích thước (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('Kích thước file quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
            this.value = '';
            return;
        }
        
        // Kiểm tra loại file
        const fileType = file.type;
        if (!fileType.match('image/(jpeg|jpg|png|gif)')) {
            alert('Chỉ chấp nhận file hình ảnh có định dạng JPG, JPEG, PNG, GIF.');
            this.value = '';
            return;
        }
        
        // Hiển thị preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewImg');
            preview.src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    }
});

// Xóa ảnh đã chọn
document.getElementById('removeImage').addEventListener('click', function() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('d-none');
});

// Validate form
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const price = document.getElementById('price').value;
    const description = document.getElementById('description').value.trim();
    
    if (name === '') {
        alert('Vui lòng nhập tên sản phẩm');
        return false;
    }
    
    if (isNaN(price) || parseFloat(price) <= 0) {
        alert('Giá sản phẩm phải là số dương');
        return false;
    }
    
    if (description === '') {
        alert('Vui lòng nhập mô tả sản phẩm');
        return false;
    }
    
    return true;
}
</script>

<?php include 'app/views/shares/footer.php'; ?>