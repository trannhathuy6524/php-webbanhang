<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Danh sách danh mục</h1>
        <a href="/webbanhang/Category/add" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i>Thêm danh mục mới
        </a>
    </div>
    
    <!-- Bộ lọc và tìm kiếm -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form action="/webbanhang/Category" method="GET" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Tìm kiếm danh mục..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-funnel me-1"></i>Lọc
                        </button>
                        <a href="/webbanhang/Category" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách danh mục -->
    <?php if(empty($categories)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
            <h4>Không tìm thấy danh mục nào</h4>
            <p class="mb-0">Hãy thử lại với tiêu chí tìm kiếm khác hoặc thêm danh mục mới.</p>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">#</th>
                            <th scope="col" width="60%">Tên danh mục</th>
                            <th scope="col" width="20%">Số sản phẩm</th>
                            <th scope="col" width="15%" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <th scope="row"><?php echo $category->id; ?></th>
                                <td><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo isset($category->product_count) ? $category->product_count : 0; ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="/webbanhang/Category/edit/<?php echo $category->id; ?>" class="btn btn-outline-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="/webbanhang/Category/delete/<?php echo $category->id; ?>" 
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Tất cả sản phẩm thuộc danh mục này cũng sẽ bị xóa.');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Phân trang -->
        <?php if(isset($totalPages) && $totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
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

<?php include 'app/views/shares/footer.php'; ?>