<?php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Database.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . BASE_URL . "/pages/login.php");
    exit();
}

$db = new Database();

// Get stats
$db->query("SELECT COUNT(*) AS total FROM products");
$products_count = $db->single()->total;

$db->query("SELECT COUNT(*) AS total FROM orders");
$orders_count = $db->single()->total;

$db->query("SELECT COUNT(*) AS total FROM users");
$users_count = $db->single()->total;

$db->query("SELECT SUM(total) AS total FROM orders WHERE status = 'completed'");
$revenue = $db->single()->total;

// Get recent orders
$db->query("SELECT o.id, u.name, o.total, o.created_at 
           FROM orders o 
           JOIN users u ON o.user_id = u.id 
           ORDER BY o.created_at DESC LIMIT 5");
$recent_orders = $db->resultSet();

// Get popular products
$db->query("SELECT p.title, COUNT(oi.id) AS order_count 
           FROM order_items oi 
           JOIN products p ON oi.product_id = p.id 
           GROUP BY p.id 
           ORDER BY order_count DESC LIMIT 5");
$popular_products = $db->resultSet();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-primary/10 rounded-lg mr-4">
                    <i class="fas fa-book text-primary text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600">Total Products</p>
                    <p class="text-2xl font-bold"><?= $products_count ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-secondary/10 rounded-lg mr-4">
                    <i class="fas fa-shopping-cart text-secondary text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold"><?= $orders_count ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-accent/10 rounded-lg mr-4">
                    <i class="fas fa-users text-accent text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold"><?= $users_count ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-primary/10 rounded-lg mr-4">
                    <i class="fas fa-dollar-sign text-primary text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold">$<?= number_format($revenue, 2) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Recent Orders</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-left py-3 px-4">Order ID</th>
                            <th class="text-left py-3 px-4">Customer</th>
                            <th class="text-left py-3 px-4">Amount</th>
                            <th class="text-left py-3 px-4">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr class="border-b">
                                <td class="py-3 px-4">#<?= $order->id ?></td>
                                <td class="py-3 px-4"><?= $order->name ?></td>
                                <td class="py-3 px-4">$<?= number_format($order->total, 2) ?></td>
                                <td class="py-3 px-4"><?= date('M d, Y', strtotime($order->created_at)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="<?= BASE_URL ?>/admin/orders/list.php" class="text-primary hover:underline">View All Orders</a>
            </div>
        </div>

        <!-- Popular Products -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Popular Products</h2>
            <div class="space-y-4">
                <?php foreach ($popular_products as $product): ?>
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-16 bg-gray-100 rounded mr-4 flex items-center justify-center">
                                <i class="fas fa-book text-gray-400"></i>
                            </div>
                            <h3 class="font-medium"><?= $product->title ?></h3>
                        </div>
                        <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm">
                            <?= $product->order_count ?> orders
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4 text-right">
                <a href="<?= BASE_URL ?>/admin/products/list.php" class="text-primary hover:underline">View All Products</a>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../includes/footer.php'; ?>