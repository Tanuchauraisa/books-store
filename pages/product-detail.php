<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Database.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

$db = new Database();
$db->query("SELECT * FROM products WHERE id = :id");
$db->bind(':id', $id);
$product = $db->single();

if (!$product) {
    header("Location: products.php");
    exit();
}

// Get related products
$db->query("SELECT * FROM products WHERE category = :category AND id != :id ORDER BY RAND() LIMIT 4");
$db->bind(':category', $product->category);
$db->bind(':id', $id);
$related_products = $db->resultSet();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-8">
                <div class="bg-gray-100 rounded-xl p-4">
                    <img src="<?= BASE_URL ?>/assets/images/placeholder.jpg" alt="<?= $product->title ?>" class="w-full h-auto">
                </div>
            </div>
            <div class="md:w-2/3 p-8">
                <span class="text-sm font-semibold bg-gray-200 text-gray-700 px-2 py-1 rounded"><?= $product->category ?></span>
                <h1 class="text-3xl font-bold mt-4 mb-2"><?= $product->title ?></h1>
                <p class="text-xl text-gray-600 mb-4">by <?= $product->author ?></p>
                
                <div class="flex items-center mb-6">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="ml-2 text-gray-600">4.5 (120 reviews)</span>
                </div>
                
                <p class="text-3xl font-bold text-primary mb-6">$<?= number_format($product->price, 2) ?></p>
                
                <p class="text-gray-700 mb-8"><?= nl2br($product->description) ?></p>
                
                <div class="mb-8">
                    <h3 class="font-bold text-lg mb-2">Details</h3>
                    <ul class="grid grid-cols-2 gap-2">
                        <li class="flex items-center">
                            <i class="fas fa-book text-primary mr-2"></i>
                            <span>Format: Paperback</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-calendar text-primary mr-2"></i>
                            <span>Published: 2023</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-file text-primary mr-2"></i>
                            <span>Pages: 320</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-weight text-primary mr-2"></i>
                            <span>Dimensions: 5.5 x 8.5 inches</span>
                        </li>
                    </ul>
                </div>
                
                <div class="flex items-center space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <form method="post" action="<?= BASE_URL ?>/processes/add-to-cart.php" class="flex items-center">
                            <input type="hidden" name="product_id" value="<?= $product->id ?>">
                            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-full font-bold hover:bg-primary/90 transition flex items-center">
                                <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/pages/login.php" class="bg-primary text-white px-6 py-3 rounded-full font-bold hover:bg-primary/90 transition">Login to Purchase</a>
                    <?php endif; ?>
                    <button class="border border-primary text-primary px-6 py-3 rounded-full font-bold hover:bg-primary/10 transition">
                        <i class="far fa-heart"></i> Wishlist
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if(!empty($related_products)): ?>
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6">Related Books</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach($related_products as $related): ?>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="h-48 overflow-hidden flex items-center justify-center bg-gray-100">
                            <img src="<?= BASE_URL ?>/uploads/<?=$related->image?>" alt="<?= $related->title ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg"><?= $related->title ?></h3>
                            <p class="text-gray-600 text-sm">by <?= $related->author ?></p>
                            <div class="flex justify-between items-center mt-3">
                                <span class="font-bold text-primary">$<?= number_format($related->price, 2) ?></span>
                                <a href="<?= BASE_URL ?>/pages/product-detail.php?id=<?= $related->id ?>" class="text-sm text-primary hover:underline">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>