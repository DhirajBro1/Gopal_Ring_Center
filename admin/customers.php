<?php
session_start();
include(__DIR__ . '/../includes/db.php');

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit;
}
// Handle customer deletion
if(isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Optional: Prevent admin from deleting themselves
    if($user_id != $_SESSION['admin_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='user'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['message'] = "Customer deleted successfully!";
        header("Location: customers");
        exit;
    } else {
        $_SESSION['message'] = "You cannot delete yourself!";
        header("Location: customers");
        exit;
    }
}

// Fetch all users (customers)
$users = $conn->query("SELECT id, name, email, phone, address, role, created_at FROM users WHERE role='user' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="../logo.png">
<title>Manage Customers</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<?php 
if(isset($_SESSION['message'])) {
    echo "<p class='text-green-600 mb-4'>".$_SESSION['message']."</p>";
    unset($_SESSION['message']);
}
?>

<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-6">Manage Customers</h1>
    <a href="dashboard" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-6 inline-block">Back to Dashboard</a>

    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded shadow">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2">ID</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Phone</th>
                    <th class="p-2">Address</th>
                    <th class="p-2">Role</th>
                    <th class="p-2">Registered On</th>
                </tr>
            </thead>
            <tbody>
                <?php if($users->num_rows > 0) { ?>
                    <?php while($user = $users->fetch_assoc()) { ?>
                        <tr class="border-b">
                            <td class="p-2"><?php echo $user['id']; ?></td>
                            <td class="p-2"><?php echo $user['name']; ?></td>
                            <td class="p-2"><?php echo $user['email']; ?></td>
                            <td class="p-2"><?php echo $user['phone']; ?></td>
                            <td class="p-2"><?php echo $user['address']; ?></td>
                            <td class="p-2"><?php echo ucfirst($user['role']); ?></td>
                            <td class="p-2"><?php echo $user['created_at']; ?></td>
                            <td class="p-2">
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                                </form>
                            </td>
                            </tr>

                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="7" class="p-2 text-center">No customers found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
