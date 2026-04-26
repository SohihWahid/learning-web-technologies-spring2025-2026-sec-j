<?php
session_start();

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_data']['name'];

if (!isset($_SESSION['user_stats'][$user_name])) {
    $_SESSION['user_stats'][$user_name] = ['spent' => 0, 'tokens' => 0];
}

$message = "";
$has_discount = ($_SESSION['user_stats'][$user_name]['tokens'] >= 10);

// Purchase Logic
if (isset($_POST['buy_item'])) {
    $target_id = $_POST['product_id'];

    foreach ($_SESSION['all_products'] as $key => $product) {
        if ($product['id'] === $target_id) {
            if ($product['stock'] > 0) {
                $original_price = $product['price'];
                $final_price = $original_price;

                // One-time 10% Discount Logic
                if ($has_discount) {
                    $final_price = $original_price * 0.90; // 10% off
                    $_SESSION['user_stats'][$user_name]['tokens'] -= 10; // Reset/Deduct 10 tokens
                    $message = "Discount Applied! 10 Tokens used.";
                }

                $_SESSION['all_products'][$key]['stock']--;
                $_SESSION['user_stats'][$user_name]['spent'] += $final_price;

                // Add new tokens based on new spending (1 per 1000)
                $_SESSION['user_stats'][$user_name]['tokens'] += floor($final_price / 1000);

                $message .= " Successfully bought " . $product['name'] . "!";
                $has_discount = false; // Disable for the rest of this page load
            } else {
                $message = "Out of stock!";
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
            width: 220px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Price Styling */
        .original-price {
            color: #888;
            text-decoration: line-through;
            font-size: 0.9em;
            display: block;
        }

        .discount-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.3em;
            display: block;
        }

        .normal-price {
            color: #28a745;
            font-weight: bold;
            font-size: 1.3em;
            display: block;
        }

        .btn-buy {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .token-badge {
            background: gold;
            color: #333;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
        }

        .discount-alert {
            color: #e74c3c;
            font-weight: bold;
            font-size: 0.8em;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="top-nav">
        <span>User: <strong><?php echo $user_name; ?></strong></span>
        <span>My Tokens: <span class="token-badge">🪙
                <?php echo $_SESSION['user_stats'][$user_name]['tokens']; ?></span></span>
    </div>

    <div style="padding: 20px;">
        <h2>Available Products</h2>
        <?php if ($message)
            echo "<p style='color: green; font-weight:bold;'>$message</p>"; ?>

        <div class="shop-container">
            <?php if (!empty($_SESSION['all_products'])): ?>
                <?php foreach ($_SESSION['all_products'] as $product): ?>
                    <div class="product-card">
                        <h3><?php echo $product['name']; ?></h3>

                        <?php if ($has_discount): ?>
                            <span class="discount-alert">🔥 10% TOKEN DISCOUNT</span>
                            <span class="original-price">$<?php echo $product['price']; ?></span>
                            <span class="discount-price">$<?php echo $product['price'] * 0.90; ?></span>
                        <?php else: ?>
                            <span class="normal-price">$<?php echo $product['price']; ?></span>
                            <?php if ($_SESSION['user_stats'][$user_name]['tokens'] < 10): ?>
                                <small style="color:#999">Need <?php echo (10 - $_SESSION['user_stats'][$user_name]['tokens']); ?> more
                                    tokens for discount</small>
                            <?php endif; ?>
                        <?php endif; ?>

                        <p>Stock: <?php echo $product['stock']; ?></p>

                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button name="buy_item" class="btn-buy" <?php echo ($product['stock'] <= 0) ? 'disabled' : ''; ?>>
                                Buy Now
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Shop is empty.</p>
            <?php endif; ?>
        </div>
    </div>

    <div style="padding: 20px;">
        <a href="logout.php">Logout</a>
    </div>

</body>

</html>
