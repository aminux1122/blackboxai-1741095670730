<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = sanitize_input($_POST['user_type']);
    
    if ($user_type === 'user') {
        // User registration
        $first_name = sanitize_input($_POST['first_name']);
        $last_name = sanitize_input($_POST['last_name']);
        $email = sanitize_input($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $phone = sanitize_input($_POST['phone']);

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
            $error = 'Please fill in all required fields';
        } elseif ($password !== $confirm_password) {
            $error = 'Passwords do not match';
        } else {
            try {
                // Check if email already exists
                $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->rowCount() > 0) {
                    $error = 'Email already registered';
                } else {
                    // Insert new user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$first_name, $last_name, $email, $hashed_password, $phone]);
                    
                    $success = 'Registration successful! Please login.';
                }
            } catch(PDOException $e) {
                $error = 'Registration failed. Please try again.';
            }
        }
    } else {
        // Restaurant registration
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $phone = sanitize_input($_POST['phone']);
        $address = sanitize_input($_POST['address']);
        $cuisine_type = sanitize_input($_POST['cuisine_type']);
        $opening_time = sanitize_input($_POST['opening_time']);
        $closing_time = sanitize_input($_POST['closing_time']);
        $max_capacity = sanitize_input($_POST['max_capacity']);

        if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($phone) || empty($address)) {
            $error = 'Please fill in all required fields';
        } elseif ($password !== $confirm_password) {
            $error = 'Passwords do not match';
        } else {
            try {
                // Check if email already exists
                $stmt = $conn->prepare("SELECT restaurant_id FROM restaurants WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->rowCount() > 0) {
                    $error = 'Email already registered';
                } else {
                    // Insert new restaurant
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO restaurants (name, email, password, phone, address, cuisine_type, opening_time, closing_time, max_capacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $email, $hashed_password, $phone, $address, $cuisine_type, $opening_time, $closing_time, $max_capacity]);
                    
                    $success = 'Registration successful! Please login.';
                }
            } catch(PDOException $e) {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TableSpot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="index.php" class="text-2xl font-bold text-indigo-600">TableSpot</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Create your account
                </h2>
            </div>
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <!-- Registration Type Selection -->
            <div class="flex justify-center space-x-4 mb-8">
                <button type="button" onclick="showForm('user')" class="px-4 py-2 bg-indigo-600 text-white rounded-md user-type-btn active">Register as Customer</button>
                <button type="button" onclick="showForm('restaurant')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md restaurant-type-btn">Register as Restaurant</button>
            </div>

            <!-- User Registration Form -->
            <form id="userForm" class="mt-8 space-y-6" action="register.php" method="POST">
                <input type="hidden" name="user_type" value="user">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="sr-only">First Name</label>
                            <input id="first_name" name="first_name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="First Name">
                        </div>
                        <div>
                            <label for="last_name" class="sr-only">Last Name</label>
                            <input id="last_name" name="last_name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Last Name">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address">
                    </div>
                    <div>
                        <label for="phone" class="sr-only">Phone</label>
                        <input id="phone" name="phone" type="tel" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Phone">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                    <div>
                        <label for="confirm_password" class="sr-only">Confirm Password</label>
                        <input id="confirm_password" name="confirm_password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Register
                    </button>
                </div>
            </form>

            <!-- Restaurant Registration Form -->
            <form id="restaurantForm" class="mt-8 space-y-6 hidden" action="register.php" method="POST">
                <input type="hidden" name="user_type" value="restaurant">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="restaurant_name" class="sr-only">Restaurant Name</label>
                        <input id="restaurant_name" name="name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Restaurant Name">
                    </div>
                    <div>
                        <label for="restaurant_email" class="sr-only">Email address</label>
                        <input id="restaurant_email" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email address">
                    </div>
                    <div>
                        <label for="restaurant_phone" class="sr-only">Phone</label>
                        <input id="restaurant_phone" name="phone" type="tel" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Phone">
                    </div>
                    <div>
                        <label for="address" class="sr-only">Address</label>
                        <textarea id="address" name="address" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Address" rows="3"></textarea>
                    </div>
                    <div>
                        <label for="cuisine_type" class="sr-only">Cuisine Type</label>
                        <input id="cuisine_type" name="cuisine_type" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Cuisine Type">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="opening_time" class="sr-only">Opening Time</label>
                            <input id="opening_time" name="opening_time" type="time" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                        </div>
                        <div>
                            <label for="closing_time" class="sr-only">Closing Time</label>
                            <input id="closing_time" name="closing_time" type="time" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="max_capacity" class="sr-only">Maximum Capacity</label>
                        <input id="max_capacity" name="max_capacity" type="number" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Maximum Capacity">
                    </div>
                    <div>
                        <label for="restaurant_password" class="sr-only">Password</label>
                        <input id="restaurant_password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                    <div>
                        <label for="restaurant_confirm_password" class="sr-only">Confirm Password</label>
                        <input id="restaurant_confirm_password" name="confirm_password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Register Restaurant
                    </button>
                </div>
            </form>

            <div class="text-sm text-center">
                <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Already have an account? Sign in
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="mt-8 border-t border-gray-700 pt-8">
                <p class="text-gray-400 text-center">&copy; 2023 TableSpot. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function showForm(type) {
            const userForm = document.getElementById('userForm');
            const restaurantForm = document.getElementById('restaurantForm');
            const userBtn = document.querySelector('.user-type-btn');
            const restaurantBtn = document.querySelector('.restaurant-type-btn');

            if (type === 'user') {
                userForm.classList.remove('hidden');
                restaurantForm.classList.add('hidden');
                userBtn.classList.add('bg-indigo-600', 'text-white');
                userBtn.classList.remove('bg-gray-200', 'text-gray-700');
                restaurantBtn.classList.add('bg-gray-200', 'text-gray-700');
                restaurantBtn.classList.remove('bg-indigo-600', 'text-white');
            } else {
                userForm.classList.add('hidden');
                restaurantForm.classList.remove('hidden');
                restaurantBtn.classList.add('bg-indigo-600', 'text-white');
                restaurantBtn.classList.remove('bg-gray-200', 'text-gray-700');
                userBtn.classList.add('bg-gray-200', 'text-gray-700');
                userBtn.classList.remove('bg-indigo-600', 'text-white');
            }
        }
    </script>
</body>
</html>
