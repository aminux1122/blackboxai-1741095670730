<?php
require_once 'includes/config.php';

// Check if restaurant is logged in
if (!is_restaurant_logged_in()) {
    header('Location: login.php');
    exit();
}

$table_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$restaurant_id = $_SESSION['restaurant_id'];

try {
    // First check if the table belongs to this restaurant
    $stmt = $conn->prepare("SELECT table_id FROM tables WHERE table_id = ? AND restaurant_id = ?");
    $stmt->execute([$table_id, $restaurant_id]);
    
    if ($stmt->rowCount() > 0) {
        // Check if there are any future reservations for this table
        $stmt = $conn->prepare("
            SELECT reservation_id 
            FROM reservations 
            WHERE table_id = ? 
            AND reservation_date >= CURDATE() 
            AND status IN ('confirmed', 'pending')
        ");
        $stmt->execute([$table_id]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'Cannot delete table: There are future reservations for this table.';
        } else {
            // Delete the table
            $stmt = $conn->prepare("DELETE FROM tables WHERE table_id = ? AND restaurant_id = ?");
            $stmt->execute([$table_id, $restaurant_id]);
            
            $_SESSION['success'] = 'Table deleted successfully';
        }
    } else {
        $_SESSION['error'] = 'Table not found';
    }
} catch(PDOException $e) {
    $_SESSION['error'] = 'Failed to delete table. Please try again.';
}

header('Location: restaurant-dashboard.php');
exit();
?>
