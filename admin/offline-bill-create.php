<?php
session_start();
include(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
    exit;
}

// Fetch all products
$products = $conn->query("SELECT id, name, price, stock FROM products ORDER BY name ASC");
$products_list = array();
while ($product = $products->fetch_assoc()) {
    $products_list[] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../logo.png">
    <title>Offline Bill Creator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Create Offline Bill</h1>
        <a href="dashboard" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-6 inline-block">Back to Dashboard</a>

        <div class="bg-white p-8 rounded shadow">
            <form method="POST" action="offline-bill.php" id="billForm">
                <!-- Customer Information -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Customer Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-semibold mb-2">Customer Name *</label>
                            <input type="text" name="user_name" id="user_name" required class="w-full border px-4 py-2 rounded" placeholder="Enter customer name">
                        </div>
                        <div>
                            <label class="block font-semibold mb-2">Phone Number *</label>
                            <input type="text" name="phone" id="phone" required class="w-full border px-4 py-2 rounded" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Address</label>
                        <input type="text" name="address" id="address" class="w-full border px-4 py-2 rounded" placeholder="Enter customer address">
                    </div>
                </div>

                <!-- Products Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Select Products</h2>
                    
                    <div class="border rounded">
                        <div class="grid grid-cols-12 gap-4 bg-gray-200 p-4 font-bold">
                            <div class="col-span-5">Product Name</div>
                            <div class="col-span-2">Price</div>
                            <div class="col-span-2">Stock</div>
                            <div class="col-span-3">Quantity</div>
                        </div>

                        <div id="productsContainer">
                            <?php foreach ($products_list as $idx => $product) { ?>
                                <div class="grid grid-cols-12 gap-4 p-4 border-t items-center">
                                    <div class="col-span-5">
                                        <input type="hidden" name="products[<?php echo $idx; ?>][id]" value="<?php echo $product['id']; ?>">
                                        <label><?php echo htmlspecialchars($product['name']); ?></label>
                                    </div>
                                    <div class="col-span-2">
                                        रु<?php echo number_format($product['price'], 2); ?>
                                        <input type="hidden" name="products[<?php echo $idx; ?>][price]" value="<?php echo $product['price']; ?>">
                                    </div>
                                    <div class="col-span-2">
                                        <?php echo $product['stock']; ?>
                                    </div>
                                    <div class="col-span-3">
                                        <input type="number" name="products[<?php echo $idx; ?>][quantity]" min="0" max="<?php echo $product['stock']; ?>" value="0" class="w-full border px-2 py-1 rounded text-center qty-input" data-price="<?php echo $product['price']; ?>">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Manual Products Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">Add Manual Products</h2>
                        <button type="button" id="addManualItem" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 font-semibold">+ Add Item</button>
                    </div>

                    <div class="border rounded">
                        <div class="grid grid-cols-12 gap-4 bg-gray-200 p-4 font-bold">
                            <div class="col-span-5">Item Name</div>
                            <div class="col-span-2">Price</div>
                            <div class="col-span-2">Quantity</div>
                            <div class="col-span-3">Action</div>
                        </div>
                        <div id="manualItemsContainer"></div>
                    </div>
                </div>

                <!-- Total Section -->
                <div class="mb-8 bg-gray-100 p-4 rounded">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold">Total Amount:</span>
                        <span class="text-3xl font-bold text-blue-600">रु <span id="totalAmount">0.00</span></span>
                    </div>
                </div>

                <!-- Hidden input to store selected items -->
                <input type="hidden" name="selected_items" id="selected_items" value="">

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 font-semibold">Generate Bill</button>
                    <a href="dashboard" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 font-semibold">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qtyInputs = document.querySelectorAll('.qty-input');
            const manualContainer = document.getElementById('manualItemsContainer');
            const addManualItemBtn = document.getElementById('addManualItem');
            let manualItemIndex = 0;

            function createManualRow(index) {
                const row = document.createElement('div');
                row.className = 'grid grid-cols-12 gap-4 p-4 border-t items-center manual-item-row';
                row.innerHTML = `
                    <div class="col-span-5">
                        <input type="text" name="manual_items[${index}][name]" class="w-full border px-3 py-2 rounded manual-name" placeholder="Enter item name">
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="manual_items[${index}][price]" min="0" step="0.01" value="0" class="w-full border px-3 py-2 rounded manual-price" placeholder="Price">
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="manual_items[${index}][quantity]" min="0" value="0" class="w-full border px-3 py-2 rounded manual-qty" placeholder="Qty">
                    </div>
                    <div class="col-span-3">
                        <button type="button" class="remove-manual-item bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Remove</button>
                    </div>
                `;
                return row;
            }
            
            function calculateTotal() {
                let total = 0;
                qtyInputs.forEach(input => {
                    const qty = parseInt(input.value) || 0;
                    const price = parseFloat(input.dataset.price);
                    total += qty * price;
                });
                document.querySelectorAll('.manual-item-row').forEach(row => {
                    const qty = parseInt(row.querySelector('.manual-qty')?.value) || 0;
                    const price = parseFloat(row.querySelector('.manual-price')?.value) || 0;
                    total += qty * price;
                });
                document.getElementById('totalAmount').textContent = total.toFixed(2);
            }

            qtyInputs.forEach(input => {
                input.addEventListener('change', calculateTotal);
                input.addEventListener('input', calculateTotal);
            });

            addManualItemBtn.addEventListener('click', function() {
                manualContainer.appendChild(createManualRow(manualItemIndex++));
                calculateTotal();
            });

            manualContainer.addEventListener('input', function(e) {
                if (e.target.matches('.manual-name, .manual-price, .manual-qty')) {
                    calculateTotal();
                }
            });

            manualContainer.addEventListener('click', function(e) {
                if (e.target.matches('.remove-manual-item')) {
                    e.target.closest('.manual-item-row')?.remove();
                    calculateTotal();
                }
            });

            manualContainer.appendChild(createManualRow(manualItemIndex++));
            calculateTotal();

            // Form submission validation
            document.getElementById('billForm').addEventListener('submit', function(e) {
                const userName = document.getElementById('user_name').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const qtyInputs = document.querySelectorAll('.qty-input');
                
                let hasItems = false;
                qtyInputs.forEach(input => {
                    if (parseInt(input.value) > 0) {
                        hasItems = true;
                    }
                });

                document.querySelectorAll('.manual-item-row').forEach(row => {
                    const name = row.querySelector('.manual-name')?.value.trim();
                    const qty = parseInt(row.querySelector('.manual-qty')?.value) || 0;
                    if (name && qty > 0) {
                        hasItems = true;
                    }
                });

                if (!userName) {
                    alert('Please enter customer name');
                    e.preventDefault();
                    return;
                }

                if (!phone) {
                    alert('Please enter phone number');
                    e.preventDefault();
                    return;
                }

                if (!hasItems) {
                    alert('Please select at least one product or add a manual item');
                    e.preventDefault();
                    return;
                }
            });
        });
    </script>
</body>
</html>
