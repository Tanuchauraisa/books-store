<?php

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../classes/Order.php';

// Ensure user is admin
redirectIfNotAdmin();

$order = new Order();
$orders = $order->getAllOrders();

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $status = trim($_POST['status']);
    
    if ($order->updateOrderStatus($order_id, $status)) {
        $_SESSION['admin_message'] = "Order status updated successfully";
        header("Location: list.php");
        exit();
    } else {
        $_SESSION['admin_error'] = "Failed to update order status";
    }
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Manage Orders</h1>
    
    <?php if (isset($_SESSION['admin_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= $_SESSION['admin_message'] ?>
            <?php unset($_SESSION['admin_message']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['admin_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?= $_SESSION['admin_error'] ?>
            <?php unset($_SESSION['admin_error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="text-left py-3 px-4">Order ID</th>
                    <th class="text-left py-3 px-4">Customer</th>
                    <th class="text-left py-3 px-4">Date</th>
                    <th class="text-left py-3 px-4">Total</th>
                    <th class="text-left py-3 px-4">Status</th>
                    <th class="text-left py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr class="border-b">
                        <td class="py-3 px-4">#<?= $order->id ?></td>
                        <td class="py-3 px-4"><?= $order->name ?></td>
                        <td class="py-3 px-4"><?= date('M d, Y', strtotime($order->created_at)) ?></td>
                        <td class="py-3 px-4">$<?= number_format($order->total, 2) ?></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs 
                                <?= $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' ?>
                                <?= $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                                <?= $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' ?>">
                                <?= ucfirst($order->status) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="view.php?id=<?= $order->id ?>" class="text-primary hover:underline">View</a>
                            <form method="post" class="inline-block ml-2">
                                <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                <select name="status" onchange="this.form.submit()" 
                                        class="border border-gray-300 rounded px-2 py-1 text-sm">
                                    <option value="pending" <?= $order->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= $order->status === 'processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="shipped" <?= $order->status === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="completed" <?= $order->status === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="cancelled" <?= $order->status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($orders)): ?>
            <div class="text-center py-12">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">No orders found</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>