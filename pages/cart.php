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
$db->query("SELECT p.id, p.title, p.author, p.price, p.image, c.quantity 
           FROM cart c 
           JOIN products p ON c.product_id = p.id 
           WHERE c.user_id = :user_id");
$db->bind(':user_id', $user_id);
$cart_items = $db->resultSet();

// Calculate total
$cart_total = 0;
foreach ($cart_items as $item) {
    $cart_total += $item->price * $item->quantity;
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Your Shopping Cart</h1>

    <?php
    if (isset($_SESSION['cart_message'])) {
        echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">'
            . $_SESSION['cart_message'] . '</div>';
        unset($_SESSION['cart_message']);
    }
    if (isset($_SESSION['cart_error'])) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">'
            . $_SESSION['cart_error'] . '</div>';
        unset($_SESSION['cart_error']);
    }
    ?>


    <?php if (empty($cart_items)): ?>
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-6"></i>
            <h3 class="text-2xl font-bold mb-4">Your cart is empty</h3>
            <p class="text-gray-600 mb-6">Looks like you haven't added any books to your cart yet</p>
            <a href="<?= BASE_URL ?>/pages/products.php" class="bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition">
                Browse Books
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="hidden md:grid grid-cols-12 gap-4 bg-gray-100 p-4 font-bold">
                <div class="col-span-5">Product</div>
                <div class="col-span-2 text-center">Price</div>
                <div class="col-span-2 text-center">Quantity</div>
                <div class="col-span-2 text-center">Total</div>
                <div class="col-span-1 text-center">Action</div>
            </div>

            <form method="post" action="<?= BASE_URL ?>/processes/update-cart.php">
                <?php foreach ($cart_items as $item): ?>
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 border-b p-4 items-center">
                        <div class="col-span-5 flex items-center">
                            <div class="w-16 h-24 bg-gray-100 rounded mr-4 flex items-center justify-center">                                
                                <img src="<?= BASE_URL ?>/uploads/<?= $item->image ?>" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold"><?= $item->title ?></h3>
                                <p class="text-gray-600 text-sm">by <?= $item->author ?></p>
                            </div>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="font-bold text-primary">$<?= number_format($item->price, 2) ?></span>
                        </div>
                        <div class="col-span-2">
                            <div class="flex justify-center">
                                <input type="number" name="quantity[<?= $item->id ?>]"
                                    value="<?= $item->quantity ?>" min="1"
                                    class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center">
                            </div>
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="font-bold">$<?= number_format($item->price * $item->quantity, 2) ?></span>
                        </div>
                        <div class="col-span-1 text-center">
                            <a href="<?= BASE_URL ?>/processes/remove-from-cart.php?product_id=<?= $item->id ?>"
                                class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>

                <div class="p-6 bg-gray-50 flex flex-col md:flex-row justify-between items-center">
                    <button type="submit" name="update_cart" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition mb-4 md:mb-0  mt-4 inline-block bg-primary text-white px-8 py-3 rounded-lg font-bold hover:bg-primary/90 transition">
                        Update Cart
                    </button>
                    <div class="text-center md:text-right">
                        <p class="text-lg">Total: <span class="font-bold text-xl text-primary">$<?= number_format($cart_total, 2) ?></span></p>
                        <a href="<?= BASE_URL ?>/pages/checkout.php" class="mt-4 inline-block bg-primary text-white px-8 py-3 rounded-lg font-bold hover:bg-primary/90 transition">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>