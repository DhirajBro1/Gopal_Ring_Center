<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if(!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle product deletion
    if (isset($_POST['delete_product'])) {
        $product_id = intval($_POST['product_id']);
        
        // Fetch product to get image name
        $product_result = $conn->query("SELECT image FROM products WHERE id = $product_id");
        if ($product_result && $product_result->num_rows > 0) {
            $product = $product_result->fetch_assoc();
            $image_path = __DIR__ . "/../public/images/" . $product['image'];
            
            // Delete product from database
            if ($conn->query("DELETE FROM products WHERE id = $product_id")) {
                // Try to delete image file
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $success = "Product deleted successfully!";
            } else {
                $error = "Database error: " . $conn->error;
            }
        } else {
            $error = "Product not found!";
        }
    }
    // Handle product update
    elseif (isset($_POST['update_product'])) {
        $product_id = intval($_POST['product_id']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        
        $update_query = "UPDATE products SET price = $price, stock = $stock WHERE id = $product_id";
        if ($conn->query($update_query)) {
            $success = "Product updated successfully!";
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
    // Handle product addition
    elseif (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['stock'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        // Handle image upload
        $target_dir = __DIR__ . "/../public/images/";
        
        // Create folder if not exists
        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                $error = "Failed to create image directory. Please contact administrator.";
            }
        }
        
        // Check if directory is writable
        if (!is_writable($target_dir)) {
            $error = "Image directory is not writable. Please contact administrator.";
        }
        
        if (!isset($error) && isset($_FILES['image'])) {
            // Check for file upload errors
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $upload_errors = array(
                    UPLOAD_ERR_INI_SIZE => 'File is too large (server limit).',
                    UPLOAD_ERR_FORM_SIZE => 'File is too large (form limit).',
                    UPLOAD_ERR_PARTIAL => 'File upload was incomplete.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary directory.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION => 'Upload stopped by extension.'
                );
                $error = isset($upload_errors[$_FILES['image']['error']]) ? $upload_errors[$_FILES['image']['error']] : 'Unknown upload error.';
            } else {
                $image = basename($_FILES['image']['name']);
                $target_file = $target_dir . $image;
                
                // Validate file type
                $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
                $file_type = mime_content_type($target_file) ?: $_FILES['image']['type'];
                
                if (!in_array($file_type, $allowed_types)) {
                    $error = "Invalid file type. Only JPEG, PNG, GIF, and WEBP are allowed.";
                } else if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $insert = $conn->query("INSERT INTO products (name, description, price, stock, image) VALUES ('$name','$description','$price','$stock','$image')");
                    if($insert) {
                        $success = "Product added successfully!";
                    } else {
                        $error = "Database error: " . $conn->error;
                    }
                } else {
                    $error = "Failed to upload image. Please try again.";
                }
            }
        }
    }
}

// Fetch all products
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo.png">
    <title>Manage Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleEdit(productId) {
            const viewMode = document.getElementById('view-' + productId);
            const editMode = document.getElementById('edit-' + productId);
            
            if (viewMode.style.display === 'none') {
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
            } else {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
            }
        }
    </script>
    <a href="dashboard" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-6 inline-block">Back to Dashboard</a>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Manage Products</h1>
    <?php if(isset($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='text-green-500 mb-4'>$success</p>"; ?>

    <!-- Add Product Form -->
    <form method="POST" enctype="multipart/form-data" class="mb-6 bg-white p-4 rounded shadow-md w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Add New Product</h2>
        <input type="text" name="name" placeholder="Product Name" required class="w-full mb-2 px-3 py-2 border rounded">
        <textarea name="description" placeholder="Description" required class="w-full mb-2 px-3 py-2 border rounded"></textarea>
        <input type="number" name="price" placeholder="Price" step="0.01" required class="w-full mb-2 px-3 py-2 border rounded">
        <input type="number" name="stock" placeholder="Stock" required class="w-full mb-2 px-3 py-2 border rounded">
        <input type="file" name="image" accept="image/*" required class="w-full mb-2">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Product</button>
    </form>

    <!-- List of Products -->
    <div class="grid grid-cols-3 gap-4">
        <?php while($row = $products->fetch_assoc()) { ?>
            <div class="bg-white p-4 rounded shadow">
                <img src="../public/images/<?php echo $row['image']; ?>" alt="" class="w-full h-40 object-cover mb-2">
                <h3 class="font-bold"><?php echo $row['name']; ?></h3>
                
                <!-- View Mode -->
                <div id="view-<?php echo $row['id']; ?>">
                    <p>Price: रु<?php echo $row['price']; ?></p>
                    <p>Stock: <?php echo $row['stock']; ?></p>
                    <div class="flex gap-2 mt-2">
                        <button onclick="toggleEdit(<?php echo $row['id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex-1">Edit</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');" class="flex-1">
                            <input type="hidden" name="delete_product" value="1">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 w-full">Delete</button>
                        </form>
                    </div>
                </div>
                
                <!-- Edit Mode -->
                <div id="edit-<?php echo $row['id']; ?>" style="display:none;">
                    <form method="POST">
                        <input type="hidden" name="update_product" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <div class="mb-2">
                            <label class="block text-sm font-bold mb-1">Price (रु)</label>
                            <input type="number" name="price" value="<?php echo $row['price']; ?>" step="0.01" required class="w-full px-3 py-2 border rounded">
                        </div>
                        <div class="mb-2">
                            <label class="block text-sm font-bold mb-1">Stock</label>
                            <input type="number" name="stock" value="<?php echo $row['stock']; ?>" required class="w-full px-3 py-2 border rounded">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex-1">Save</button>
                            <button type="button" onclick="toggleEdit(<?php echo $row['id']; ?>)" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 flex-1">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>