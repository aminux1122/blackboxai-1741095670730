<!-- Newsletter Section -->
    <section class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Stay Updated
                </h2>
                <p class="mt-4 text-lg leading-6 text-gray-500">
                    Subscribe to our newsletter for exclusive deals and restaurant updates.
                </p>
                <form class="mt-8 sm:flex justify-center">
                    <input type="email" required class="w-full px-5 py-3 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs border-gray-300 rounded-md" placeholder="Enter your email">
                    <button type="submit" class="mt-3 w-full px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:flex-shrink-0 sm:inline-flex sm:items-center sm:w-auto">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1">
                    <h3 class="text-white text-lg font-semibold mb-4">TableSpot</h3>
                    <p class="text-gray-400">Making restaurant reservations simple and convenient.</p>
                    <div class="mt-4 flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-span-1">
                    <h3 class="text-white text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="about.php" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="restaurants.php" class="text-gray-400 hover:text-white">Restaurants</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-white">Contact</a></li>
                        <li><a href="faq.php" class="text-gray-400 hover:text-white">FAQ</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="col-span-1">
                    <h3 class="text-white text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="help.php" class="text-gray-400 hover:text-white">Help Center</a></li>
                        <li><a href="terms.php" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                        <li><a href="privacy.php" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="careers.php" class="text-gray-400 hover:text-white">Careers</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-span-1">
                    <h3 class="text-white text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li>
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            123 Restaurant Street, Foodie City, FC 12345
                        </li>
                        <li>
                            <i class="fas fa-phone mr-2"></i>
                            +1 (555) 123-4567
                        </li>
                        <li>
                            <i class="fas fa-envelope mr-2"></i>
                            info@tablespot.com
                        </li>
                        <li>
                            <i class="fas fa-clock mr-2"></i>
                            Mon-Fri: 9:00 AM - 8:00 PM
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-700 pt-8">
                <p class="text-gray-400 text-center">&copy; <?php echo date('Y'); ?> TableSpot. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 bg-indigo-600 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" style="display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'flex';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Mobile Menu Toggle
        const mobileMenu = document.querySelector('.sm\\:hidden');
        const mobileMenuButton = document.querySelector('[aria-label="Main menu"]');
        
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Dropdown Menu
        const dropdownButton = document.querySelector('[x-data]');
        const dropdownMenu = dropdownButton?.nextElementSibling;
        
        if (dropdownButton && dropdownMenu) {
            dropdownButton.addEventListener('click', () => {
                const isOpen = dropdownMenu.classList.contains('hidden');
                dropdownMenu.classList.toggle('hidden', !isOpen);
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (event) => {
                if (!dropdownButton.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
