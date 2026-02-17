<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Check if verified (optional)
            // if($user['verified'] == 0) {
            //     $error = "Please verify your email first.";
            // } else {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];


                // Redirect to dashboard
                header("Location: dashboard");
                exit;
            // }
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
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
    <title>Customer Login - Gopal Ring Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-3 mb-4">
                <img src="../logo.png" alt="Gopal Ring Center Logo" class="h-14 w-auto shadow-lg rounded-lg">
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Gopal Ring Center</h1>
            <p class="text-gray-600 mt-2">All cement products at one place</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Welcome Back
                </h2>
                <p class="text-amber-100 text-sm mt-1">Sign in to your account</p>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <?php if(isset($error)) echo "<div class='mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded'><p class='text-red-700 flex items-center gap-2'><i class='fas fa-exclamation-circle'></i>" . htmlspecialchars($error) . "</p></div>"; ?>

                <form method="POST" action="">
                    <!-- Email Field -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-amber-600"></i> Email Address
                        </label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-amber-600 transition duration-200 bg-gray-50"
                               placeholder="you@example.com">
                    </div>

                    <!-- Password Field -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-amber-600"></i> Password
                        </label>
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-amber-600 transition duration-200 bg-gray-50"
                               placeholder="Enter your password">
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-6">
                        <label class="flex items-center gap-2 text-gray-700 cursor-pointer text-sm">
                            <input type="checkbox" class="w-4 h-4 text-amber-600 rounded">
                            <span>Remember me</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold py-3 rounded-lg hover:from-amber-700 hover:to-amber-800 transition duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-right"></i> Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="text-gray-500 text-sm">New customer?</span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                <!-- Register Link -->
                <button onclick="window.location.href='register'" class="w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-200 transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i> Create an Account
                </button>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    By signing in, you agree to our <a href="#" class="text-amber-600 hover:underline">Terms of Service</a> and <a href="#" class="text-amber-600 hover:underline">Privacy Policy</a>
                </p>
            </div>
        </div>

        <!-- Security Badge -->
        <div class="mt-6 text-center text-sm text-gray-600">
            <p><i class="fas fa-shield-alt text-green-600"></i> Secure & Encrypted Login</p>
        </div>
    </div>
</body>
</html>
