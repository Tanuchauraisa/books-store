<?php
require_once __DIR__ . '/../includes/config.php';

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Cart.php';
require_once __DIR__ . '/../classes/Order.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit();
}

// Get cart items
$cart = new Cart();
$cart_items = $cart->getCartItems($_SESSION['user_id']);

if (empty($cart_items)) {
    $_SESSION['checkout_error'] = "Your cart is empty";
    header("Location: cart.php");
    exit();
}

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item->price * $item->quantity;
}

// Create order
$order = new Order();
$order_id = $order->createOrder($_SESSION['user_id'], $total, $_POST);

if ($order_id) {
    // Add order items
    foreach ($cart_items as $item) {
        $order->addOrderItem($order_id, $item->id, $item->quantity, $item->price);
    }

    // Clear cart
    $cart->clearCart($_SESSION['user_id']);
    $_SESSION['cart_count'] = 0;

    // Redirect to success page
    $_SESSION['order_id'] = $order_id;
    header("Location: " . BASE_URL . "/pages/account.php");
    exit();
} else {
    $_SESSION['checkout_error'] = "Failed to place order. Please try again.";
    header("Location: " . BASE_URL . "/pagescheckout.php");
    exit();
}
