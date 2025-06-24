<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../classes/Product.php';

// Ensure user is admin
redirectIfNotAdmin();

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/admin/products/list.php");
    exit();
}

// Get product ID
$product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($product_id <= 0) {
    header("Location: " . BASE_URL . "/admin/products/list.php");
    exit();
}

// Process form data
$data = [
    'title' => trim($_POST['title']),
    'author' => trim($_POST['author']),
    'price' => floatval($_POST['price']),
    'description' => trim($_POST['description']),
    'category' => trim($_POST['category']),
    'image' => trim($_POST['existing_image'])
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
            // Delete old image if exists
            if (!empty($data['image'])) {
                $old_file = $target_dir . $data['image'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            $data['image'] = $file_name;
        }
    }
}

// Update product
$product = new Product();
$success = $product->updateProduct($product_id, $data);

if ($success) {
    $_SESSION['admin_message'] = "Product updated successfully";
    header("Location: " . BASE_URL . "/admin/products/list.php");
} else {
    $_SESSION['admin_error'] = "Failed to update product";
    header("Location: " . BASE_URL . "/admin/products/edit.php?id=$product_id");
}
exit();
?>