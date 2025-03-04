<?php
require_once 'includes/config.php';

$restaurant_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$date = isset($_GET['date']) ? sanitize_input($_GET['date']) : date('Y-m-d');
$time = isset($_GET['time']) ? sanitize_input($_GET['time']) : '';
$guests = isset($_GET['guests']) ? (int)$_GET['guests'] : 2;

// Get restaurant information
try {
    $stmt = $conn->prepare("SELECT * FROM restaurants WHERE restaurant_id = ?");
    $stmt->execute([$restaurant_id]);
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$restaurant) {
        header('Location: index.php');
        exit();
    }

    // Get available tables
    $stmt = $conn->prepare("
        SELECT t.* 
        FROM tables t
        LEFT JOIN reservations r ON t.table_id = r.table_id 
            AND r.reservation_date = ? 
            AND r.status IN ('confirmed', 'pending')
        WHERE t.restaurant_id = ?
        AND t.capacity >= ?
        AND t.status = 'available'
        AND r.reservation_id IS NULL
        ORDER BY t.capacity ASC
    ");
    $stmt->execute([$date, $restaurant_id, $guests]);
    $available_tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get menu categories and items
    $stmt = $conn->prepare("
        SELECT c.*, i.*
        FROM menu_categories c
        LEFT JOIN menu_items i ON c.category_id = i.category_id
        WHERE c.restaurant_id = ?
        ORDER BY c.name, i.name
    ");
    $stmt->execute([$restaurant_id]);
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize menu items by category
    $menu = [];
    foreach ($menu_items as $item) {
        if (!isset($menu[$item['category_id']])) {
            $menu[$item['category_id']] = [
                'name' => $item['name'],
                'items' => []
            ];
        }
        if ($item['item_id']) {
            $menu[$item['category_id']]['items'][] = $item;
        }
    }

} catch(PDOException $e) {
    header('Location: index.php');
    exit();
}

// Handle reservation submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && is_logged_in()) {
    $table_id = sanitize_input($_POST['table_id']);
    $special_requests = sanitize_input($_POST['special_requests']);
    $reservation_time = sanitize_input($_POST['reservation_time']);

    try {
        // Verify table is still available
        $stmt = $conn->prepare("
            SELECT t.table_id 
            FROM tables t
            LEFT JOIN reservations r ON t.table_id = r.table_id 
                AND r.reservation_date = ? 
                AND r.status IN ('confirmed', 'pending')
            WHERE t.table_id = ?
            AND t.restaurant_id = ?
            AND t.status = 'available'
            AND r.reservation_id IS NULL
        ");
        $stmt->execute([$date, $table_id, $restaurant_id]);
        
        if ($stmt->rowCount() > 0) {
            // Create reservation
            $stmt = $conn->prepare("
                INSERT INTO reservations (user_id, restaurant_id, table_id, reservation_date, 
                    reservation_time, number_of_guests, special_requests, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([
                $_SESSION['user_id'],
                $restaurant_id,
                $table_id,
                $date,
                $reservation_time,
                $guests,
                $special_requests
            ]);
            
            $_SESSION['success'] = 'Reservation request submitted successfully!';
            header("Location: reservations.php");
            exit();
        } else {
            $error = 'Selected table is no longer available. Please try another table.';
        }
    } catch(PDOException $e) {
        $error = 'Failed to create reservation. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($restaurant['name']); ?> - TableSpot</title>
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

    <!-- Restaurant Header -->
    <div class="relative h-96">
        <?php if ($restaurant['image_url']): ?>
            <img class="w-full h-full object-cover" src="<?php echo htmlspecialchars($restaurant['image_url']); ?>" alt="<?php echo htmlspecialchars($restaurant['name']); ?>">
        <?php else: ?>
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <i class="fas fa-utensils text-6xl text-gray-400"></i>
            </div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                <h1 class="text-4xl font-bold"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
                <p class="mt-2 text-xl"><?php echo htmlspecialchars($restaurant['cuisine_type']); ?></p>
                <p class="mt-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <?php echo htmlspecialchars($restaurant['address']); ?>
                </p>
                <p class="mt-2">
                    <i class="fas fa-clock mr-2"></i>
                    <?php echo date('g:i A', strtotime($restaurant['opening_time'])); ?> - 
                    <?php echo date('g:i A', strtotime($restaurant['closing_time'])); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Menu Section -->
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold mb-6">Menu</h2>
            <?php if (empty($menu)): ?>
                <p class="text-gray-500">No menu items available</p>
            <?php else: ?>
                <?php foreach ($menu as $category): ?>
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4"><?php echo htmlspecialchars($category['name']); ?></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($category['items'] as $item): ?>
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <div class="flex justify-between">
                                        <h4 class="font-medium"><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <span class="text-indigo-600">$<?php echo number_format($item['price'], 2); ?></span>
                                    </div>
                                    <?php if ($item['description']): ?>
                                        <p class="text-gray-600 text-sm mt-2"><?php echo htmlspecialchars($item['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Reservation Section -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6">Make a Reservation</h2>
                
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!is_logged_in()): ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4">
                        Please <a href="login.php" class="underline">login</a> to make a reservation.
                    </div>
                <?php else: ?>
                    <form action="restaurant.php?id=<?php echo $restaurant_id; ?>" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" 
                                   min="<?php echo date('Y-m-d'); ?>" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Time</label>
                            <input type="time" name="reservation_time" value="<?php echo htmlspecialchars($time); ?>" required
                                   min="<?php echo $restaurant['opening_time']; ?>" 
                                   max="<?php echo $restaurant['closing_time']; ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Number of Guests</label>
                            <select name="guests" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <?php for($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo $guests == $i ? 'selected' : ''; ?>>
                                        <?php echo $i; ?> <?php echo $i == 1 ? 'Guest' : 'Guests'; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <?php if (!empty($available_tables)): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select Table</label>
                                <select name="table_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <?php foreach ($available_tables as $table): ?>
                                        <option value="<?php echo $table['table_id']; ?>">
                                            Table <?php echo htmlspecialchars($table['table_number']); ?> 
                                            (<?php echo $table['capacity']; ?> persons)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Special Requests</label>
                                <textarea name="special_requests" rows="3" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Any special requests or dietary requirements?"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                                Reserve Table
                            </button>
                        <?php else: ?>
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
                                No tables available for the selected date and party size.
                            </div>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
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
