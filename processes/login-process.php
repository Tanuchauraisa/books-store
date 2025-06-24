<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $user = new User();
    $loggedInUser = $user->login($email, $password);
    
    if ($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser->id;
        $_SESSION['user_name'] = $loggedInUser->name;
        $_SESSION['user_role'] = $loggedInUser->role;
        
        // Redirect to original page or home
        $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : BASE_URL;
        unset($_SESSION['redirect_url']);
        
        header("Location: $redirect_url");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid email or password. Please try again.";
        header("Location: " . BASE_URL . "/pages/login.php");
        exit();
    }
}

header("Location: " . BASE_URL . "/pages/login.php");
exit();
?>