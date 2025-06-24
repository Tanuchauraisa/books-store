<?php

require_once __DIR__ . '/../classes/Database.php'; 

class Product {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAllProducts() {
        $this->db->query("SELECT * FROM products");
        return $this->db->resultSet();
    }
    
    public function getProductById($id) {
        $this->db->query("SELECT * FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function getProductsByCategory($category) {
        $this->db->query("SELECT * FROM products WHERE category = :category");
        $this->db->bind(':category', $category);
        return $this->db->resultSet();
    }
    
    public function searchProducts($query) {
        $this->db->query("SELECT * FROM products 
                         WHERE title LIKE :query OR author LIKE :query OR description LIKE :query");
        $this->db->bind(':query', "%$query%");
        return $this->db->resultSet();
    }
    
    public function addProduct($data) {
        $this->db->query("INSERT INTO products (title, author, price, description, image, category) 
                         VALUES (:title, :author, :price, :description, :image, :category)");
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':author', $data['author']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':category', $data['category']);
        
        return $this->db->execute();
    }
    
    public function updateProduct($id, $data) {
        $this->db->query("UPDATE products SET 
                         title = :title, 
                         author = :author, 
                         price = :price, 
                         description = :description, 
                         image = :image, 
                         category = :category 
                         WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':author', $data['author']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':category', $data['category']);
        
        return $this->db->execute();
    }
    
    public function deleteProduct($id) {
        $this->db->query("DELETE FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>