<?php
require_once 'includes/config.php';

// Check if restaurant is logged in
if (!is_restaurant_logged_in()) {
    header('Location: login.php');
    exit();
}

$table_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$restaurant_id = $_SESSION['restaurant_id'];
$error = '';
$success = '';
$table = null;

// Get table information
try {
    $stmt = $conn->prepare("SELECT * FROM tables WHERE table_id = ? AND restaurant_id = ?");
    $stmt->execute([$table_id, $restaurant_id]);
    $table = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$table) {
        header('Location: restaurant-dashboard.php');
        exit();
    }
} catch(PDOException $e) {
    header('Location: restaurant-dashboard.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table_number = sanitize_input($_POST['table_number']);
    $capacity = sanitize_input($_POST['capacity']);
    $status = sanitize_input($_POST['status']);

    if (empty($table_number) || empty($capacity)) {
        $error = 'Please fill in all fields';
    } else {
        try {
            // Check if table number already exists for this restaurant (excluding current table)
            $stmt = $conn->prepare("SELECT table_id FROM tables WHERE restaurant_id = ? AND table_number = ? AND table_id != ?");
            $stmt->execute([$restaurant_id, $table_number, $table_id]);
            
            if ($stmt->rowCount() > 0) {
                $error = 'Table number already exists';
            } else {
                // Update table
                $stmt = $conn->prepare("UPDATE tables SET table_number = ?, capacity = ?, status = ? WHERE table_id = ? AND restaurant_id = ?");
                $stmt->execute([$table_number, $capacity, $status, $table_id, $restaurant_id]);
                
                $success = 'Table updated successfully';
                $table['table_number'] = $table_number;
                $table['capacity'] = $capacity;
                $table['status'] = $status;
            }
        } catch(PDOException $e) {
            $error = 'Failed to update table. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Table - TableSpot</title>
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
                        <a href="restaurant-dashboard.php" class="text-2xl font-bold text-indigo-600">TableSpot</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Edit Table Form -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Table</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Update the table information below.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $success; ?></span>
                    </div>
                <?php endif; ?>

                <form action="edit_table.php?id=<?php echo $table_id; ?>" method="POST">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="table_number" class="block text-sm font-medium text-gray-700">Table Number</label>
                                    <input type="text" name="table_number" id="table_number" value="<?php echo htmlspecialchars($table['table_number']); ?>" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                                    <input type="number" name="capacity" id="capacity" value="<?php echo htmlspecialchars($table['capacity']); ?>" required min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="available" <?php echo $table['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                                        <option value="reserved" <?php echo $table['status'] === 'reserved' ? 'selected' : ''; ?>>Reserved</option>
                                        <option value="occupied" <?php echo $table['status'] === 'occupied' ? 'selected' : ''; ?>>Occupied</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <a href="restaurant-dashboard.php" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
