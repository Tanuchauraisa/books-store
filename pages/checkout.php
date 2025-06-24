<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$user_id = $_SESSION['user_id'];

// Get cart items
$db->query("SELECT p.id, p.title, p.price, c.quantity 
           FROM cart c 
           JOIN products p ON c.product_id = p.id 
           WHERE c.user_id = :user_id");
$db->bind(':user_id', $user_id);
$cart_items = $db->resultSet();

// Calculate total
$cart_total = 0;
foreach($cart_items as $item) {
    $cart_total += $item->price * $item->quantity;
}

// Get user information
$db->query("SELECT * FROM users WHERE id = :id");
$db->bind(':id', $user_id);
$user = $db->single();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>
    
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-2/3">
            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                <ul class="divide-y">
                    <?php foreach($cart_items as $item): ?>
                        <li class="py-3 flex justify-between">
                            <span><?= $item->title ?> × <?= $item->quantity ?></span>
                            <span>$<?= number_format($item->price * $item->quantity, 2) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="border-t pt-4 mt-4 flex justify-between text-lg font-bold">
                    <span>Total:</span>
                    <span>$<?= number_format($cart_total, 2) ?></span>
                </div>
            </div>
            
            <!-- Shipping Information -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Shipping Information</h2>
                <form method="post" action="<?= BASE_URL ?>/processes/checkout-process.php">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" required class="w-full border border-gray-300 rounded-lg px-4 py-2" value="<?= $user->name ?>">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2" value="<?= $user->email ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2">City</label>
                            <input type="text" name="city" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">State</label>
                            <input type="text" name="state" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">ZIP Code</label>
                            <input type="text" name="zip" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h2 class="text-xl font-bold mb-4">Payment Information</h2>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Card Number</label>
                            <input type="text" name="card_number" required class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="1234 5678 9012 3456">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Expiration Month</label>
                                <input type="text" name="exp_month" placeholder="MM" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Expiration Year</label>
                                <input type="text" name="exp_year" placeholder="YYYY" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">CVV</label>
                                <input type="text" name="cvv" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition w-full">
                        Place Order
                    </button>
                </form>
            </div>
        </div>
        
        <div class="lg:w-1/3">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-bold mb-4">Complete Your Purchase</h2>
                <div class="border-t pt-4 mt-4">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>$<?= number_format($cart_total, 2) ?></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mt-4 pt-4 border-t">
                        <span>Total</span>
                        <span>$<?= number_format($cart_total, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>