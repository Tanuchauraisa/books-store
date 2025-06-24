<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Order.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = new User();
$currentUser = $user->getUserById($_SESSION['user_id']);
$order = new Order();
$orders = $order->getOrdersByUser($_SESSION['user_id']);

// Handle account update
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Check if password change is requested
    $password_change = false;
    if (!empty($current_password)) {
        if (!password_verify($current_password, $currentUser->password)) {
            $errors[] = "Current password is incorrect";
        } elseif (empty($new_password)) {
            $errors[] = "New password is required";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "New password must be at least 6 characters";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "New passwords do not match";
        } else {
            $password_change = true;
        }
    }
    
    // Update user if no errors
    if (empty($errors)) {
        $update_data = [
            'name' => $name,
            'email' => $email
        ];
        
        if ($password_change) {
            $update_data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }
        
        if ($user->updateUser($currentUser->id, $update_data)) {
            // Update session
            $_SESSION['user_name'] = $name;
            $currentUser = $user->getUserById($_SESSION['user_id']);
            $success = "Account updated successfully";
        } else {
            $errors[] = "Failed to update account. Please try again.";
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Account</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Account Settings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Account Settings</h2>
                
                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <?= $success ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="post" class="space-y-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                               value="<?= htmlspecialchars($currentUser->name) ?>">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                               value="<?= htmlspecialchars($currentUser->email) ?>">
                    </div>
                    
                    <div class="border-t pt-6">
                        <h3 class="text-xl font-bold mb-4">Change Password</h3>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 mb-2">New Password</label>
                                <input type="password" name="new_password" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" name="confirm_password" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-primary-dark transition">
                        Update Account
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Order History -->
        <div>
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                <h2 class="text-2xl font-bold mb-6">Order History</h2>
                
                <?php if (empty($orders)): ?>
                    <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
                    <a href="products.php" class="text-primary hover:underline">Browse books</a>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($orders as $order): ?>
                            <div class="border-b pb-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold">Order #<?= $order->id ?></p>
                                        <p class="text-gray-600 text-sm"><?= date('M d, Y', strtotime($order->created_at)) ?></p>
                                    </div>
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                                        $<?= number_format($order->total, 2) ?>
                                    </span>
                                </div>
                                <p class="mt-2 text-sm">Status: 
                                    <span class="font-bold <?= $order->status === 'completed' ? 'text-green-600' : 'text-primary' ?>">
                                        <?= ucfirst($order->status) ?>
                                    </span>
                                </p>
                                <a href="#" class="text-primary hover:underline text-sm mt-2 inline-block">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-6 text-center">
                        <a href="#" class="text-primary hover:underline">View all orders</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>