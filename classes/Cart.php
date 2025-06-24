<?php

require_once 'Database.php';

class Cart
{
    private $db;



    public function __construct()
    {
        $this->db = new Database();
    }

    public function getCartItems($user_id)
    {
        $this->db->query("SELECT p.id, p.title, p.author, p.price, p.image, c.quantity 
                         FROM cart c 
                         JOIN products p ON c.product_id = p.id 
                         WHERE c.user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function addToCart($user_id, $product_id, $quantity = 1)
    {
        // Check if product already in cart
        $this->db->query("SELECT * FROM cart 
                         WHERE user_id = :user_id AND product_id = :product_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        $existing = $this->db->single();

        if ($existing) {
            // Update quantity
            $new_quantity = $existing->quantity + $quantity;
            return $this->updateCartItem($user_id, $product_id, $new_quantity);
        } else {
            // Add to cart
            $this->db->query("INSERT INTO cart (user_id, product_id, quantity) 
                             VALUES (:user_id, :product_id, :quantity)");
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':product_id', $product_id);
            $this->db->bind(':quantity', $quantity);
            return $this->db->execute();
        }
    }

    public function updateCartItem($user_id, $product_id, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($user_id, $product_id);
        }

        $this->db->query("UPDATE cart SET quantity = :quantity 
                         WHERE user_id = :user_id AND product_id = :product_id");
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        return $this->db->execute();
    }

    public function removeFromCart($user_id, $product_id)
    {
        $this->db->query("DELETE FROM cart 
                         WHERE user_id = :user_id AND product_id = :product_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        return $this->db->execute();
    }

    public function clearCart($user_id)
    {
        $this->db->query("DELETE FROM cart WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function getCartCount($user_id)
    {
        $this->db->query("SELECT COUNT(*) AS count FROM cart WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $result = $this->db->single();
        return $result->count;
    }
}
