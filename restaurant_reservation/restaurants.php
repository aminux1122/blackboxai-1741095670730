<?php
$page_title = "Restaurants";
require_once 'includes/config.php';
require_once 'includes/header.php';

// Get filter parameters
$cuisine = isset($_GET['cuisine']) ? sanitize_input($_GET['cuisine']) : '';
$price_range = isset($_GET['price_range']) ? sanitize_input($_GET['price_range']) : '';
$rating = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;
$sort = isset($_GET['sort']) ? sanitize_input($_GET['sort']) : 'name_asc';

// Build query
$query = "SELECT r.*, 
          AVG(rev.rating) as avg_rating,
          COUNT(DISTINCT rev.review_id) as review_count
          FROM restaurants r
          LEFT JOIN reviews rev ON r.restaurant_id = rev.restaurant_id
          WHERE 1=1";

if ($cuisine) {
    $query .= " AND r.cuisine_type = :cuisine";
}
if ($price_range) {
    $query .= " AND r.price_range = :price_range";
}
if ($rating > 0) {
    $query .= " HAVING avg_rating >= :rating";
}

$query .= " GROUP BY r.restaurant_id";

// Add sorting
switch ($sort) {
    case 'rating_desc':
        $query .= " ORDER BY avg_rating DESC";
        break;
    case 'reviews_desc':
        $query .= " ORDER BY review_count DESC";
        break;
    case 'name_desc':
        $query .= " ORDER BY r.name DESC";
        break;
    case 'name_asc':
    default:
        $query .= " ORDER BY r.name ASC";
}

try {
    $stmt = $conn->prepare($query);
    
    if ($cuisine) {
        $stmt->bindParam(':cuisine', $cuisine);
    }
    if ($price_range) {
        $stmt->bindParam(':price_range', $price_range);
    }
    if ($rating > 0) {
        $stmt->bindParam(':rating', $rating);
    }
    
    $stmt->execute();
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get cuisine types for filter
    $stmt = $conn->query("SELECT DISTINCT cuisine_type FROM restaurants WHERE cuisine_type IS NOT NULL ORDER BY cuisine_type");
    $cuisine_types = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch(PDOException $e) {
    $error = 'Failed to load restaurants. Please try again.';
    $restaurants = [];
    $cuisine_types = [];
}
?>

<!-- Hero Section -->
<div class="relative bg-gray-900">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Restaurants">
        <div class="absolute inset-0 bg-gray-900 mix-blend-multiply"></div>
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">Discover Restaurants</h1>
        <p class="mt-6 text-xl text-gray-300 max-w-3xl">
            Find and book the perfect restaurant for any occasion.
        </p>
    </div>
</div>

<!-- Filters and Restaurant List -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-3">
            <div class="bg-white shadow rounded-lg p-6 sticky top-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Filters</h2>
                <form action="restaurants.php" method="GET" class="space-y-6">
                    <!-- Cuisine Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cuisine Type</label>
                        <select name="cuisine" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Cuisines</option>
                            <?php foreach ($cuisine_types as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>" <?php echo $cuisine === $type ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price Range</label>
                        <select name="price_range" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Prices</option>
                            <option value="$" <?php echo $price_range === '$' ? 'selected' : ''; ?>>$ (Inexpensive)</option>
                            <option value="$$" <?php echo $price_range === '$$' ? 'selected' : ''; ?>>$$ (Moderate)</option>
                            <option value="$$$" <?php echo $price_range === '$$$' ? 'selected' : ''; ?>>$$$ (Expensive)</option>
                            <option value="$$$$" <?php echo $price_range === '$$$$' ? 'selected' : ''; ?>>$$$$ (Very Expensive)</option>
                        </select>
                    </div>

                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Minimum Rating</label>
                        <select name="rating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Any Rating</option>
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>" <?php echo $rating === $i ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>+ Stars
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort By</label>
                        <select name="sort" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                            <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
                            <option value="rating_desc" <?php echo $sort === 'rating_desc' ? 'selected' : ''; ?>>Highest Rated</option>
                            <option value="reviews_desc" <?php echo $sort === 'reviews_desc' ? 'selected' : ''; ?>>Most Reviewed</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Restaurant List -->
        <div class="mt-6 lg:mt-0 lg:col-span-9">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if (empty($restaurants)): ?>
                <div class="text-center py-12">
                    <i class="fas fa-utensils text-4xl text-gray-400"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No restaurants found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your filters to find more restaurants.</p>
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
                                <?php if (isset($restaurant['avg_rating'])): ?>
                                    <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded-md shadow">
                                        <div class="flex items-center">
                                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                                            <span class="text-sm font-medium"><?php echo number_format($restaurant['avg_rating'], 1); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($restaurant['name']); ?>
                                </h3>
                                <p class="mt-2 text-gray-600">
                                    <?php echo htmlspecialchars($restaurant['cuisine_type']); ?>
                                </p>
                                <p class="mt-2 text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <?php echo htmlspecialchars($restaurant['address']); ?>
                                </p>
                                <p class="mt-2 text-sm text-gray-500">
                                    <i class="fas fa-clock mr-2"></i>
                                    <?php echo date('g:i A', strtotime($restaurant['opening_time'])); ?> - 
                                    <?php echo date('g:i A', strtotime($restaurant['closing_time'])); ?>
                                </p>
                                <?php if (isset($restaurant['review_count'])): ?>
                                    <p class="mt-2 text-sm text-gray-500">
                                        <i class="fas fa-comment mr-2"></i>
                                        <?php echo $restaurant['review_count']; ?> reviews
                                    </p>
                                <?php endif; ?>
                                <div class="mt-4">
                                    <a href="restaurant.php?id=<?php echo $restaurant['restaurant_id']; ?>" 
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
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
