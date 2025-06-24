<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../classes/Database.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . BASE_URL . "/pages/login.php");
    exit();
}

$db = new Database();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_path = UPLOAD_PATH . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image = $file_name;
        }
    }
    
    $db->query("INSERT INTO products (title, author, price, description, image, category) 
               VALUES (:title, :author, :price, :description, :image, :category)");
    $db->bind(':title', $title);
    $db->bind(':author', $author);
    $db->bind(':price', $price);
    $db->bind(':description', $description);
    $db->bind(':image', $image);
    $db->bind(':category', $category);
    
    if ($db->execute()) {
        $_SESSION['admin_message'] = "Product added successfully!";
        header("Location: list.php");
        exit();
    } else {
        $error = "Failed to add product";
    }
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Add New Product</h1>
    
    <?php if(isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="post" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Author</label>
                    <input type="text" name="author" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        <option value="">Select Category</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Mystery">Mystery</option>
                        <option value="Romance">Romance</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2" required></textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Book Cover</label>
                <input type="file" name="image" class="w-full">
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition">
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>