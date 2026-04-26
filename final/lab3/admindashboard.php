<?php
session_start();

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize the products array if it doesn't exist
if (!isset($_SESSION['all_products'])) {
    $_SESSION['all_products'] = [];
}

if (isset($_POST['add_product'])) {
    // Add the new product to the list
    $_SESSION['all_products'][] = [
        'id' => uniqid(), // unique ID to identify which one is being bought
        'name' => $_POST['p_name'],
        'price' => (float) $_POST['p_price'],
        'stock' => (int) $_POST['p_stock']
    ];
    $msg = "Product added to shop!";
}

// Clear all products (optional helper)
if (isset($_POST['clear_shop'])) {
    $_SESSION['all_products'] = [];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Add Products</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 20px;
        }

        .form-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-add {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            cursor: pointer;
        }

        .btn-clear {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h1>Admin Panel</h1>
    <div class="form-box">
        <h3>Add New Product</h3>
        <?php if (isset($msg))
            echo "<p style='color:green'>$msg</p>"; ?>
        <form method="POST">
            <input type="text" name="p_name" placeholder="Product Name (e.g. Laptop)" required>
            <input type="number" name="p_price" placeholder="Price ($)" required>
            <input type="number" name="p_stock" placeholder="Stock Quantity" required>
            <button name="add_product" class="btn-add">Add Product to Dashboard</button>
        </form>

        <form method="POST">
            <button name="clear_shop" class="btn-clear">Reset All Products</button>
        </form>
    </div>
    <p><a href="logout.php">Logout</a></p>
</body>

</html>