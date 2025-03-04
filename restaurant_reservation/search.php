<?php
require_once 'includes/config.php';

$location = isset($_GET['location']) ? sanitize_input($_GET['location']) : '';
$date = isset($_GET['date']) ? sanitize_input($_GET['date']) : date('Y-m-d');
$time = isset($_GET['time']) ? sanitize_input($_GET['time']) : '';
$guests = isset($_GET['guests']) ? (int)$_GET['guests'] : 2;

try {
    // Search for restaurants based on location (name or address)
    $searchTerm = "%{$location}%";
    $stmt = $conn->prepare("
        SELECT DISTINCT r.*, 
        (SELECT COUNT(*) FROM tables t 
         WHERE t.restaurant_id = r.restaurant_id 
         AND t.status = 'available' 
         AND t.capacity >= ?) as available_tables
        FROM restaurants r
        WHERE (r.name LIKE ? OR r.address LIKE ?)
        AND r.opening_time <= COALESCE(?, CURTIME())
        AND r.closing_time >= COALESCE(?, CURTIME())
        HAVING available_tables > 0
        ORDER BY r.name ASC
    ");
    $stmt->execute([$guests, $searchTerm, $searchTerm, $time, $time]);
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = 'Search failed. Please try again.';
    $restaurants = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - TableSpot</title>
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
                <div class="flex items-center">
                    <?php if (is_logged_in()): ?>
                        <a href="profile.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Profile</a>
                        <a href="reservations.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">My Reservations</a>
                        <a href="logout.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="register.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 ml-3">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Form -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <form action="search.php" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="sm:col-span-1">
                    <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" placeholder="Location or restaurant" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-3">
                </div>
                <div>
                    <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" min="<?php echo date('Y-m-d'); ?>" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-3">
                </div>
                <div>
                    <input type="time" name="time" value="<?php echo htmlspecialchars($time); ?>" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-3">
                </div>
                <div>
                    <select name="guests" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-3">
                        <?php for($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $guests == $i ? 'selected' : ''; ?>>
                                <?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="sm:col-span-4">
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-md text-sm font-medium hover:bg-indigo-700">
                        Search Restaurants
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Results -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <?php if (empty($restaurants)): ?>
            <div class="text-center py-8">
                <h3 class="text-lg font-medium text-gray-900">No restaurants found</h3>
                <p class="mt-2 text-sm text-gray-500">Try adjusting your search criteria</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($restaurants as $restaurant): ?>
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="relative h-48">
                            <?php if ($restaurant['image_url']): ?>
                                <img class="w-full h-full object-cover" src="<?php echo htmlspecialchars($restaurant['image_url']); ?>" alt="<?php echo htmlspecialchars($restaurant['name']); ?>">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-utensils text-4xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                            <p class="mt-2 text-gray-600"><?php echo htmlspecialchars($restaurant['cuisine_type']); ?></p>
                            <p class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <?php echo htmlspecialchars($restaurant['address']); ?>
                            </p>
                            <p class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-clock mr-2"></i>
                                <?php echo date('g:i A', strtotime($restaurant['opening_time'])); ?> - 
                                <?php echo date('g:i A', strtotime($restaurant['closing_time'])); ?>
                            </p>
                            <div class="mt-4">
                                <span class="text-sm text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <?php echo $restaurant['available_tables']; ?> tables available
                                </span>
                            </div>
                            <div class="mt-4">
                                <a href="restaurant.php?id=<?php echo $restaurant['restaurant_id']; ?>&date=<?php echo urlencode($date); ?>&time=<?php echo urlencode($time); ?>&guests=<?php echo $guests; ?>" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    View Details & Reserve
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="mt-8 border-t border-gray-700 pt-8">
                <p class="text-gray-400 text-center">&copy; 2023 TableSpot. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
