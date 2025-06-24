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

// Validate input
if (!isset($_POST['quantity']) || !is_array($_POST['quantity'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No items to update']);
    exit();
}

$cart = new Cart();
$all_success = true;

foreach ($_POST['quantity'] as $product_id => $quantity) {
    if (!is_numeric($product_id) || intval($product_id) <= 0) {
        continue; // skip invalid IDs
    }

    $quantity = is_numeric($quantity) && intval($quantity) > 0 ? intval($quantity) : 1;

    $success = $cart->updateCartItem($_SESSION['user_id'], intval($product_id), $quantity);
    if (!$success) {
        $all_success = false;
    }
}

// Update cart count in session
$cart_count = $cart->getCartCount($_SESSION['user_id']);
$_SESSION['cart_count'] = $cart_count;

if ($all_success) {
    $_SESSION['cart_message'] = 'Cart updated successfully.';
    header("Location: " . BASE_URL . "/pages/cart.php");
    exit();    
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Some items failed to update']);
}
?>