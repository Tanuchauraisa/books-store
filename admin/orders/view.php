<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../classes/Order.php';
require_once __DIR__ . '/../../classes/Order.php';


// Ensure user is admin
redirectIfNotAdmin();

// Get order ID
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) {
    header("Location: list.php");
    exit();
}

// Get order details
$order = new Order();
$order_details = $order->getOrderDetails($order_id);
$order_items = $order->getOrderItems($order_id);

if (!$order_details) {
    header("Location: list.php");
    exit();
}




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
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Order #<?= $order_details->id ?></h1>
        <a href="list.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition">
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">Order Items</h2>
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-left py-3 px-4">Product</th>
                            <th class="text-left py-3 px-4">Price</th>
                            <th class="text-left py-3 px-4">Qty</th>
                            <th class="text-left py-3 px-4">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr class="border-b">
                                <td class="py-3 px-4"><?= $item->title ?></td>
                                <td class="py-3 px-4">$<?= number_format($item->price, 2) ?></td>
                                <td class="py-3 px-4"><?= $item->quantity ?></td>
                                <td class="py-3 px-4">$<?= number_format($item->price * $item->quantity, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right py-3 px-4 font-bold">Subtotal</td>
                            <td class="py-3 px-4">$<?= number_format($order_details->total, 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right py-3 px-4 font-bold">Shipping</td>
                            <td class="py-3 px-4">$0.00</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right py-3 px-4 font-bold">Total</td>
                            <td class="py-3 px-4 font-bold">$<?= number_format($order_details->total, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Order Status Update -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Update Status</h2>
                <form method="post" class="inline-block ml-2">
                    <input type="hidden" name="order_id" value="<?= $order_id ?>">
                    <select name="status" onchange="this.form.submit()" 
                            class="border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="pending" <?= $order_details->status === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order_details->status === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="shipped" <?= $order_details->status === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option value="completed" <?= $order_details->status === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $order_details->status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Customer Information -->
        <div>
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-bold mb-4">Customer Information</h2>
                
                <div class="mb-6">
                    <h3 class="font-bold mb-2">Shipping Address</h3>
                    <p><?= $order_details->shipping_name ?></p>
                    <p><?= $order_details->shipping_address ?></p>
                    <p><?= $order_details->shipping_city ?>, <?= $order_details->shipping_state ?> <?= $order_details->shipping_zip ?></p>
                </div>
                
                <div class="mb-6">
                    <h3 class="font-bold mb-2">Contact Information</h3>
                    <p>Email: <?= $order_details->shipping_email ?></p>
                    <p>Phone: <?= $order_details->phone ?? 'Not provided' ?></p>
                </div>
                
                <div>
                    <h3 class="font-bold mb-2">Order Details</h3>
                    <p>Order Date: <?= date('M j, Y', strtotime($order_details->created_at)) ?></p>
                    <p>Payment Method: <?= ucfirst($order_details->payment_method) ?></p>
                    <p>Status: 
                        <span class="px-2 py-1 rounded-full text-xs 
                            <?= $order_details->status === 'completed' ? 'bg-green-100 text-green-800' : '' ?>
                            <?= $order_details->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                            <?= $order_details->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' ?>
                            <?= $order_details->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' ?>">
                            <?= ucfirst($order_details->status) ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>