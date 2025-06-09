
<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">API Client</h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="apiTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab" aria-controls="products" aria-selected="true">Sản phẩm</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">Danh mục</button>
                </li>
            </ul>
            
            <div class="tab-content mt-3" id="apiTabsContent">
                <!-- Tab sản phẩm -->
                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                    <div class="mb-3">
                        <div class="d-flex">
                            <button id="loadProducts" class="btn btn-primary me-2">Tải danh sách sản phẩm</button>
                            <div class="input-group w-50">
                                <input type="text" id="productId" class="form-control" placeholder="Nhập ID sản phẩm">
                                <button id="getProduct" class="btn btn-outline-primary">Tìm sản phẩm</button>
                            </div>
                        </div>
                    </div>
                    <div class="result-container">
                        <pre id="productsResult" class="border rounded p-3 bg-light" style="min-height: 300px; overflow-y: auto;"></pre>
                    </div>
                </div>
                
                <!-- Tab danh mục -->
                <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                    <div class="mb-3">
                        <button id="loadCategories" class="btn btn-primary">Tải danh sách danh mục</button>
                    </div>
                    <div class="result-container">
                        <pre id="categoriesResult" class="border rounded p-3 bg-light" style="min-height: 300px; overflow-y: auto;"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý API sản phẩm
    document.getElementById('loadProducts').addEventListener('click', function() {
        fetch('/webbanhang/api/product')
            .then(response => response.json())
            .then(data => {
                document.getElementById('productsResult').textContent = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                document.getElementById('productsResult').textContent = 'Lỗi: ' + error.message;
            });
    });

    document.getElementById('getProduct').addEventListener('click', function() {
        const id = document.getElementById('productId').value;
        if (!id) {
            alert('Vui lòng nhập ID sản phẩm');
            return;
        }
        
        fetch(`/webbanhang/api/product/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('productsResult').textContent = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                document.getElementById('productsResult').textContent = 'Lỗi: ' + error.message;
            });
    });

    // Xử lý API danh mục
    document.getElementById('loadCategories').addEventListener('click', function() {
        fetch('/webbanhang/api/category')
            .then(response => response.json())
            .then(data => {
                document.getElementById('categoriesResult').textContent = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                document.getElementById('categoriesResult').textContent = 'Lỗi: ' + error.message;
            });
    });
    
    // Load Bootstrap JS để tabs hoạt động
    const bootstrapScript = document.createElement('script');
    bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
    document.body.appendChild(bootstrapScript);
});
</script>

<?php include 'app/views/shares/footer.php'; ?>