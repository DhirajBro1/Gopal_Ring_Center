<?php
include(__DIR__ . '/../includes/db.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // secure hash

    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        // Insert user
        $insert = $conn->query("INSERT INTO users (name, email, password, phone, address, role) VALUES ('$name','$email','$password','$phone','$address','user')");
        if ($insert) {
            $success = "Registration successful! You can now log in to your account.";
        } else {
            $error = "Registration failed: " . $conn->error;
        }
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
    <title>Create Account - Gopal Ring Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-3 mb-4">
                <img src="../logo.png" alt="Gopal Ring Center Logo" class="h-14 w-auto shadow-lg rounded-lg">
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Gopal Ring Center</h1>
            <p class="text-gray-600 mt-2">Join Our Community</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Create Your Account
                </h2>
                <p class="text-amber-100 text-sm mt-1">Join us to access products online</p>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <!-- Info Banner -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <p class="text-blue-700 text-sm flex items-start gap-2">
                        <i class="fas fa-info-circle mt-1 flex-shrink-0"></i>
                        <span><strong>Please use authentic information.</strong> We may contact you regarding orders and exclusive offers.</span>
                    </p>
                </div>

                <?php if(isset($error)) echo "<div class='mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded'><p class='text-red-700 flex items-center gap-2'><i class='fas fa-exclamation-circle'></i>" . htmlspecialchars($error) . "</p></div>"; ?>
                <?php if(isset($success)) echo "<div class='mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded'><p class='text-green-700 flex items-center gap-2'><i class='fas fa-check-circle'></i>" . htmlspecialchars($success) . "</p></div>"; ?>

                <form method="POST" action="">
                    <!-- Name Field -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-amber-600"></i> Full Name *
                        </label>
                        <input type="text" id="name" name="name" required 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-amber-600 transition duration-200 bg-gray-50"
                               placeholder="Enter your full name">
                    </div>

                    <!-- Email Field -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-amber-600"></i> Email Address *
                        </label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-amber-600 transition duration-200 bg-gray-50"
                               placeholder="you@example.com">
                    </div>

                    <!-- Phone Field -->
                    <div class="mb-5">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone text-amber-600"></i> Phone Number *
                        </label>
                        <input type="tel" id="phone" name="phone" required 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-amber-600 transition duration-200 bg-gray-50"
                               placeholder="+91 98765 43210">
                    </div>

                    <!-- Address Field -->
                    <div class="mb-5">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-amber-600"></i> Address / Location *
                        </label>
                        <textarea id="address" name="address" required rows="3"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-amber-600 transition duration-200 bg-gray-50 resize-none"
                                  placeholder="Enter your complete address"></textarea>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-amber-600"></i> Password *
                        </label>
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-amber-600 transition duration-200 bg-gray-50"
                               placeholder="Create a strong password">
                        <p class="text-xs text-gray-600 mt-2">Password must be at least 6 characters long</p>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="mb-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" required class="w-5 h-5 text-amber-600 rounded mt-1">
                            <span class="text-sm text-gray-700">
                                I agree to the <a href="#" class="text-amber-600 hover:underline font-medium">Terms of Service</a> and <a href="#" class="text-amber-600 hover:underline font-medium">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold py-3 rounded-lg hover:from-amber-700 hover:to-amber-800 transition duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-user-check"></i> Create Account
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="text-gray-500 text-sm">Already registered?</span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                <!-- Login Link -->
                <button onclick="window.location.href='login'" class="w-full bg-gray-100 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-200 transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Sign In to Existing Account
                </button>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    <i class="fas fa-shield-alt text-green-600"></i> Your information is secure and encrypted
                </p>
            </div>
        </div>

        <!-- Trust Indicators -->
        <div class="mt-6 grid grid-cols-3 gap-4 text-center">
            <div class="text-sm text-gray-600">
                <i class="fas fa-check-circle text-green-600 text-xl mb-2"></i>
                <p>100% Secure</p>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-truck text-blue-600 text-xl mb-2"></i>
                <p>Delivery Nationwide</p>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-headset text-amber-600 text-xl mb-2"></i>
                <p>24/7 Support</p>
            </div>
        </div>
    </div>
</body>
</html>
