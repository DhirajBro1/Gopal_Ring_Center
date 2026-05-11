<?php
session_start();
include(__DIR__ . '/../includes/db.php');

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit;
}

$admin_name = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo.png">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Welcome, <?php echo $admin_name; ?>!</h1>

        <div class="flex gap-4 mb-6 flex-wrap">
            <a href="products" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Manage Products</a>
            <a href="orders" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">View Orders</a>
            <a href="offline-bill-create" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Offline Billing</a>
            <a href="customers" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">Manage Customers</a>
            <a href="logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>

        <p>Use Billing / Print Bills to open Orders, then click Print Bill for any order.</p>
    </div>
</body>
</html>
