<?php
class AccountModel {
    private $conn;
    private $table_name = "account";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullname, $password, $role = "user") {
        $query = "INSERT INTO " . $this->table_name . " (username, password, role, fullname) 
                  VALUES (:username, :password, :role, :fullname)";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $username = htmlspecialchars(strip_tags($username));
        $fullname = htmlspecialchars(strip_tags($fullname));

        // Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':fullname', $fullname);

        // Thực thi câu lệnh
        return $stmt->execute();
    }

    public function getAccounts() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAccountById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function updateAccount($id, $username, $fullname, $email, $role, $password = null) {
        if ($password) {
            // Nếu có cập nhật mật khẩu
            $query = "UPDATE " . $this->table_name . " SET 
                    username = :username, 
                    fullname = :fullname, 
                    password = :password, 
                    role = :role,
                    email = :email
                    WHERE id = :id";
        } else {
            // Nếu không cập nhật mật khẩu
            $query = "UPDATE " . $this->table_name . " SET 
                    username = :username, 
                    fullname = :fullname, 
                    role = :role,
                    email = :email
                    WHERE id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':email', $email);
        
        if ($password) {
            $stmt->bindParam(':password', $password);
        }
        
        return $stmt->execute();
    }

    public function deleteAccount($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}