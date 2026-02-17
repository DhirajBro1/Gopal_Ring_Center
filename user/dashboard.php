<?php
session_start();
include(__DIR__ . '/../includes/db.php');

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch recent orders
$orders = $conn->query("
    SELECT id, total_amount, status, created_at
    FROM orders
    WHERE user_id = $user_id
    ORDER BY created_at DESC
    LIMIT 5
");
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
    <title>User Dashboard - Gopal Ring Center</title>
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
                <a href="landing" class="text-gray-700 hover:text-amber-600 font-medium transition">
                    <i class="fas fa-home mr-1"></i>Home
                </a>
                <a href="products" class="text-gray-700 hover:text-amber-600 font-medium transition">
                    <i class="fas fa-shopping-bag mr-1"></i>Shop
                </a>
                <a href="cart" class="text-gray-700 hover:text-amber-600 font-medium transition">
                    <i class="fas fa-shopping-cart mr-1"></i>Cart
                </a>
                <a href="logout" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Welcome Section -->
<div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                <i class="fas fa-user text-amber-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-4xl font-bold">Welcome, <?php echo htmlspecialchars($user_name); ?>! 🎉</h2>
                <p class="text-amber-100">Here's your account dashboard</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Quick Action Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-12">
        <a href="products" class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2 text-center group">
            <div class="text-5xl text-amber-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Browse Products</h3>
            <p class="text-gray-600">Explore our premium collection</p>
        </a>

        <a href="cart" class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2 text-center group">
            <div class="text-5xl text-blue-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">My Cart</h3>
            <p class="text-gray-600">View and manage your cart</p>
        </a>

        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="text-5xl text-green-600 mb-4">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Total Orders</h3>
            <p class="text-3xl font-bold text-green-600"><?php echo $orders->num_rows; ?></p>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
            <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-history"></i> Recent Orders
            </h3>
        </div>

        <!-- Content -->
        <div class="p-6">
            <?php if($orders->num_rows > 0) { ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="px-6 py-4 text-left font-semibold text-gray-800">
                                    <i class="fas fa-hashtag text-amber-600 mr-2"></i>Order ID
                                </th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-800">
                                    <i class="fas fa-money-bill text-amber-600 mr-2"></i>Total Amount
                                </th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-800">
                                    <i class="fas fa-tag text-amber-600 mr-2"></i>Status
                                </th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-800">
                                    <i class="fas fa-calendar text-amber-600 mr-2"></i>Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php 
                            // Reset pointer to beginning
                            $orders->data_seek(0);
                            while($order = $orders->fetch_assoc()) { 
                                // Determine status badge color
                                $statusColor = 'bg-yellow-100 text-yellow-800';
                                if($order['status'] == 'completed') $statusColor = 'bg-green-100 text-green-800';
                                elseif($order['status'] == 'cancelled') $statusColor = 'bg-red-100 text-red-800';
                                elseif($order['status'] == 'processing') $statusColor = 'bg-blue-100 text-blue-800';
                            ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800">#<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td class="px-6 py-4 text-lg font-semibold text-amber-600">
                                        रु <?php echo htmlspecialchars($order['total_amount']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-4 py-2 rounded-full text-sm font-semibold <?php echo $statusColor; ?>">
                                            <?php echo ucfirst(htmlspecialchars($order['status'] ?? 'Pending')); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <i class="fas fa-calendar-day mr-2 text-amber-600"></i>
                                        <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mb-4">
                        <i class="fas fa-inbox text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No Orders Yet</h3>
                    <p class="text-gray-600 mb-6">Start shopping to place your first order!</p>
                    <a href="products" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-lg transition">
                        <i class="fas fa-shopping-bag mr-2"></i> Browse Products
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="mt-12 bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-600 p-8 rounded-lg">
        <h3 class="text-2xl font-bold text-blue-900 mb-4 flex items-center gap-2">
            <i class="fas fa-phone-alt"></i> Need Help?
        </h3>
        <div class="grid md:grid-cols-2 gap-6 text-blue-800">
            <p class="flex items-center gap-3">
                <i class="fas fa-phone text-2xl text-blue-600"></i>
                <div>
                    <strong>Phone:</strong><br>
                    <a href="tel:+977-9817319154" class="hover:underline">+977-981-7319154</a>
                </div>
            </p>
            <p class="flex items-center gap-3">
                <i class="fas fa-info-circle text-2xl text-blue-600"></i>
                <div>
                    <strong>Note:</strong><br>
                    For any queries or order updates, please feel free to contact us.
                </div>
            </p>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <div>
                <h4 class="text-xl font-bold mb-4">Gopal Ring Center</h4>
                <p class="text-gray-400">Premium cement and clay products for your home, garden & business.</p>
            </div>
            <div>
                <h4 class="text-xl font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="landing" class="hover:text-amber-400 transition">Home</a></li>
                    <li><a href="products" class="hover:text-amber-400 transition">Products</a></li>
                    <li><a href="cart" class="hover:text-amber-400 transition">Cart</a></li>
                    <li><a href="dashboard" class="hover:text-amber-400 transition">Dashboard</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-bold mb-4">Account</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="dashboard" class="hover:text-amber-400 transition">My Dashboard</a></li>
                    <li><a href="cart" class="hover:text-amber-400 transition">My Cart</a></li>
                    <li><a href="logout" class="hover:text-amber-400 transition">Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Gopal Ring Center. All rights reserved. | Premium Cement & Clay Products</p>
        </div>
    </div>
</footer>

</body>
</html>
