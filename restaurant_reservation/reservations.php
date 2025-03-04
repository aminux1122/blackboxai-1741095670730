<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

// Get user's reservations
try {
    $stmt = $conn->prepare("
        SELECT r.*, 
               res.name as restaurant_name, 
               res.address as restaurant_address,
               res.phone as restaurant_phone,
               t.table_number
        FROM reservations r
        JOIN restaurants res ON r.restaurant_id = res.restaurant_id
        JOIN tables t ON r.table_id = t.table_id
        WHERE r.user_id = ?
        ORDER BY r.reservation_date DESC, r.reservation_time DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = 'Failed to load reservations. Please try again.';
    $reservations = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - TableSpot</title>
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
                    <a href="profile.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Profile</a>
                    <a href="reservations.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">My Reservations</a>
                    <a href="logout.php" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Reservations List -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-2xl font-semibold text-gray-900">My Reservations</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if (empty($reservations)): ?>
                <div class="mt-4 text-center py-8">
                    <div class="text-gray-500">
                        <i class="fas fa-calendar-alt text-4xl mb-4"></i>
                        <p class="text-lg">You don't have any reservations yet.</p>
                        <a href="index.php" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">
                            Find a restaurant to make a reservation
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="mt-4 grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <?php echo htmlspecialchars($reservation['restaurant_name']); ?>
                                    </h3>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php 
                                        switch($reservation['status']) {
                                            case 'confirmed':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'cancelled':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            case 'completed':
                                                echo 'bg-gray-100 text-gray-800';
                                                break;
                                        }
                                        ?>">
                                        <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?>
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <?php echo date('F j, Y', strtotime($reservation['reservation_date'])); ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-clock mr-2"></i>
                                        <?php echo date('g:i A', strtotime($reservation['reservation_time'])); ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-users mr-2"></i>
                                        <?php echo htmlspecialchars($reservation['number_of_guests']); ?> guests
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-chair mr-2"></i>
                                        Table <?php echo htmlspecialchars($reservation['table_number']); ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <?php echo htmlspecialchars($reservation['restaurant_address']); ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-2"></i>
                                        <?php echo htmlspecialchars($reservation['restaurant_phone']); ?>
                                    </p>
                                </div>

                                <?php if ($reservation['special_requests']): ?>
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-900">Special Requests:</h4>
                                        <p class="mt-1 text-sm text-gray-600">
                                            <?php echo nl2br(htmlspecialchars($reservation['special_requests'])); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($reservation['status'] === 'pending' || $reservation['status'] === 'confirmed'): ?>
                                    <?php if (strtotime($reservation['reservation_date']) > strtotime('today')): ?>
                                        <div class="mt-4">
                                            <a href="cancel_reservation.php?id=<?php echo $reservation['reservation_id']; ?>" 
                                               onclick="return confirm('Are you sure you want to cancel this reservation?')"
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                Cancel Reservation
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
