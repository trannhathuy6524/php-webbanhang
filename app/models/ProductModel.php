<?php
class ProductModel {
    private $conn;
    private $table_name = "product";

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function getProducts() {
        $query = "SELECT p.id, p.name, p.description, p.price, c.name as category_name 
                FROM " . $this->table_name . " p 
                LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductById($id) {
        $query = "SELECT p.*, c.name AS category_name 
                FROM " . $this->table_name . " p 
                LEFT JOIN category c ON p.category_id = c.id 
                WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    
    public function addProduct($name, $description, $price, $category_id) {
        // Kiểm tra nếu các giá trị hợp lệ
        $errors = [];
    
        if (empty($name)) {
            $errors[] = "Tên sản phẩm không được để trống";
        }
    
        if (empty($description)) {
            $errors[] = "Mô tả sản phẩm không được để trống";
        }
    
        if (!is_numeric($price) || $price <= 0) {
            $errors[] = "Giá sản phẩm phải là số dương";
        }
    
        if (!empty($errors)) {
            return $errors;
        }
    
        try {
            // Sửa từ "products" thành $this->table_name
            $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id) 
                    VALUES (:name, :description, :price, :category_id)";
        
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':category_id', $category_id);
        
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            // Log lỗi và trả về false
            error_log("Lỗi thêm sản phẩm: " . $e->getMessage());
            return false;
        }
    }


    public function updateProduct($id, $name, $description, $price, $category_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, description=:description, price=:price, category_id=:category_id
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Xử lý dữ liệu đầu vào để tránh lỗi bảo mật
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        // Kiểm tra xem có sản phẩm nào với id này không

        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
}