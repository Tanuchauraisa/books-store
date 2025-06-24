<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNook - Your Online Bookstore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5b8fb9',
                        secondary: '#b6e2d3',
                        accent: '#ef7c8e',
                        light: '#f8f6f4',
                        gray: '#d8d9da',
                        dark: '#1b262c'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="bg-primary text-white shadow-lg">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="<?= BASE_URL ?>" class="text-2xl font-bold flex items-center">
                <i class="fas fa-book-open mr-2"></i>BookNook
            </a>
            
            <div class="flex items-center space-x-4">
                <a href="<?= BASE_URL ?>" class="hover:bg-white/20 px-3 py-2 rounded transition">Home</a>
                <a href="<?= BASE_URL ?>/pages/products.php" class="hover:bg-white/20 px-3 py-2 rounded transition">Books</a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/pages/cart.php" class="hover:bg-white/20 px-3 py-2 rounded transition flex items-center">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        Cart <?php if(count($_SESSION['cart']) > 0): ?><span class="ml-1 bg-accent text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?= count($_SESSION['cart']) ?></span><?php endif; ?>
                    </a>
                    <div class="relative group text-black">
                        <button class="flex items-center space-x-1 py-2 text-white ">
                            <i class="fas fa-user-circle"></i>
                            <span><?= $_SESSION['user_name'] ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-1 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block z-10">
                            <?php if($_SESSION['user_role'] == 'admin'): ?>
                                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="block px-4 py-2 text-black hover:bg-gray-100">Admin Panel</a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>/pages/account.php" class="block px-4 py-2 text-black hover:bg-gray-100">My Account</a>
                            <a href="<?= BASE_URL ?>/processes/logout-process.php" class="block px-4 py-2 text-black hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/pages/login.php" class="hover:bg-white/20 px-3 py-2 rounded transition">Login</a>
                    <a href="<?= BASE_URL ?>/pages/register.php" class="bg-secondary text-dark px-4 py-2 rounded font-medium hover:bg-secondary/80 transition">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>