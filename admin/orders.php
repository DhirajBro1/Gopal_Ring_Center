<?php
session_start();
include(__DIR__ . '/../includes/db.php');

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit;
}
// Handle order status update
if(isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();

    // Optional: flash message
    $_SESSION['message'] = "Order #$order_id status updated to $status";
    header("Location: orders"); // reload page to show update
    exit;
}

// Fetch all orders with full user info
$orders = $conn->query("
    SELECT o.id AS order_id, o.total_amount, o.status, o.created_at,
           u.name AS user_name, u.email, u.phone, u.address
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="../logo.png">
<title>Admin Orders</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-6">All Orders</h1>
    <a href="dashboard" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-6 inline-block">Back to Dashboard</a>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if($orders->num_rows > 0) { ?>
            <?php while($order = $orders->fetch_assoc()) { 
                // Fetch order items
                $items = $conn->query("
                    SELECT oi.quantity, oi.price, p.name 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = ".$order['order_id']
                );
            ?>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-2">Order #<?php echo $order['order_id']; ?></h2>

                <form method="POST" class="mb-4">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <label class="mr-2 font-semibold">Status:</label>
                    <select name="status" class="border px-2 py-1 rounded">
                        <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>Pending</option>
                        <option value="completed" <?php if($order['status']=='completed') echo 'selected'; ?>>Completed</option>
                        <option value="cancelled" <?php if($order['status']=='cancelled') echo 'selected'; ?>>Cancel</option>
                    </select>
                    <button type="submit" name="update_status" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 ml-2">Update</button>
                </form>

                <div class="mb-4">
                    <a href="bill?id=<?php echo $order['order_id']; ?>&lang=en" target="_blank" class="inline-block bg-amber-600 text-white px-3 py-1 rounded hover:bg-amber-700 mr-2">
                        Print Bill (EN)
                    </a>
                    <a href="bill?id=<?php echo $order['order_id']; ?>&lang=ne" target="_blank" class="inline-block bg-amber-600 text-white px-3 py-1 rounded hover:bg-amber-700">
                        Print Bill (NP)
                    </a>
                </div>

                <div class="mb-2">
                    <p><strong>User:</strong> <?php echo $order['user_name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
                    <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
                </div>

                <div class="mb-2">
                    <p><strong>Total Amount:</strong> रु<?php echo $order['total_amount']; ?></p>
                    <p><strong>Placed on:</strong> <?php echo $order['created_at']; ?></p>
                </div>

                <h3 class="font-semibold mt-4 mb-2">Items:</h3>
                <ul class="list-disc list-inside">
                    <?php while($item = $items->fetch_assoc()) { ?>
                        <li><?php echo $item['name']; ?> - Qty: <?php echo $item['quantity']; ?> - रु<?php echo $item['price']; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        <?php } else { ?>
            <p>No orders found.</p>
        <?php } ?>
    </div>
</body>
</html>
