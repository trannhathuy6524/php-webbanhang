<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController {
    private $categoryModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index() {
        // Lấy thông số tìm kiếm và phân trang
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Số mục trên mỗi trang
        $offset = ($page - 1) * $limit;

        // Lấy danh sách danh mục và tổng số
        $categories = $this->categoryModel->getCategories($search, $limit, $offset);
        $totalCategories = $this->categoryModel->getTotalCategories($search);
        $totalPages = ceil($totalCategories / $limit);

        include 'app/views/category/list.php';
    }

    public function add() {
        include 'app/views/category/add.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

            $result = $this->categoryModel->addCategory($name, $description, $status);
            
            if (is_array($result)) {
                // Có lỗi validation
                $errors = $result;
                include 'app/views/category/add.php';
            } else {
                // Thành công
                header('Location: /webbanhang/Category');
                exit;
            }
        } else {
            // Hiển thị form add
            include 'app/views/category/add.php';
        }
    }

    public function edit($id) {
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            echo "Không tìm thấy danh mục.";
            return;
        }

        // Đếm số sản phẩm trong danh mục
        $product_count = $this->categoryModel->getProductCountInCategory($id);
        include 'app/views/category/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

            $result = $this->categoryModel->updateCategory($id, $name, $description, $status);
            
            if (is_array($result)) {
                // Có lỗi validation
                $errors = $result;
                $category = $this->categoryModel->getCategoryById($id);
                $product_count = $this->categoryModel->getProductCountInCategory($id);
                include 'app/views/category/edit.php';
            } else {
                // Thành công
                header('Location: /webbanhang/Category');
                exit;
            }
        } else {
            header('Location: /webbanhang/Category');
            exit;
        }
    }

    public function delete($id) {
        $result = $this->categoryModel->deleteCategory($id);
        header('Location: /webbanhang/Category');
        exit;
    }
}