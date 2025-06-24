<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Cart.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthenticated']);
    exit();
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get and validate input
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid product ID']);
    exit();
}

if ($quantity <= 0) {
    $quantity = 1;
}

// Add to cart
$cart = new Cart();
$success = $cart->addToCart($_SESSION['user_id'], $product_id, $quantity);

if ($success) {
    // Update cart count in session
    $cart_count = $cart->getCartCount($_SESSION['user_id']);
    $_SESSION['cart_count'] = $cart_count;
    
    echo json_encode([
        'success' => true,
        'cart_count' => $cart_count,
        'message' => 'Product added to cart'
    ]);

    // Redirect to BASE_URL
    header("Location: " . BASE_URL . "/pages/cart.php");
    exit();
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add to cart']);
}
?>