<?php
session_start();
require_once 'app/models/ProductModel.php';

// Lấy URL từ query string và xử lý
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định tên controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// Xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Kiểm tra xem controller có tồn tại không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Không tìm thấy controller');
}

require_once 'app/controllers/' . $controllerName . '.php';

// Khởi tạo controller
$controller = new $controllerName();

// Kiểm tra xem action có tồn tại trong controller không
if (!method_exists($controller, $action)) {
    die('Không tìm thấy action');
}

// Gọi action với các tham số còn lại (nếu có)
call_user_func_array([$controller, $action], array_slice($url, 2));