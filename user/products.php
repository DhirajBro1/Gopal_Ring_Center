<?php
session_start();
include(__DIR__ . '/../includes/db.php');


// Redirect to login if not logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch all products
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6GZ4E1XQ75"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-6GZ4E1XQ75');
</script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo.png">
    <title>Products - Gopal Ring Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">

<!-- Navbar -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo and Brand -->
            <div class="flex items-center gap-3">
                <img src="../logo.png" alt="Gopal Ring Center Logo" class="h-12 w-auto">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Gopal Ring Center</h1>
                    <p class="text-xs text-gray-600">Premium Cement & Clay Products</p>
                </div>
            </div>
            
            <!-- Nav Links -->
            <div class="flex items-center gap-4">
                <a href="/" class="text-gray-700 hover:text-amber-600 font-medium transition">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="cart" class="relative text-gray-700 hover:text-amber-600 font-medium transition">
                    <i class="fas fa-shopping-cart mr-2 text-lg"></i>Cart
                </a>
                <a href="dashboard" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">
                    <i class="fas fa-user mr-2"></i>Dashboard
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Page Header -->
<div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold flex items-center gap-3 mb-2">
            <i class="fas fa-box"></i> Our Products
        </h2>
        <p class="text-amber-100">Browse our premium collection of cement and clay products</p>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Filter/Search Section -->
    <div class="mb-8 flex items-center gap-4">
        <a href="landing" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php while($row = $products->fetch_assoc()) { ?>
            <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden group">
                
                <!-- Image Container -->
                <div class="relative bg-gray-100 h-56 overflow-hidden">
                    <img src="../public/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="w-full h-full object-contain group-hover:scale-110 transition duration-300 p-4">
                    
                    <!-- Stock Badge -->
                    <?php if($row['stock'] < 5 && $row['stock'] > 0): ?>
                        <div class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> Low Stock
                        </div>
                    <?php elseif($row['stock'] == 0): ?>
                        <div class="absolute top-3 right-3 bg-gray-700 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            <i class="fas fa-ban"></i> Out of Stock
                        </div>
                    <?php else: ?>
                        <div class="absolute top-3 right-3 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            <i class="fas fa-check"></i> In Stock
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <!-- Name -->
                    <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2 h-14">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </h3>
                    
                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 h-10">
                        <?php echo htmlspecialchars($row['description']); ?>
                    </p>
                    
                    <!-- Price Section -->
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="text-3xl font-bold text-amber-600 mb-2">
                            रु <?php echo htmlspecialchars($row['price']); ?>
                        </div>
                        <div class="text-sm text-gray-500">
                            Stock Available: <span class="font-bold text-gray-700"><?php echo htmlspecialchars($row['stock']); ?> units</span>
                        </div>
                    </div>
                    
                    <!-- Add to Cart Form -->
                    <?php if($row['stock'] > 0): ?>
                        <form method="POST" action="cart" class="space-y-3">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-semibold text-gray-700">Quantity:</label>
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($row['stock']); ?>" 
                                       class="flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-600 transition">
                            </div>
                            
                            <button type="submit" name="add_to_cart" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 text-white py-3 rounded-lg font-bold hover:from-amber-700 hover:to-amber-800 transition flex items-center justify-center gap-2">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    <?php else: ?>
                        <button disabled class="w-full bg-gray-400 text-white py-3 rounded-lg font-bold cursor-not-allowed">
                            Out of Stock
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- No Products Message -->
    <?php if($products->num_rows == 0): ?>
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <div class="mb-6">
                <i class="fas fa-box-open text-6xl text-gray-300"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">No Products Available</h2>
            <p class="text-gray-600">Check back soon for our latest products!</p>
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <!-- About Footer -->
            <div>
                <h4 class="text-xl font-bold mb-4">Gopal Ring Center</h4>
                <p class="text-gray-400">Premium cement and clay products for your home, garden & business.</p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-xl font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="landing" class="hover:text-amber-400 transition">Home</a></li>
                    <li><a href="products.php" class="hover:text-amber-400 transition">Products</a></li>
                    <li><a href="cart.php" class="hover:text-amber-400 transition">Cart</a></li>
                    <li><a href="dashboard" class="hover:text-amber-400 transition">Dashboard</a></li>
                </ul>
            </div>
            
            <!-- Help -->
            <div>
                <h4 class="text-xl font-bold mb-4">Help</h4>
                <p class="text-gray-400 text-sm">
                    Need assistance? Contact us at our customer service office or visit us in person.
                </p>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Gopal Ring Center. All rights reserved. | Premium Cement & Clay Products</p>
        </div>
    </div>
</footer>

</body>
</html>
