<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    public function register() {
        include_once 'app/views/account/register.php';
    }

    public function login() {
        include_once 'app/views/account/login.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';

            $errors = [];

            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui lòng nhập họ tên!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập mật khẩu!";
            }
            if ($password !== $confirmPassword) {
                $errors['confirmPass'] = "Mật khẩu và xác nhận không khớp!";
            }

            // Kiểm tra username đã được đăng ký chưa
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tài khoản này đã có người đăng ký!";
            }

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->save($username, $fullName, $password);
                if ($result) {
                    header('Location: /webbanhang/account/login');
                    exit();
                }
            }
        }
    }

    public function logout() {
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        header('Location: /webbanhang/product');
        exit();
    }

    public function checkLogin() {
        // Kiểm tra xem liệu form đã được submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $pwd_hashed = $account->password;

                // Kiểm tra mật khẩu
                if (password_verify($password, $pwd_hashed)) {
                    session_start();
                    $_SESSION['username'] = $account->username;
                    $_SESSION['role'] = $account->role;

                    header('Location: /webbanhang/product');
                    exit();
                } else {
                    echo "Mật khẩu không chính xác.";
                }
            } else {
                echo "Lỗi: Không tìm thấy tài khoản.";
            }
        }
    }
    public function manage() {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        
        // Lấy danh sách tài khoản từ model
        $accounts = $this->accountModel->getAccounts();
        include 'app/views/account/manage.php';
    }

    public function editAccount($id) {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        
        $account = $this->accountModel->getAccountById($id);
        if ($account) {
            include 'app/views/account/edit_account.php';
        } else {
            echo "Không tìm thấy tài khoản.";
        }
    }

    public function updateAccount() {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $username = $_POST['username'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'user';
            
            // Cập nhật mật khẩu nếu có nhập mới
            $password = null;
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
            }
            
            $result = $this->accountModel->updateAccount($id, $username, $fullname, $email, $role, $password);
            if ($result) {
                header('Location: /webbanhang/Account/manage');
                exit;
            } else {
                echo "Có lỗi xảy ra khi cập nhật tài khoản";
            }
        }
    }

    public function deleteAccount($id) {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        
        if ($this->accountModel->deleteAccount($id)) {
            header('Location: /webbanhang/Account/manage');
            exit;
        } else {
            echo "Có lỗi xảy ra khi xóa tài khoản";
        }
    }

    public function addAccountForm() {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        
        include 'app/views/account/add_account.php';
    }

    public function addAccount() {
        if (!SessionHelper::isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user';
            
            if (empty($username) || empty($password)) {
                echo "Tên đăng nhập và mật khẩu không được để trống";
                return;
            }
            
            // Kiểm tra username đã tồn tại chưa
            $existingAccount = $this->accountModel->getAccountByUsername($username);
            if ($existingAccount) {
                echo "Tên đăng nhập đã tồn tại";
                return;
            }
            
            $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $result = $this->accountModel->save($username, $fullname, $password, $role);
            
            if ($result) {
                header('Location: /webbanhang/Account/manage');
                exit;
            } else {
                echo "Có lỗi xảy ra khi thêm tài khoản";
            }
        }
    }
}