<?php
class CategoryModel {
    private $conn;
    private $table_name = "category";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCategories($search = '', $limit = null, $offset = null) {
        $query = "SELECT c.*, COUNT(p.id) as product_count 
                  FROM " . $this->table_name . " c 
                  LEFT JOIN product p ON c.id = p.category_id";
        
        if (!empty($search)) {
            $query .= " WHERE c.name LIKE :search";
        }
        
        $query .= " GROUP BY c.id";
        
        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        if ($limit !== null && $offset !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getTotalCategories($search = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        if (!empty($search)) {
            $query .= " WHERE name LIKE :search";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function getProductCountInCategory($category_id) {
        $query = "SELECT COUNT(*) as count FROM product WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function addCategory($name, $description, $status) {
        // Kiểm tra xem tên danh mục đã tồn tại chưa
        $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE name = :name";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':name', $name);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return ["Danh mục với tên này đã tồn tại"];
        }
        
        if (empty($name)) {
            return ["Tên danh mục không được để trống"];
        }
        
        try {
            $query = "INSERT INTO " . $this->table_name . " (name, description, status, created_at) 
                      VALUES (:name, :description, :status, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            return ["Có lỗi khi thêm danh mục: " . $e->getMessage()];
        }
    }

    public function updateCategory($id, $name, $description, $status) {
        // Kiểm tra xem tên danh mục đã tồn tại với ID khác chưa
        $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE name = :name AND id != :id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':name', $name);
        $checkStmt->bindParam(':id', $id);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return ["Danh mục với tên này đã tồn tại"];
        }
        
        if (empty($name)) {
            return ["Tên danh mục không được để trống"];
        }
        
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET name = :name, 
                          description = :description, 
                          status = :status, 
                          updated_at = NOW() 
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            return ["Có lỗi khi cập nhật danh mục: " . $e->getMessage()];
        }
    }

    public function deleteCategory($id) {
        try {
            // Xóa các sản phẩm thuộc danh mục này trước
            $deleteProductsQuery = "DELETE FROM product WHERE category_id = :id";
            $deleteProductsStmt = $this->conn->prepare($deleteProductsQuery);
            $deleteProductsStmt->bindParam(':id', $id);
            $deleteProductsStmt->execute();
            
            // Sau đó xóa danh mục
            $deleteCategoryQuery = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $deleteCategoryStmt = $this->conn->prepare($deleteCategoryQuery);
            $deleteCategoryStmt->bindParam(':id', $id);
            $deleteCategoryStmt->execute();
            
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
}