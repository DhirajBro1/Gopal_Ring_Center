<?php
session_start();
include(__DIR__ . '/../includes/db.php');

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
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
<link rel="icon" type="image/png" href="logo.png">
<title>Gopal Ring Center - Premium Cement & Clay Products</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

<!-- Navbar -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo and Brand -->
            <div class="flex items-center gap-3">
                <img src="logo.png" alt="Gopal Ring Center Logo" class="h-12 w-auto">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Gopal Ring Center</h1>
                    <p class="text-xs text-gray-600">Premium Cement & Clay Products</p>
                </div>
            </div>
            
            <!-- Nav Links -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#products" class="text-gray-700 hover:text-amber-600 font-medium transition">Products</a>
                <a href="#about" class="text-gray-700 hover:text-amber-600 font-medium transition">About</a>
                <a href="#contact" class="text-gray-700 hover:text-amber-600 font-medium transition">Contact</a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="user/dashboard" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">Dashboard</a>
                    <a href="user/logout" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">Logout</a>
                <?php else: ?>
                    <a href="user/login" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">Sign In</a>
                    <a href="user/register" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background with gradient overlay -->
    <img src="Back.jpg" alt="Background" class="absolute inset-0 w-full h-full object-cover z-0">
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/50 z-1"></div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-white">
        <div class="mb-6">
            <img src="logo.png" alt="Gopal Ring Center Logo" class="h-24 w-auto mx-auto mb-6 drop-shadow-lg">
        </div>
        <h1 class="text-5xl md:text-6xl font-bold mb-4 leading-tight">Crafting Quality Since 20+ Years</h1>
        <p class="text-xl md:text-2xl mb-8 text-gray-200">Premium cement & clay products for your home, garden & business</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#products" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 px-8 rounded-lg transition transform hover:scale-105">
                <i class="fas fa-shopping-bag mr-2"></i> Browse Products
            </a>
            <a href="#about" class="inline-block bg-white hover:bg-gray-100 text-amber-600 font-bold py-4 px-8 rounded-lg transition transform hover:scale-105">
                <i class="fas fa-info-circle mr-2"></i> Learn More
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">About Gopal Ring Center</h2>
            <div class="w-20 h-1 bg-amber-600 mx-auto mb-6"></div>
            
            <!-- Language Toggle -->
            <div class="flex justify-center gap-4 mb-8">
                <button onclick="showEnglish()" id="btnEnglish" class="px-6 py-2 bg-amber-600 text-white rounded-lg font-semibold transition">English</button>
                <button onclick="showNepali()" id="btnNepali" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold transition hover:bg-gray-400">नेपाली</button>
            </div>
        </div>
        
        <!-- English Content -->
        <div id="englishContent">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">20+ Years of Excellence</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Gopal Ring Center has been a trusted manufacturer of premium cement and clay products for over 20 years. We are committed to providing high-quality, durable, and aesthetically appealing products for your home and business needs.
                    </p>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Our extensive product range includes:
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> Plant Pots & Planters
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> Decorative Rings & Frames
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> Ventilation Products
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> Railings & Fencing
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> Home Pipes & Fixtures
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> Tulsi Stands & Garden Decor
                        </li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed">
                        Combining traditional craftsmanship with modern design, we create products that enhance the beauty and functionality of your spaces. Our mission is to provide durable, reliable, and affordable solutions for all your cement and clay product needs.
                    </p>
                </div>
                
                <!-- Stats -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-amber-50 to-amber-100 p-8 rounded-lg border-l-4 border-amber-600">
                        <div class="text-4xl font-bold text-amber-600 mb-2">20+</div>
                        <div class="text-gray-700 font-semibold">Years in Business</div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-8 rounded-lg border-l-4 border-blue-600">
                        <div class="text-4xl font-bold text-blue-600 mb-2">100%</div>
                        <div class="text-gray-700 font-semibold">Quality Assured</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-8 rounded-lg border-l-4 border-green-600">
                        <div class="text-4xl font-bold text-green-600 mb-2">5000+</div>
                        <div class="text-gray-700 font-semibold">Satisfied Customers</div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-8 rounded-lg border-l-4 border-purple-600">
                        <div class="text-4xl font-bold text-purple-600 mb-2">50+</div>
                        <div class="text-gray-700 font-semibold">Product Varieties</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Nepali Content -->
        <div id="nepaliContent" style="display:none;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">२० वर्षभन्दा बढीको उत्कृष्टता</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        गोपाल रिङ सेन्टरले २० वर्षभन्दा बढी समयदेखि सिमेन्ट र माटोका उच्च गुणस्तरीय उत्पादनहरू निर्माण गर्दै आएको छ। हामी तपाईंको घर र व्यवसायको लागि दिगो, आकर्षक र भरपर्दो सामग्रीहरू उपलब्ध गराउन प्रतिबद्ध छौं।
                    </p>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        हाम्रो व्यापक उत्पाद श्रृंखलमा निम्नलिखित समावेश छ:
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> गमला र बागको पात्रहरू
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> सजावटी रिङ र फ्रेमहरू
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> भेन्टिलेसन उत्पादनहरू
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> रेलिङ र बाडहरू
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> घरको पाइप र फिटिङहरू
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-600"></i> तुलसी मठ र बागको सजावट
                        </li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed">
                        परम्परागत हातकला र आधुनिक डिजाइनको संयोजनमार्फत हामी तपाईंको स्थानको सुन्दरता र कार्यक्षमता बढाउने उत्पादनहरू सिर्जना गर्दछौं। हाम्रो उद्देश्य सिमेन्ट र माटोका सम्पूर्ण उत्पाद आवश्यकताको लागि दिगो, भरपर्दो र सस्ता समाधान प्रदान गर्नु हो।
                    </p>
                </div>
                
                <!-- Stats -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-amber-50 to-amber-100 p-8 rounded-lg border-l-4 border-amber-600">
                        <div class="text-4xl font-bold text-amber-600 mb-2">20+</div>
                        <div class="text-gray-700 font-semibold">वर्षको व्यवसाय</div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-8 rounded-lg border-l-4 border-blue-600">
                        <div class="text-4xl font-bold text-blue-600 mb-2">100%</div>
                        <div class="text-gray-700 font-semibold">गुणस्तर निश्चित</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-8 rounded-lg border-l-4 border-green-600">
                        <div class="text-4xl font-bold text-green-600 mb-2">5000+</div>
                        <div class="text-gray-700 font-semibold">सन्तुष्ट ग्राहकहरू</div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-8 rounded-lg border-l-4 border-purple-600">
                        <div class="text-4xl font-bold text-purple-600 mb-2">50+</div>
                        <div class="text-gray-700 font-semibold">उत्पादको विविधता</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-16 bg-gray-100">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-4xl font-bold text-gray-800 text-center mb-12">Why Choose Gopal Ring Center?</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                <div class="text-amber-600 text-4xl mb-4"><i class="fas fa-award"></i></div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Premium Quality</h3>
                <p class="text-gray-700">Crafted with superior materials and attention to detail, our products are built to last.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                <div class="text-amber-600 text-4xl mb-4"><i class="fas fa-palette"></i></div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Modern Design</h3>
                <p class="text-gray-700">Contemporary designs that complement any space, from traditional to modern aesthetics.</p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                <div class="text-amber-600 text-4xl mb-4"><i class="fas fa-headset"></i></div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Expert Support</h3>
                <p class="text-gray-700">Dedicated customer service to help you find the perfect products for your needs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="products" class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Products</h2>
            <p class="text-gray-600 text-lg">Explore our wide range of premium cement and clay products</p>
            <div class="w-20 h-1 bg-amber-600 mx-auto mt-4"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php while($row = $products->fetch_assoc()) { ?>
            <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden group">
                <!-- Image Container -->
                <div class="relative bg-gray-100 h-48 overflow-hidden">
                    <img src="public/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="w-full h-full object-contain group-hover:scale-110 transition duration-300 p-4">
                    <?php if($row['stock'] < 5 && $row['stock'] > 0): ?>
                        <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Low Stock</div>
                    <?php elseif($row['stock'] == 0): ?>
                        <div class="absolute top-2 right-2 bg-gray-700 text-white px-3 py-1 rounded-full text-sm font-semibold">Out of Stock</div>
                    <?php endif; ?>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2"><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?php echo htmlspecialchars($row['description']); ?></p>
                    
                    <div class="mb-4">
                        <div class="text-3xl font-bold text-amber-600 mb-1">रु <?php echo htmlspecialchars($row['price']); ?></div>
                        <div class="text-sm text-gray-500">Stock: <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($row['stock']); ?></span></div>
                    </div>
                    
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="register" class="w-full block bg-amber-600 hover:bg-amber-700 text-white py-2 rounded-lg font-semibold text-center transition">
                            <i class="fas fa-lock mr-2"></i> Register to Buy
                        </a>
                    <?php else: ?>
                        <?php if($row['stock'] > 0): ?>
                            <form method="POST" action="cart" class="space-y-3">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($row['stock']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-600">
                                <button type="submit" name="add_to_cart" class="w-full bg-amber-600 hover:bg-amber-700 text-white py-2 rounded-lg font-semibold transition">
                                    <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                                </button>
                            </form>
                        <?php else: ?>
                            <button disabled class="w-full bg-gray-400 text-white py-2 rounded-lg font-semibold cursor-not-allowed">
                                Out of Stock
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-12">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <!-- About Footer -->
            <div>
                <h4 class="text-xl font-bold mb-4">Gopal Ring Center</h4>
                <p class="text-gray-400">Trusted manufacturer of premium cement and clay products since 2004.</p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-xl font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#products" class="hover:text-amber-400 transition">Products</a></li>
                    <li><a href="#about" class="hover:text-amber-400 transition">About Us</a></li>
                    <li><a href="register" class="hover:text-amber-400 transition">Register</a></li>
                    <li><a href="login" class="hover:text-amber-400 transition">Sign In</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div id="contact">
                <h4 class="text-xl font-bold mb-4">Contact Us</h4>
                <p class="text-gray-400 mb-2"><i class="fas fa-phone mr-2 text-amber-400"></i> +977-9817319154</p>
                <p class="text-gray-400 mb-2"><i class="fas fa-envelope mr-2 text-amber-400"></i> panditdhiraj296@gmail.com</p>
                <p class="text-gray-400"><i class="fas fa-map-marker-alt mr-2 text-amber-400"></i> Urlabari, Nepal</p>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Gopal Ring Center. All rights reserved. | Premium Cement & Clay Products</p>
        </div>
    </div>
</footer>

<script>
    function showEnglish() {
        document.getElementById('englishContent').style.display = 'block';
        document.getElementById('nepaliContent').style.display = 'none';
        document.getElementById('btnEnglish').classList.add('bg-amber-600', 'text-white');
        document.getElementById('btnEnglish').classList.remove('bg-gray-300', 'text-gray-700');
        document.getElementById('btnNepali').classList.remove('bg-amber-600', 'text-white');
        document.getElementById('btnNepali').classList.add('bg-gray-300', 'text-gray-700');
    }
    
    function showNepali() {
        document.getElementById('englishContent').style.display = 'none';
        document.getElementById('nepaliContent').style.display = 'block';
        document.getElementById('btnNepali').classList.add('bg-amber-600', 'text-white');
        document.getElementById('btnNepali').classList.remove('bg-gray-300', 'text-gray-700');
        document.getElementById('btnEnglish').classList.remove('bg-amber-600', 'text-white');
        document.getElementById('btnEnglish').classList.add('bg-gray-300', 'text-gray-700');
    }
</script>

</body>
</html>
