<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Product.php';

// Ensure user is admin
redirectIfNotAdmin();

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/admin/products/add.php");
    exit();
}

// Process form data
$data = [
    'title' => trim($_POST['title']),
    'author' => trim($_POST['author']),
    'price' => floatval($_POST['price']),
    'description' => trim($_POST['description']),
    'category' => trim($_POST['category']),
    'image' => ''
];

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = UPLOAD_PATH;
    $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
    $target_file = $target_dir . $file_name;
    
    // Check file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($imageFileType, $allowed_types)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $data['image'] = $file_name;
        }
    }
}

// Add product
$product = new Product();
$success = $product->addProduct($data);

if ($success) {
    $_SESSION['admin_message'] = "Product added successfully";
    header("Location: " . BASE_URL . "/admin/products/list.php");
} else {
    $_SESSION['admin_error'] = "Failed to add product";
    $_SESSION['form_data'] = $data;
    header("Location: " . BASE_URL . "/admin/products/add.php");
}
exit();
?>