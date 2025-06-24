<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    $errors = [];
    
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
    
    $user = new User();
    
    // Check if email exists
    if (empty($errors) && $user->findUserByEmail($email)) {
        $errors[] = "Email already exists";
    }
    
    if (empty($errors)) {
        $user_id = $user->register($name, $email, $password);
        
        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = 'customer';
            
            header("Location: " . BASE_URL);
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
    
    // Store errors in session
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_data'] = [
        'name' => $name,
        'email' => $email
    ];
}

header("Location: " . BASE_URL . "/pages/register.php");
exit();
?>