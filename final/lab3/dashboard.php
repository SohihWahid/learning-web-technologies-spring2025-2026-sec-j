<?php
session_start();

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_data']['name'];

// Initialize user stats
if (!isset($_SESSION['user_stats'][$user_name])) {
    $_SESSION['user_stats'][$user_name] = ['spent' => 0, 'tokens' => 0];
}

$message = "";

// Purchase Logic
if (isset($_POST['buy_item'])) {
    $target_id = $_POST['product_id'];

    // Find the product in the list
    foreach ($_SESSION['all_products'] as $key => $product) {
        if ($product['id'] === $target_id) {
            if ($product['stock'] > 0) {
                $final_price = $product['price'];

                // 50% discount if tokens >= 10
                if ($_SESSION['user_stats'][$user_name]['tokens'] >= 10) {
                    $final_price *= 0.5;
                }

                // Update Session Data
                $_SESSION['all_products'][$key]['stock']--;
                $_SESSION['user_stats'][$user_name]['spent'] += $final_price;
                $_SESSION['user_stats'][$user_name]['tokens'] = floor($_SESSION['user_stats'][$user_name]['spent'] / 1000);

                $message = "Successfully bought " . $product['name'] . "!";
            } else {
                $message = "Sorry, " . $product['name'] . " is out of stock!";
            }
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Shop</title>
    <style>
        body {
            font-family: sans-serif;
            background: #eee;
            margin: 0;
        }

        .top-nav {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .shop-container {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
            gap: 20px;
        }

        .product-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            width: 200px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .price {
            color: #28a745;
            font-weight: bold;
            font-size: 1.2em;
        }

        .btn-buy {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn-buy:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .token-badge {
            background: gold;
            color: #333;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="top-nav">
        <span>Welcome, <strong><?php echo $user_name; ?></strong></span>
        <span>My Tokens: <span class="token-badge">🪙
                <?php echo $_SESSION['user_stats'][$user_name]['tokens']; ?></span></span>
    </div>

    <div style="padding: 20px;">
        <h2>Available Products</h2>
        <?php if ($message)
            echo "<p style='background:white; padding:10px; border-left: 5px solid blue;'>$message</p>"; ?>

        <div class="shop-container">
            <?php if (!empty($_SESSION['all_products'])): ?>
                <?php foreach ($_SESSION['all_products'] as $product): ?>
                    <div class="product-card">
                        <h3><?php echo $product['name']; ?></h3>
                        <p class="price">$<?php echo $product['price']; ?></p>
                        <p>Stock: <?php echo $product['stock']; ?></p>

                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button name="buy_item" class="btn-buy" <?php echo ($product['stock'] <= 0) ? 'disabled' : ''; ?>>
                                <?php echo ($product['stock'] > 0) ? 'Buy Now' : 'Out of Stock'; ?>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>The shop is currently empty. Check back later!</p>
            <?php endif; ?>
        </div>
    </div>

    <div style="padding: 20px; border-top: 1px solid #ddd;">
        <a href="logout.php">Logout Securely</a>
    </div>

</body>

</html>