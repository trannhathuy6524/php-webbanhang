
<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Danh sách sản phẩm</h1>
        <a href="/webbanhang/Product/add" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
        </a>
    </div>
    
    <!-- Bộ lọc và tìm kiếm -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form action="/webbanhang/Product" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Tìm kiếm sản phẩm..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        <?php if(isset($categories)): foreach($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel me-1"></i>Lọc
                        </button>
                        <a href="/webbanhang/Product" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <?php if(empty($products)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
            <h4>Không tìm thấy sản phẩm nào</h4>
            <p class="mb-0">Hãy thử lại với tiêu chí tìm kiếm khác hoặc thêm sản phẩm mới.</p>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <!-- Thêm hình ảnh sản phẩm -->
                        <?php if(!empty($product->image)): ?>
                            <div class="product-image-container">
                                <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                    class="card-img-top product-image" 
                                    alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        <?php else: ?>
                            <div class="product-image-container bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark product-title">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">
                                    <?php echo isset($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa phân loại'; ?>
                                </span>
                                <small class="text-muted">ID: <?php echo $product->id; ?></small>
                            </div>
                            <p class="card-text text-muted mb-2 product-description">
                                <?php echo htmlspecialchars(substr($product->description, 0, 100) . (strlen($product->description) > 100 ? '...' : ''), ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                            <p class="card-text fw-bold text-primary mb-3">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                            <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil-square me-1"></i>Sửa
                            </a>
                            <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                            class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                <i class="bi bi-trash me-1"></i>Xóa
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Phân trang -->
        <?php if(isset($totalPages) && $totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php 
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $queryParams = $_GET;
                unset($queryParams['page']);
                $queryString = http_build_query($queryParams);
                $queryString = !empty($queryString) ? '&' . $queryString : '';
                ?>
                
                <!-- Nút Previous -->
                <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?><?php echo $queryString; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                
                <!-- Các số trang -->
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo $queryString; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <!-- Nút Next -->
                <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?><?php echo $queryString; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
    .product-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .product-title {
        font-weight: 600;
        display: block;
        margin-bottom: 10px;
    }
    
    .product-title:hover {
        color: #0d6efd !important;
    }
    
    .product-description {
        min-height: 50px;
        font-size: 0.9rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-footer .btn {
        border-radius: 5px;
    }
    
    .product-image-container {
        height: 200px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .pagination .page-link {
        border-radius: 5px;
        margin: 0 3px;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>