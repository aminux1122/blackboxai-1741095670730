<?php
require_once 'includes/config.php';

// Check if restaurant is logged in
if (!is_restaurant_logged_in()) {
    header('Location: login.php');
    exit();
}

$reservation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? sanitize_input($_GET['action']) : '';
$restaurant_id = $_SESSION['restaurant_id'];

if (!in_array($action, ['confirm', 'cancel'])) {
    $_SESSION['error'] = 'Invalid action';
    header('Location: restaurant-dashboard.php');
    exit();
}

try {
    // First check if the reservation belongs to this restaurant
    $stmt = $conn->prepare("
        SELECT r.*, t.table_id, t.status as table_status 
        FROM reservations r
        JOIN tables t ON r.table_id = t.table_id
        WHERE r.reservation_id = ? AND r.restaurant_id = ? AND r.status = 'pending'
    ");
    $stmt->execute([$reservation_id, $restaurant_id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($reservation) {
        // Start transaction
        $conn->beginTransaction();
        
        if ($action === 'confirm') {
            // Update reservation status to confirmed
            $stmt = $conn->prepare("UPDATE reservations SET status = 'confirmed' WHERE reservation_id = ?");
            $stmt->execute([$reservation_id]);
            
            // Update table status to reserved for the reservation date
            $stmt = $conn->prepare("UPDATE tables SET status = 'reserved' WHERE table_id = ?");
            $stmt->execute([$reservation['table_id']]);
            
            $_SESSION['success'] = 'Reservation confirmed successfully';
        } else {
            // Update reservation status to cancelled
            $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE reservation_id = ?");
            $stmt->execute([$reservation_id]);
            
            // Update table status back to available
            $stmt = $conn->prepare("UPDATE tables SET status = 'available' WHERE table_id = ?");
            $stmt->execute([$reservation['table_id']]);
            
            $_SESSION['success'] = 'Reservation cancelled successfully';
        }
        
        // Commit transaction
        $conn->commit();
    } else {
        $_SESSION['error'] = 'Reservation not found or already processed';
    }
} catch(PDOException $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['error'] = 'Failed to update reservation. Please try again.';
}

header('Location: restaurant-dashboard.php');
exit();
?>
