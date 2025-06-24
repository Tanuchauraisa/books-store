    <!-- Footer -->
    <footer class="bg-dark text-white py-8 mt-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">BookNook</h3>
                    <p class="text-gray-400">Your online destination for all things books. Find your next favorite read with us.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="<?= BASE_URL ?>" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="<?= BASE_URL ?>/pages/products.php" class="text-gray-400 hover:text-white transition">Books</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Categories</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Best Sellers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Customer Service</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Shipping Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Returns & Refunds</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                            <span>123 Book Street, Reading City</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone mt-1 mr-3"></i>
                            <span>(123) 456-7890</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3"></i>
                            <span>info@booknook.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> BookNook. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>
</html>