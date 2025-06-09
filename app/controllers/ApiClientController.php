

<?php
require_once('app/config/database.php');

class ApiClientController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function index() {
        // Kiểm tra quyền admin
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        
        include 'app/views/api-client/index.php';
    }
}