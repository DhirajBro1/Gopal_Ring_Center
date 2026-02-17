<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if(!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}
$user_id = $_SESSION['user_id'] ?? 0;
if($user_id) {
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $user = $result->fetch_assoc();

    
}
// Initialize cart session
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if(isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Handle checkout
if(isset($_POST['checkout'])) {
    //check for verificaton
    // if($user['verified'] == 0) {
    //     // Show popup and stop further execution
    //     echo "<script>
    //             alert('हामी तपाईंलाई चाँडै VERIFY गर्नेछौं। VERIFICATION पछि मात्र तपाईं अर्डर गर्न सक्नुहुन्छ।');
    //             window.location.href = 'dashboard'; // redirect to homepage or desired page
    //           </script>";
    //     exit;}
    $user_id = $_SESSION['user_id'];
    $total_amount = 0;

    // Calculate total
    foreach($_SESSION['cart'] as $pid => $qty) {
        $product = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc();
        $total_amount += $product['price'] * $qty;
    }

    // Insert order
    $conn->query("INSERT INTO orders (user_id, total_amount) VALUES ($user_id, $total_amount)");
    $order_id = $conn->insert_id;

    // Insert order items
    foreach($_SESSION['cart'] as $pid => $qty) {
        $product = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc();
        $price = $product['price'];
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $pid, $qty, $price)");
    }

    // Clear cart
    $_SESSION['cart'] = [];
    $success = "Order placed successfully!";
}

// Fetch cart details
$cart_items = [];
foreach($_SESSION['cart'] as $pid => $qty) {
    $product = $conn->query("SELECT * FROM products WHERE id=$pid")->fetch_assoc();
    $product['quantity'] = $qty;
    $cart_items[] = $product;
}
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
    <title>Shopping Cart - Gopal Ring Center</title>
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
                <a href="products" class="text-gray-700 hover:text-amber-600 font-medium transition">
                    <i class="fas fa-shopping-bag mr-2"></i>Products
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
        <h2 class="text-4xl font-bold flex items-center gap-3">
            <i class="fas fa-shopping-cart"></i> Shopping Cart
        </h2>
        <p class="text-amber-100 mt-2">Review your items and proceed to checkout</p>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <?php if(isset($success)) { ?>
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <p class="text-green-700 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <strong><?php echo htmlspecialchars($success); ?></strong>
            </p>
        </div>
    <?php } ?>

    <?php if(count($cart_items) > 0) { ?>
        <form method="POST" class="grid lg:grid-cols-3 gap-8">
            <!-- Cart Items Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-box text-amber-600"></i>
                            Order Summary (<?php echo count($cart_items); ?> <?php echo count($cart_items) == 1 ? 'Item' : 'Items'; ?>)
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-200">
                        <?php foreach($cart_items as $item) { 
                            $total = $item['price'] * $item['quantity'];
                        ?>
                            <div class="flex items-center p-6 hover:bg-gray-50 transition">
                                <!-- Product Image -->
                                <div class="mr-6 flex-shrink-0">
                                    <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center p-2">
                                        <img src="../public/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="h-full w-full object-contain">
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-grow">
                                    <h4 class="text-lg font-bold text-gray-800 mb-1">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </h4>
                                    <p class="text-gray-600 text-sm mb-2">
                                        <?php echo htmlspecialchars(substr($item['description'], 0, 50)); ?>...
                                    </p>
                                    <div class="flex items-center gap-4">
                                        <span class="text-lg font-bold text-amber-600">
                                            रु <?php echo htmlspecialchars($item['price']); ?>
                                        </span>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">
                                            Qty: <?php echo htmlspecialchars($item['quantity']); ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Item Total -->
                                <div class="text-right ml-6">
                                    <p class="text-gray-600 text-sm mb-1">Item Total</p>
                                    <p class="text-2xl font-bold text-gray-800">
                                        रु <?php echo htmlspecialchars($total); ?>
                                    </p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <div class="mt-6">
                    <a href="products" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <!-- Order Total Box -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-24">
                    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-receipt"></i> Order Total
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <?php $grand_total = 0; ?>
                        <?php foreach($cart_items as $item) { 
                            $grand_total += $item['price'] * $item['quantity'];
                        } ?>

                        <div class="flex justify-between items-center py-4 bg-amber-50 px-4 rounded-lg border-2 border-amber-600">
                            <span class="text-lg font-bold text-gray-800">Grand Total</span>
                            <span class="text-2xl font-bold text-amber-600">रु <?php echo htmlspecialchars($grand_total); ?></span>
                        </div>

                        <button type="submit" name="checkout" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 text-white font-bold py-4 rounded-lg hover:from-amber-700 hover:to-amber-800 transition shadow-lg text-lg flex items-center justify-center gap-2 mt-6">
                            <i class="fas fa-credit-card"></i> Place Order
                        </button>
                    </div>
                </div>

                <!-- Payment Info Box - Sticky -->
                <div class="sticky top-24 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                    <h4 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Important
                    </h4>
                    <p class="text-blue-800 text-sm leading-relaxed">
                        चिन्ता नलिनुहोस्! अर्डर गर्नुको लागि कुनै भुक्तानी आवश्यक छैन। हामी तपाईंले दिएको नम्बरमा सम्पर्क गरेपछि मात्र कुरा अगाडि बढ्ने छ।
                    </p>
                </div>
            </div>
        </form>

    <?php } else { ?>
        <!-- Empty Cart -->
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <div class="mb-6">
                <i class="fas fa-shopping-cart text-6xl text-gray-300"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Your Cart is Empty</h2>
            <p class="text-gray-600 mb-8">Start shopping to add items to your cart!</p>
            <a href="products" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-lg transition">
                <i class="fas fa-shopping-bag mr-2"></i> Browse Products
            </a>
        </div>
    <?php } ?>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-6 text-center text-gray-400">
        <p>&copy; 2024 Gopal Ring Center. All rights reserved. | Premium Cement & Clay Products</p>
    </div>
</footer>

</body>
</html>
