<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

$reservation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

try {
    // Start transaction
    $conn->beginTransaction();

    // Get reservation details and verify ownership
    $stmt = $conn->prepare("
        SELECT r.*, t.table_id 
        FROM reservations r
        JOIN tables t ON r.table_id = t.table_id
        WHERE r.reservation_id = ? 
        AND r.user_id = ? 
        AND r.status IN ('pending', 'confirmed')
        AND r.reservation_date > CURDATE()
    ");
    $stmt->execute([$reservation_id, $user_id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reservation) {
        // Update reservation status to cancelled
        $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE reservation_id = ?");
        $stmt->execute([$reservation_id]);

        // Update table status back to available
        $stmt = $conn->prepare("UPDATE tables SET status = 'available' WHERE table_id = ?");
        $stmt->execute([$reservation['table_id']]);

        // Commit transaction
        $conn->commit();

        $_SESSION['success'] = 'Reservation cancelled successfully';
    } else {
        $_SESSION['error'] = 'Invalid reservation or cannot be cancelled';
    }
} catch(PDOException $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['error'] = 'Failed to cancel reservation. Please try again.';
}

header('Location: reservations.php');
exit();
?>
