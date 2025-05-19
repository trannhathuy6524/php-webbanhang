
<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0">✏️ Sửa sản phẩm</h3>
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

            <form method="POST" action="/webbanhang/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
                <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Tên sản phẩm:</label>
                    <input type="text" id="name" name="name" class="form-control rounded-3"
                           value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả:</label>
                    <textarea id="description" name="description" class="form-control rounded-3" rows="4" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Giá:</label>
                    <input type="number" id="price" name="price" class="form-control rounded-3" step="0.01"
                           value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Hình ảnh sản phẩm:</label>
                    
                    <?php if (!empty($product->image) && file_exists($product->image)): ?>
                        <div class="mb-3 text-center" id="currentImage">
                            <div class="mb-2">Ảnh hiện tại:</div>
                            <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                 alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                                 class="img-thumbnail" style="max-height: 200px;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="replaceImage" name="replaceImage">
                                <label class="form-check-label" for="replaceImage">
                                    Thay thế ảnh này
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div id="uploadSection" <?php echo !empty($product->image) ? 'class="d-none"' : ''; ?>>
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
                </div>

                <div class="mb-4">
                    <label for="category_id" class="form-label">Danh mục:</label>
                    <select id="category_id" name="category_id" class="form-select rounded-3" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>" <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/webbanhang/Product" class="btn btn-outline-secondary">← Quay lại</a>
                    <button type="submit" class="btn btn-success">💾 Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Hiển thị/ẩn phần upload ảnh mới khi chọn thay thế ảnh
document.addEventListener('DOMContentLoaded', function() {
    const replaceCheckbox = document.getElementById('replaceImage');
    const uploadSection = document.getElementById('uploadSection');
    const currentImage = document.getElementById('currentImage');
    
    if (replaceCheckbox) {
        replaceCheckbox.addEventListener('change', function() {
            if (this.checked) {
                uploadSection.classList.remove('d-none');
            } else {
                uploadSection.classList.add('d-none');
                document.getElementById('image').value = '';
                document.getElementById('imagePreview').classList.add('d-none');
            }
        });
    }
    
    // Hiển thị preview ảnh khi upload
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
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
    }
    
    // Xóa ảnh đã chọn
    const removeButton = document.getElementById('removeImage');
    if (removeButton) {
        removeButton.addEventListener('click', function() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').classList.add('d-none');
        });
    }
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