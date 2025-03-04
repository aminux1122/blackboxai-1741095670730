<?php
require_once 'includes/config.php';

// Check if restaurant is logged in
if (!is_restaurant_logged_in()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table_number = sanitize_input($_POST['table_number']);
    $capacity = sanitize_input($_POST['capacity']);
    $restaurant_id = $_SESSION['restaurant_id'];

    // Validate input
    if (empty($table_number) || empty($capacity)) {
        $_SESSION['error'] = 'Please fill in all fields';
        header('Location: restaurant-dashboard.php');
        exit();
    }

    try {
        // Check if table number already exists for this restaurant
        $stmt = $conn->prepare("SELECT table_id FROM tables WHERE restaurant_id = ? AND table_number = ?");
        $stmt->execute([$restaurant_id, $table_number]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'Table number already exists';
        } else {
            // Insert new table
            $stmt = $conn->prepare("INSERT INTO tables (restaurant_id, table_number, capacity, status) VALUES (?, ?, ?, 'available')");
            $stmt->execute([$restaurant_id, $table_number, $capacity]);
            
            $_SESSION['success'] = 'Table added successfully';
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Failed to add table. Please try again.';
    }
}

header('Location: restaurant-dashboard.php');
exit();
?>
