<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Cart.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/pages/login.php");
    die();
}

// Validate product_id
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    header("Location: " . BASE_URL . "/pages/cart.php");
    die();
}

$product_id = intval($_GET['product_id']);

// Remove item from cart
$cart = new Cart();
$success = $cart->removeFromCart($_SESSION['user_id'], $product_id);

if ($success) {
    // Update cart count in session
    $_SESSION['cart_count'] = $cart->getCartCount($_SESSION['user_id']);
    $_SESSION['cart_message'] = "Product removed from cart";
} else {
    $_SESSION['cart_error'] = "Failed to remove product from cart";
}

// Redirect back to cart page
header("Location: " . BASE_URL . "/pages/cart.php");
die();
