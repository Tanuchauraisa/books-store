<?php
function getCartCount() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['cart_count'])) {
        return $_SESSION['cart_count'];
    }
    return 0;
}

function calculateCartTotal($cart_items) {
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item->price * $item->quantity;
    }
    return $total;
}

function updateCartSessionCount($count) {
    $_SESSION['cart_count'] = $count;
}
?>