<?php

require_once __DIR__ . '/../classes/Database.php'; // Adjust the path as needed

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function createOrder($user_id, $total, $data)
    {
        $this->db->query("INSERT INTO orders (user_id, total, 
                         shipping_name, shipping_email, shipping_address, 
                         shipping_city, shipping_state, shipping_zip, payment_method) 
                         VALUES (:user_id, :total, :name, :email, :address, 
                                 :city, :state, :zip, :payment_method)");

        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':total', $total);
        $this->db->bind(':name', $data['first_name'] . ' ' . $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':state', $data['state']);
        $this->db->bind(':zip', $data['zip']);
        $this->db->bind(':payment_method', 'Credit Card'); // Default payment method

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addOrderItem($order_id, $product_id, $quantity, $price)
    {
        $this->db->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES (:order_id, :product_id, :quantity, :price)");
        $this->db->bind(':order_id', $order_id);
        $this->db->bind(':product_id', $product_id);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':price', $price);
        return $this->db->execute();
    }

    public function getOrdersByUser($user_id)
    {
        $this->db->query("SELECT * FROM orders 
                         WHERE user_id = :user_id 
                         ORDER BY created_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getOrderDetails($order_id)
    {
        $this->db->query("SELECT o.*, u.name, u.email 
                         FROM orders o
                         JOIN users u ON o.user_id = u.id
                         WHERE o.id = :order_id");
        $this->db->bind(':order_id', $order_id);
        return $this->db->single();
    }

    public function getOrderItems($order_id)
    {
        $this->db->query("SELECT oi.*, p.title, p.author 
                         FROM order_items oi
                         JOIN products p ON oi.product_id = p.id
                         WHERE oi.order_id = :order_id");
        $this->db->bind(':order_id', $order_id);
        return $this->db->resultSet();
    }

    public function getAllOrders()
    {
        $this->db->query("SELECT o.*, u.name 
                         FROM orders o
                         JOIN users u ON o.user_id = u.id
                         ORDER BY o.created_at DESC");
        return $this->db->resultSet();
    }

    public function updateOrderStatus($order_id, $status)
    {
        $this->db->query("UPDATE orders SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }
}
