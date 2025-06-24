<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL);
    exit();
}

$error = '';
$email = '';

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $user = new User();
    $loggedInUser = $user->login($email, $password);
    
    if ($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser->id;
        $_SESSION['user_name'] = $loggedInUser->name;
        $_SESSION['user_role'] = $loggedInUser->role;
        
        // Redirect to home page
        header("Location: " . BASE_URL);
        exit();
    } else {
        $error = "Invalid email or password. Please try again.";
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Login to Your Account</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= $error ?>
            </div>
        <?php endif; ?>
        
        <form method="post" class="space-y-6">
            <div>
                <label class="block text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                       value="<?= htmlspecialchars($email) ?>">
            </div>
            
            <div>
                <label class="block text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" class="mr-2">
                    <label for="remember" class="text-gray-700">Remember me</label>
                </div>
                <a href="#" class="text-primary hover:underline">Forgot password?</a>
            </div>
            
            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold hover:bg-primary-dark transition">
                Login
            </button>
            
            <div class="text-center mt-4">
                <p class="text-gray-600">Don't have an account? 
                    <a href="register.php" class="text-primary font-bold hover:underline">Register</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>