<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Database.php';

$db = new Database();
$db->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
$featured_products = $db->resultSet();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary text-white py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">Discover Your Next Favorite Book</h1>
        <p class="text-xl mb-8 max-w-2xl mx-auto">Explore thousands of titles across all genres. From bestsellers to hidden gems, we have something for every reader.</p>
        <a href="<?= BASE_URL ?>/pages/products.php" class="bg-white text-primary px-8 py-3 rounded-full font-bold text-lg hover:bg-gray-100 transition inline-block">Browse Books</a>
    </div>
</section>

<!-- Featured Books -->
<section class="py-16">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold mb-8 text-center">Featured Books</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php foreach ($featured_products as $product): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:scale-[1.02]">
                    <div class="h-56 overflow-hidden flex items-center justify-center bg-gray-100">
                        <img src="<?= BASE_URL ?>/uploads/<?= $product->image ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold bg-gray-200 text-gray-700 px-2 py-1 rounded"><?= $product->category ?></span>
                        <h3 class="font-bold text-xl mt-2 mb-1"><?= $product->title ?></h3>
                        <p class="text-gray-600 mb-2">by <?= $product->author ?></p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="font-bold text-lg text-primary">$<?= number_format($product->price, 2) ?></span>
                            <a href="<?= BASE_URL ?>/pages/product-detail.php?id=<?= $product->id ?>" class="bg-primary text-white px-4 py-2 rounded-full text-sm hover:bg-primary/90 transition">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold mb-8 text-center">Browse Categories</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-md text-center">
                <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hat-wizard text-2xl text-dark"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Fantasy</h3>
                <p class="text-gray-600">Magical worlds and epic adventures</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md text-center">
                <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-2xl text-accent"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Romance</h3>
                <p class="text-gray-600">Heartwarming love stories</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md text-center">
                <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-secret text-2xl text-dark"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Mystery</h3>
                <p class="text-gray-600">Thrilling puzzles to solve</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md text-center">
                <div class="w-16 h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-rocket text-2xl text-primary"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Sci-Fi</h3>
                <p class="text-gray-600">Futuristic worlds and technology</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>