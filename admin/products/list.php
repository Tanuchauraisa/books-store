<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../classes/Database.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . BASE_URL . "/pages/login.php");
    exit();
}

$db = new Database();

// Get products
$db->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $db->resultSet();

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Check if the product is referenced in the cart table
    $db->query("SELECT COUNT(*) as count FROM cart WHERE product_id = :id");
    $db->bind(':id', $delete_id);
    $referenceCount = $db->single()->count;

    if ($referenceCount > 0) {
        $_SESSION['admin_message'] = "Cannot delete product. It is referenced in the cart.";
    } else {
        $db->query("DELETE FROM products WHERE id = :id");
        $db->bind(':id', $delete_id);
        $db->execute();
        $_SESSION['admin_message'] = "Product deleted successfully!";
    }
    header("Location: list.php");
    exit();

    $_SESSION['admin_message'] = "Product deleted successfully!";
    header("Location: list.php");
    exit();
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Manage Products</h1>
        <a href="<?= BASE_URL ?>/admin/products/add.php" class="bg-primary text-white px-6 py-2 rounded-lg font-bold hover:bg-primary/90 transition">
            <i class="fas fa-plus mr-2"></i> Add Product
        </a>
    </div>

    <?php if (isset($_SESSION['admin_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= $_SESSION['admin_message'] ?>
            <?php unset($_SESSION['admin_message']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="text-left py-3 px-4">ID</th>
                    <th class="text-left py-3 px-4">Product</th>
                    <th class="text-left py-3 px-4">Author</th>
                    <th class="text-left py-3 px-4">Price</th>
                    <th class="text-left py-3 px-4">Category</th>
                    <th class="text-left py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr class="border-b">
                        <td class="py-3 px-4"><?= $product->id ?></td>
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-10 h-14 bg-gray-100 rounded mr-3 flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                                <span><?= $product->title ?></span>
                            </div>
                        </td>
                        <td class="py-3 px-4"><?= $product->author ?></td>
                        <td class="py-3 px-4">$<?= number_format($product->price, 2) ?></td>
                        <td class="py-3 px-4"><?= $product->category ?></td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="<?= BASE_URL ?>/admin/products/edit.php?id=<?= $product->id ?>" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="list.php?delete_id=<?= $product->id ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($products)): ?>
            <div class="text-center py-12">
                <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">No products found</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>