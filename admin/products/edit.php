<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../classes/Product.php';

// Ensure user is admin
redirectIfNotAdmin();

// Get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    header("Location: list.php");
    exit();
}

$product = new Product();
$prod = $product->getProductById($product_id);

if (!$prod) {
    header("Location: list.php");
    exit();
}

// Pre-fill form with existing data
$form_data = [
    'title' => $prod->title,
    'author' => $prod->author,
    'price' => $prod->price,
    'description' => $prod->description,
    'category' => $prod->category,
    'image' => $prod->image
];

// If form was submitted but had errors, use submitted data
if (isset($_SESSION['form_data'])) {
    $form_data = array_merge($form_data, $_SESSION['form_data']);
    unset($_SESSION['form_data']);
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Product</h1>
    
    <?php if (isset($_SESSION['admin_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?= $_SESSION['admin_error'] ?>
            <?php unset($_SESSION['admin_error']); ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="<?= BASE_URL ?>/processes/edit-product.php" enctype="multipart/form-data" class="bg-white rounded-xl shadow-md p-6">
        <input type="hidden" name="id" value="<?= $product_id ?>">
        <input type="hidden" name="existing_image" value="<?= $form_data['image'] ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 mb-2">Title</label>
                <input type="text" name="title" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                       value="<?= htmlspecialchars($form_data['title']) ?>">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Author</label>
                <input type="text" name="author" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                       value="<?= htmlspecialchars($form_data['author']) ?>">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Price ($)</label>
                <input type="number" name="price" step="0.01" min="0" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                       value="<?= $form_data['price'] ?>">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Category</label>
                <select name="category" required 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="Fiction" <?= $form_data['category'] === 'Fiction' ? 'selected' : '' ?>>Fiction</option>
                    <option value="Non-Fiction" <?= $form_data['category'] === 'Non-Fiction' ? 'selected' : '' ?>>Non-Fiction</option>
                    <option value="Fantasy" <?= $form_data['category'] === 'Fantasy' ? 'selected' : '' ?>>Fantasy</option>
                    <option value="Sci-Fi" <?= $form_data['category'] === 'Sci-Fi' ? 'selected' : '' ?>>Sci-Fi</option>
                    <option value="Mystery" <?= $form_data['category'] === 'Mystery' ? 'selected' : '' ?>>Mystery</option>
                    <option value="Romance" <?= $form_data['category'] === 'Romance' ? 'selected' : '' ?>>Romance</option>
                </select>
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="4" required 
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"><?= htmlspecialchars($form_data['description']) ?></textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Book Cover</label>
            
            <?php if ($form_data['image']): ?>
                <div class="mb-4 flex items-center">
                    <img src="<?= BASE_URL ?>/uploads/<?= $form_data['image'] ?>" alt="Current Cover" class="w-24 h-32 object-cover rounded-lg mr-4">
                    <div>
                        <p class="text-sm text-gray-600">Current image</p>
                        <label class="flex items-center mt-2">
                            <input type="checkbox" name="remove_image" value="1" class="mr-2">
                            <span class="text-sm">Remove current image</span>
                        </label>
                    </div>
                </div>
            <?php endif; ?>
            
            <input type="file" name="image" class="w-full">
            <p class="text-sm text-gray-500 mt-2">Upload a new image (JPG, PNG, GIF)</p>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-primary-dark transition">
                Update Product
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>