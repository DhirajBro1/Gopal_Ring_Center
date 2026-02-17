<?php
session_start();
include(__DIR__ . '/../includes/db.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if admin exists
    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND role='admin'");
    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            // Set session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            // Redirect to admin dashboard
            header("Location: dashboard");
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo.png">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>
        <?php if(isset($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-6">
                <label class="block mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Login</button>
        </form>
    </div>
</body>
</html>
