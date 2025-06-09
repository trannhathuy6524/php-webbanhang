<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0">✏️ Sửa sản phẩm</h3>
        </div>
        <div class="card-body">
            <div id="error-alert" class="alert alert-danger d-none"></div>
            <form id="edit-product-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="existing_image" id="existing_image" value="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>">

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
                                <label class="form-check-label" for="replaceImage">Thay thế ảnh này</label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div id="uploadSection" <?php echo !empty($product->image) ? 'class="d-none"' : ''; ?>>
                        <input type="file" class="form-control rounded-3" id="image" name="image" accept="image/jpeg,image/png,image/gif">
                        <div id="imagePreview" class="mt-2 text-center d-none">
                            <img src="" class="img-thumbnail mb-2" style="max-height: 200px;" id="previewImg">
                            <button type="button" class="btn btn-sm btn-outline-danger d-block mx-auto" id="removeImage">
                                <i class="bi bi-x-circle"></i> Xóa ảnh
                            </button>
                        </div>
                        <div class="form-text">Chấp nhận: JPG, PNG, GIF. Tối đa: 2MB.</div>
                    </div>
                </div>

                <div class="mb-3">
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
                    <button type="submit" class="btn btn-success" id="submit-btn">💾 Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('edit-product-form');
    const errorAlert = document.getElementById('error-alert');
    const replaceCheckbox = document.getElementById('replaceImage');
    const uploadSection = document.getElementById('uploadSection');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImage');
    const submitBtn = document.getElementById('submit-btn');

    // Toggle upload section
    if (replaceCheckbox) {
        replaceCheckbox.addEventListener('change', () => {
            uploadSection.classList.toggle('d-none', !replaceCheckbox.checked);
            if (!replaceCheckbox.checked) {
                imageInput.value = '';
                imagePreview.classList.add('d-none');
            }
        });
    }

    // Image preview and validation
    if (imageInput) {
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showError('Kích thước file quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                imageInput.value = '';
                return;
            }

            // Validate file type
            if (!file.type.match('image/(jpeg|png|gif)')) {
                showError('Chỉ chấp nhận file JPG, PNG, GIF.');
                imageInput.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    // Remove uploaded image
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', () => {
            imageInput.value = '';
            imagePreview.classList.add('d-none');
        });
    }

    // Form validation and submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!validateForm()) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';

        try {
            const formData = new FormData(form);
            const response = await fetch(`/webbanhang/api/product/${formData.get('id')}`, {
                method: 'POST', // Use POST to handle file uploads
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                window.location.href = '/webbanhang/Product';
            } else {
                showError(data.message || 'Cập nhật sản phẩm thất bại.');
            }
        } catch (error) {
            showError('Đã xảy ra lỗi. Vui lòng thử lại.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '💾 Lưu thay đổi';
        }
    });

    // Validate form inputs
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const price = parseFloat(document.getElementById('price').value);
        const description = document.getElementById('description').value.trim();

        if (!name) {
            showError('Vui lòng nhập tên sản phẩm.');
            return false;
        }
        if (isNaN(price) || price <= 0) {
            showError('Giá sản phẩm phải là số dương.');
            return false;
        }
        if (!description) {
            showError('Vui lòng nhập mô tả sản phẩm.');
            return false;
        }
        return true;
    }

    // Show error message
    function showError(message) {
        errorAlert.textContent = message;
        errorAlert.classList.remove('d-none');
        setTimeout(() => errorAlert.classList.add('d-none'), 5000);
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>