<?php
require_once __DIR__ . '/../classes/Database.php'; // Adjust the path as needed

class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function register($name, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $this->db->query("INSERT INTO users (name, email, password) 
                         VALUES (:name, :email, :password)");
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashed_password);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    public function login($email, $password) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $user = $this->db->single();
        
        if ($user && password_verify($password, $user->password)) {
            return $user;  
        }
        return false;
    }
    
    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
    
    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function updateUser($id, $data) {
        $sql = "UPDATE users SET name = :name, email = :email";
        $params = [
            ':id' => $id,
            ':name' => $data['name'],
            ':email' => $data['email']
        ];
        
        if (!empty($data['password'])) {
            $sql .= ", password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $sql .= " WHERE id = :id";
        
        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        return $this->db->execute();
    }
}
?>