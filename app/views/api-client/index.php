
<?php include 'app/views/shares/header.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<div class="container py-3">
    <h2 class="mb-4">API Client - Quản lý Sản phẩm</h2>
    
    
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="true">Danh sách sản phẩm</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button" role="tab" aria-controls="add" aria-selected="false">Thêm sản phẩm</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="search-tab" data-bs-toggle="tab" data-bs-target="#search" type="button" role="tab" aria-controls="search" aria-selected="false">Tìm kiếm</button>
        </li>
    </ul>
    
    <div class="tab-content py-3" id="myTabContent">
        <!-- Tab danh sách sản phẩm -->
        <div class="tab-pane fade show active" id="list" role="tabpanel">
            <div class="d-flex justify-content-between mb-3">
                <h4>Danh sách sản phẩm</h4>
                <button id="loadProducts" class="btn btn-primary">Làm mới</button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Mô tả</th>
                            <th>Giá</th>
                            <th>Danh mục</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="productList">
                        <!-- Dữ liệu sẽ được load bằng JavaScript -->
                        <tr>
                            <td colspan="6" class="text-center">Đang tải dữ liệu...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Tab thêm sản phẩm -->
        <div class="tab-pane fade" id="add" role="tabpanel">
            <h4 class="mb-3">Thêm sản phẩm mới</h4>
            
            <form id="addProductForm">
                <div class="mb-3">
                    <label for="productName" class="form-label">Tên sản phẩm</label>
                    <input type="text" class="form-control" id="productName" required>
                </div>
                <div class="mb-3">
                    <label for="productDescription" class="form-label">Mô tả</label>
                    <textarea class="form-control" id="productDescription" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="productPrice" class="form-label">Giá</label>
                    <input type="number" class="form-control" id="productPrice" min="0" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="productCategory" class="form-label">Danh mục</label>
                    <select class="form-control" id="productCategory" required>
                        <option value="">-- Chọn danh mục --</option>
                        <!-- Categories will be loaded by JavaScript -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
            </form>
            <div id="addResult" class="mt-3"></div>
        </div>
        
        <!-- Tab tìm kiếm -->
        <div class="tab-pane fade" id="search" role="tabpanel">
            <h4 class="mb-3">Tìm kiếm sản phẩm</h4>
            
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchKeyword" placeholder="Nhập từ khóa tìm kiếm...">
                        <div class="input-group-append">
                            <button id="searchButton" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="searchResults">
                <!-- Kết quả tìm kiếm sẽ hiển thị ở đây -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load sản phẩm khi trang được tải
    loadProducts();
    loadCategories();
    
    // Gán sự kiện cho nút làm mới
    document.getElementById('loadProducts').addEventListener('click', loadProducts);
    
    // Gán sự kiện cho form thêm sản phẩm
    document.getElementById('addProductForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addProduct();
    });
    
    // Gán sự kiện cho nút tìm kiếm
    document.getElementById('searchButton').addEventListener('click', searchProducts);
    
    // Function để tải danh sách sản phẩm
    function loadProducts() {
        const productList = document.getElementById('productList');
        productList.innerHTML = '<tr><td colspan="6" class="text-center">Đang tải dữ liệu...</td></tr>';
        
        fetch('/webbanhang/api/product')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Không thể kết nối với API');
                }
                return response.json();
            })
            .then(data => {
                console.log('API response:', data); // Log để debug
                if (data && Array.isArray(data)) {
                    // Trường hợp API trả về trực tiếp mảng sản phẩm
                    renderProductTable(data);
                } else if (data && data.data && Array.isArray(data.data)) {
                    // Trường hợp API trả về {status, data}
                    renderProductTable(data.data);
                } else {
                    productList.innerHTML = `<tr><td colspan="6" class="text-danger">Lỗi: Định dạng dữ liệu không hợp lệ</td></tr>`;
                }
            })
            .catch(error => {
                console.error('API error:', error); // Log để debug
                productList.innerHTML = `<tr><td colspan="6" class="text-danger">Lỗi: ${error.message}</td></tr>`;
            });
    }
    
    // Function để render bảng sản phẩm
    function renderProductTable(products) {
        const productList = document.getElementById('productList');
        if (products && products.length > 0) {
            productList.innerHTML = '';
            products.forEach(product => {
                productList.innerHTML += `
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td>${formatPrice(product.price)}</td>
                        <td>${product.category_name || 'Không có'}</td>
                        <td>
                            <button class="btn btn-sm btn-info view-product" data-id="${product.id}">Xem</button>
                            <button class="btn btn-sm btn-danger delete-product" data-id="${product.id}">Xóa</button>
                        </td>
                    </tr>
                `;
            });
            
            // Gán sự kiện cho các nút xem và xóa
            document.querySelectorAll('.view-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    viewProduct(id);
                });
            });
            
            document.querySelectorAll('.delete-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteProduct(id);
                });
            });
        } else {
            productList.innerHTML = '<tr><td colspan="6" class="text-center">Không có sản phẩm nào</td></tr>';
        }
    }
    
    // Function để tải danh mục sản phẩm
    // Thay thế function loadCategories hiện tại 
    function loadCategories() {
        const categorySelect = document.getElementById('productCategory');
        
        fetch('/webbanhang/api/category')
            .then(response => response.json())
            .then(data => {
                console.log('Category data:', data); // Log để debug
                
                // Xác định danh sách danh mục từ response
                let categories = [];
                if (data && Array.isArray(data)) {
                    categories = data;
                } else if (data && data.data && Array.isArray(data.data)) {
                    categories = data.data;
                } else if (data && data.status === 'success' && Array.isArray(data.data)) {
                    categories = data.data;
                }
                
                // Xóa các option cũ ngoại trừ option mặc định đầu tiên
                while (categorySelect.options.length > 1) {
                    categorySelect.remove(1);
                }
                
                // Thêm các option mới
                if (categories && categories.length > 0) {
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        categorySelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "Không có danh mục nào";
                    option.disabled = true;
                    categorySelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Lỗi khi tải danh mục:', error);
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "Lỗi tải danh mục";
                option.disabled = true;
                categorySelect.appendChild(option);
            });
    }
    
    // Function để thêm sản phẩm mới
    // Thay thế function addProduct() hiện tại bằng đoạn code sau
    function addProduct() {
        const name = document.getElementById('productName').value;
        const description = document.getElementById('productDescription').value;
        const price = document.getElementById('productPrice').value;
        const category_id = document.getElementById('productCategory').value;
        const resultDiv = document.getElementById('addResult');
        
        const data = { name, description, price, category_id };
        
        fetch('/webbanhang/api/product', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            console.log('API response for add product:', data);
            
            // Kiểm tra nếu phản hồi có status là success HOẶC có message thành công
            if (data.status === 'success' || (data.message && data.message.includes('success'))) {
                resultDiv.innerHTML = `<div class="alert alert-success">Sản phẩm đã được thêm thành công!</div>`;
                document.getElementById('addProductForm').reset();
                
                // Chuyển về tab danh sách và cập nhật
                const listTab = document.querySelector('#list-tab');
                listTab.click();
                loadProducts();
            } else if (data.errors) {
                // Hiển thị các lỗi validation
                let errorMessages = '';
                if (Array.isArray(data.errors)) {
                    errorMessages = data.errors.join('<br>');
                } else {
                    errorMessages = Object.values(data.errors).join('<br>');
                }
                resultDiv.innerHTML = `<div class="alert alert-danger">${errorMessages}</div>`;
            } else {
                // Hiển thị lỗi chung
                resultDiv.innerHTML = `<div class="alert alert-danger">Lỗi: ${data.message || 'Không thể thêm sản phẩm'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error adding product:', error);
            resultDiv.innerHTML = `<div class="alert alert-danger">Lỗi: ${error.message}</div>`;
        });
    }
    
    // Function để tìm kiếm sản phẩm
    function searchProducts() {
        const keyword = document.getElementById('searchKeyword').value;
        const searchResults = document.getElementById('searchResults');
        
        if (!keyword) {
            searchResults.innerHTML = '<div class="alert alert-warning">Vui lòng nhập từ khóa tìm kiếm</div>';
            return;
        }
        
        searchResults.innerHTML = '<div class="text-center">Đang tìm kiếm...</div>';
        
        fetch(`/webbanhang/api/product/search/${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.data && data.data.length > 0) {
                        let html = `<h5>Kết quả tìm kiếm (${data.data.length})</h5>`;
                        html += '<div class="table-responsive"><table class="table table-striped">';
                        html += '<thead><tr><th>ID</th><th>Tên sản phẩm</th><th>Mô tả</th><th>Giá</th><th>Danh mục</th></tr></thead>';
                        html += '<tbody>';
                        
                        data.data.forEach(product => {
                            html += `<tr>
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td>${product.description}</td>
                                <td>${formatPrice(product.price)}</td>
                                <td>${product.category_name || 'Không có'}</td>
                            </tr>`;
                        });
                        
                        html += '</tbody></table></div>';
                        searchResults.innerHTML = html;
                    } else {
                        searchResults.innerHTML = '<div class="alert alert-info">Không tìm thấy sản phẩm nào phù hợp</div>';
                    }
                } else {
                    searchResults.innerHTML = `<div class="alert alert-danger">Lỗi: ${data.message}</div>`;
                }
            })
            .catch(error => {
                searchResults.innerHTML = `<div class="alert alert-danger">Lỗi: ${error.message}</div>`;
            });
    }
    
    // Function để xem chi tiết sản phẩm
    function viewProduct(id) {
        fetch(`/webbanhang/api/product/${id}`)
            .then(response => response.json())
            .then(data => {
                console.log('Product data:', data); // Debug để xem dữ liệu trả về
                
                // Xác định product từ response
                let product;
                if (data.data) {
                    product = data.data;
                } else if (data.status === 'success' && data.data) {
                    product = data.data;
                } else {
                    product = data;
                }
                
                // Xóa modal cũ nếu tồn tại
                const existingModal = document.getElementById('productModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Tạo modal mới
                const modalHTML = `
                    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="productModalLabel">Chi tiết sản phẩm</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <dl class="row">
                                        <dt class="col-sm-3">ID:</dt>
                                        <dd class="col-sm-9">${product.id}</dd>
                                        
                                        <dt class="col-sm-3">Tên:</dt>
                                        <dd class="col-sm-9">${product.name}</dd>
                                        
                                        <dt class="col-sm-3">Mô tả:</dt>
                                        <dd class="col-sm-9">${product.description}</dd>
                                        
                                        <dt class="col-sm-3">Giá:</dt>
                                        <dd class="col-sm-9">${formatPrice(product.price)}</dd>
                                        
                                        <dt class="col-sm-3">Danh mục:</dt>
                                        <dd class="col-sm-9">${product.category_name || 'Không có'}</dd>
                                    </dl>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Thêm modal vào body
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                
                // Sử dụng Bootstrap 5 Modal API
                const modalElement = document.getElementById('productModal');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                
                // Xóa modal khi ẩn
                modalElement.addEventListener('hidden.bs.modal', function() {
                    modalElement.remove();
                });
            })
            .catch(error => {
                console.error('Error viewing product:', error);
                alert('Lỗi: ' + error.message);
            });
    }
    
    // Function để xóa sản phẩm
    // Thay thế function deleteProduct() hiện tại bằng đoạn code sau
    function deleteProduct(id) {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            fetch(`/webbanhang/api/product/${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Delete response:', data); // Log để debug
                
                // Kiểm tra thông báo thành công
                if (data.status === 'success' || (data.message && data.message.includes('success'))) {
                    alert('Xóa sản phẩm thành công!');
                    loadProducts();
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể xóa sản phẩm'));
                }
            })
            .catch(error => {
                console.error('Error deleting product:', error);
                alert('Lỗi: ' + error.message);
            });
        }
    }
    
    // Utility function to format price
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>