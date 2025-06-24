<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Database.php';

$db = new Database();

// Get category filter if set
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query
$query = "SELECT * FROM products";
$params = [];

if (!empty($category)) {
    $query .= " WHERE category = :category";
    $params[':category'] = $category;
}

if (!empty($search)) {
    $query .= empty($category) ? " WHERE " : " AND ";
    $query .= "(title LIKE :search OR author LIKE :search)";
    $params[':search'] = "%$search%";
}

$query .= " ORDER BY created_at DESC";

$db->query($query);
foreach ($params as $param => $value) {
    $db->bind($param, $value);
}
$products = $db->resultSet();

// Get categories for filter
$db->query("SELECT DISTINCT category FROM products");
$categories = $db->resultSet();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Our Book Collection</h1>
    
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form action="" method="get" class="flex flex-col md:flex-row gap-4">
            <div class="flex-grow">
                <input type="text" name="search" placeholder="Search books..." class="w-full border border-gray-300 rounded-lg px-4 py-2" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div>
                <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat->category ?>" <?= $category == $cat->category ? 'selected' : '' ?>>
                            <?= $cat->category ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-bold hover:bg-primary/90 transition w-full">
                    Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach($products as $product): ?>
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:scale-[1.02]">
                <div class="h-56 overflow-hidden flex items-center justify-center bg-gray-100">
                    <img src="<?= BASE_URL ?>/uploads/<?=$product->image?>" alt="<?= $product->title ?>" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <span class="text-xs font-semibold bg-gray-200 text-gray-700 px-2 py-1 rounded"><?= $product->category ?></span>
                    <h3 class="font-bold text-xl mt-2 mb-1"><?= $product->title ?></h3>
                    <p class="text-gray-600 mb-2">by <?= $product->author ?></p>
                    <div class="flex justify-between items-center mt-4">
                        <span class="font-bold text-lg text-primary">$<?= number_format($product->price, 2) ?></span>
                        <div class="flex space-x-2">
                            <a href="<?= BASE_URL ?>/pages/product-detail.php?id=<?= $product->id ?>" class="bg-gray-200 text-dark px-3 py-2 rounded-full hover:bg-gray-300 transition">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <form method="post" action="<?= BASE_URL ?>/processes/add-to-cart.php">
                                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                    <button type="submit" class="bg-primary text-white px-3 py-2 rounded-full hover:bg-primary/90 transition">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if(empty($products)): ?>
            <div class="col-span-4 text-center py-12">
                <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold">No books found</h3>
                <p class="text-gray-600 mt-2">Try adjusting your search or filter criteria</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>