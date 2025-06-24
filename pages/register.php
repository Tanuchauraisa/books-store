<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL);
    exit();
}

$errors = [];
$name = '';
$email = '';

// Process registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
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
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if email exists
    $user = new User();
    if (empty($errors)) {
        if ($user->findUserByEmail($email)) {
            $errors[] = "Email already exists";
        }
    }
    
    // Create user if no errors
    if (empty($errors)) {
        $user_id = $user->register($name, $email, $password);
        
        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = 'customer';
            
            // Redirect to home page
            header("Location: " . BASE_URL);
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Create Your Account</h1>
        
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
                       value="<?= htmlspecialchars($name) ?>">
            </div>
            
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
                <p class="text-sm text-gray-500 mt-1">Must be at least 6 characters</p>
            </div>
            
            <div>
                <label class="block text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="confirm_password" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" id="terms" name="terms" required class="mr-2">
                <label for="terms" class="text-gray-700">I agree to the 
                    <a href="#" class="text-primary hover:underline">Terms & Conditions</a>
                </label>
            </div>
            
            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold hover:bg-primary-dark transition">
                Create Account
            </button>
            
            <div class="text-center mt-4">
                <p class="text-gray-600">Already have an account? 
                    <a href="login.php" class="text-primary font-bold hover:underline">Login</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>