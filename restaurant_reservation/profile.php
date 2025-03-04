<?php
$page_title = "My Profile";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

// Get user information
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get user's recent activity
    $stmt = $conn->prepare("
        SELECT r.*, res.name as restaurant_name, res.image_url
        FROM reservations r
        JOIN restaurants res ON r.restaurant_id = res.restaurant_id
        WHERE r.user_id = ?
        ORDER BY r.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $recent_activity = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get favorite restaurants
    $stmt = $conn->prepare("
        SELECT DISTINCT r.*, 
            (SELECT COUNT(*) FROM reservations WHERE restaurant_id = r.restaurant_id AND user_id = ?) as visit_count
        FROM restaurants r
        JOIN reservations res ON r.restaurant_id = res.restaurant_id
        WHERE res.user_id = ?
        ORDER BY visit_count DESC
        LIMIT 3
    ");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
    $favorite_restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $_SESSION['error'] = 'Failed to load profile information.';
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        if (empty($current_password)) {
            // Update profile without password change
            $stmt = $conn->prepare("
                UPDATE users 
                SET first_name = ?, last_name = ?, email = ?, phone = ?
                WHERE user_id = ?
            ");
            $stmt->execute([$first_name, $last_name, $email, $phone, $_SESSION['user_id']]);
            $_SESSION['success'] = 'Profile updated successfully';
        } else {
            // Verify current password
            $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $stored_password = $stmt->fetchColumn();

            if (password_verify($current_password, $stored_password)) {
                if ($new_password === $confirm_password) {
                    // Update profile with new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("
                        UPDATE users 
                        SET first_name = ?, last_name = ?, email = ?, phone = ?, password = ?
                        WHERE user_id = ?
                    ");
                    $stmt->execute([$first_name, $last_name, $email, $phone, $hashed_password, $_SESSION['user_id']]);
                    $_SESSION['success'] = 'Profile and password updated successfully';
                } else {
                    $_SESSION['error'] = 'New passwords do not match';
                }
            } else {
                $_SESSION['error'] = 'Current password is incorrect';
            }
        }
        
        // Refresh user data
        header('Location: profile.php');
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Failed to update profile';
    }
}
?>

<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-envelope flex-shrink-0 mr-1.5 text-gray-400"></i>
                                <?php echo htmlspecialchars($user['email']); ?>
                            </div>
                            <?php if ($user['phone']): ?>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-phone flex-shrink-0 mr-1.5 text-gray-400"></i>
                                    <?php echo htmlspecialchars($user['phone']); ?>
                                </div>
                            <?php endif; ?>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar flex-shrink-0 mr-1.5 text-gray-400"></i>
                                Member since <?php echo date('F Y', strtotime($user['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Profile Settings -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Profile Settings</h3>
                        
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form action="profile.php" method="POST">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                                    <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                                    <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone number</label>
                                    <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-2">Change Password</h4>
                                    <p class="text-sm text-gray-500 mb-4">Leave blank if you don't want to change your password</p>
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current password</label>
                                    <input type="password" name="current_password" id="current_password"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="new_password" class="block text-sm font-medium text-gray-700">New password</label>
                                    <input type="password" name="new_password" id="new_password"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm new password</label>
                                    <input type="password" name="confirm_password" id="confirm_password"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Recent Activity -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>
                        <?php if (empty($recent_activity)): ?>
                            <p class="text-gray-500">No recent activity</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($recent_activity as $activity): ?>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <?php if ($activity['image_url']): ?>
                                                <img class="h-12 w-12 rounded-full object-cover" src="<?php echo htmlspecialchars($activity['image_url']); ?>" alt="">
                                            <?php else: ?>
                                                <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-utensils text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($activity['restaurant_name']); ?>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <?php echo date('M j, Y', strtotime($activity['reservation_date'])); ?> at 
                                                <?php echo date('g:i A', strtotime($activity['reservation_time'])); ?>
                                            </p>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php 
                                                switch($activity['status']) {
                                                    case 'confirmed':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'pending':
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'cancelled':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?php echo ucfirst(htmlspecialchars($activity['status'])); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4">
                                <a href="reservations.php" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View all reservations →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Favorite Restaurants -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Favorite Restaurants</h3>
                        <?php if (empty($favorite_restaurants)): ?>
                            <p class="text-gray-500">No favorite restaurants yet</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($favorite_restaurants as $restaurant): ?>
                                    <a href="restaurant.php?id=<?php echo $restaurant['restaurant_id']; ?>" 
                                       class="block hover:bg-gray-50 transition duration-150 ease-in-out rounded-lg p-2">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <?php if ($restaurant['image_url']): ?>
                                                    <img class="h-12 w-12 rounded-full object-cover" src="<?php echo htmlspecialchars($restaurant['image_url']); ?>" alt="">
                                                <?php else: ?>
                                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-utensils text-gray-400"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($restaurant['name']); ?>
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($restaurant['cuisine_type']); ?>
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    Visited <?php echo $restaurant['visit_count']; ?> times
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Account Actions</h3>
                        <div class="space-y-3">
                            <a href="reservations.php" class="flex items-center text-gray-700 hover:text-indigo-600">
                                <i class="fas fa-calendar-alt w-5 h-5 mr-2"></i>
                                My Reservations
                            </a>
                            <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                                <i class="fas fa-heart w-5 h-5 mr-2"></i>
                                Saved Restaurants
                            </a>
                            <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                                <i class="fas fa-bell w-5 h-5 mr-2"></i>
                                Notification Settings
                            </a>
                            <a href="#" class="flex items-center text-red-600 hover:text-red-800">
                                <i class="fas fa-trash-alt w-5 h-5 mr-2"></i>
                                Delete Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
