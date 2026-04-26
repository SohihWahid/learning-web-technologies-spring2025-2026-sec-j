<?php
session_start();

// Security: Check if User
if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_data']['name'];

// Initialize user stats if not set
if (!isset($_SESSION['user_stats'][$user_name])) {
    $_SESSION['user_stats'][$user_name] = [
        'spent' => 0,
        'tokens' => 0,
        'purchase_count' => 0
    ];
}

$message = "";
// Check if user currently has enough tokens for a discount
$has_discount = ($_SESSION['user_stats'][$user_name]['tokens'] >= 10);

// Purchase Logic
if (isset($_POST['buy_item'])) {
    $target_id = $_POST['product_id'];

    foreach ($_SESSION['all_products'] as $key => $product) {
        if ($product['id'] === $target_id) {
            if ($product['stock'] > 0) {
                $original_price = $product['price'];
                $final_price = $original_price;

                // 1. Handle Token Discount (10% off)
                if ($has_discount) {
                    $final_price = $original_price * 0.90;
                    $_SESSION['user_stats'][$user_name]['tokens'] -= 10;
                    $message = "Discount used! 10 tokens deducted. ";
                }

                // 2. Update Stock and Total Spent
                $_SESSION['all_products'][$key]['stock']--;
                $_SESSION['user_stats'][$user_name]['spent'] += $final_price;
                $_SESSION['user_stats'][$user_name]['purchase_count']++;

                // 3. FIXED TOKEN SYSTEM: 
                // We calculate tokens based on the TOTAL spent. 
                // floor(Total Spent / 1000) gives total tokens earned lifetime.
                // We then add any tokens they already had minus what they spent.
                $new_tokens = floor($final_price / 1000);
                if ($new_tokens > 0) {
                    $_SESSION['user_stats'][$user_name]['tokens'] += $new_tokens;
                }

                // Extra logic: if price is less than 1000, but the CUMULATIVE total 
                // just crossed a 1000 barrier, add a token.
                $old_total = $_SESSION['user_stats'][$user_name]['spent'] - $final_price;
                $new_total = $_SESSION['user_stats'][$user_name]['spent'];
                if (floor($new_total / 1000) > floor($old_total / 1000)) {
                    $_SESSION['user_stats'][$user_name]['tokens'] += 1;
                }

                $message .= "Successfully bought " . $product['name'] . "!";
                $has_discount = false; // Refresh state
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
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
        }

        .top-nav {
            background: #1a237e;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .stats-bar {
            background: #ffffff;
            padding: 15px 40px;
            display: flex;
            gap: 30px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            color: #333;
        }

        .shop-container {
            display: flex;
            flex-wrap: wrap;
            padding: 30px;
            gap: 20px;
        }

        .product-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            width: 220px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            border: 1px solid #eee;
        }

        /* Price Design */
        .original-price {
            color: #b0bec5;
            text-decoration: line-through;
            font-size: 1em;
            margin-bottom: 5px;
            display: block;
        }

        .discount-price {
            color: #d32f2f;
            font-weight: bold;
            font-size: 1.5em;
            display: block;
            margin-bottom: 10px;
        }

        .normal-price {
            color: #2e7d32;
            font-weight: bold;
            font-size: 1.5em;
            display: block;
            margin-bottom: 10px;
        }

        .btn-buy {
            background: #1a237e;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-buy:hover {
            background: #3949ab;
        }

        .btn-buy:disabled {
            background: #cfd8dc;
            cursor: not-allowed;
        }

        .token-badge {
            background: #ffd600;
            color: #000;
            padding: 4px 12px;
            border-radius: 15px;
        }

        .spent-badge {
            color: #1a237e;
        }

        .discount-tag {
            color: #d32f2f;
            font-size: 0.75em;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="top-nav">
        <h2>Shop Dashboard</h2>
        <div>
            <span>Welcome, <strong><?php echo $user_name; ?></strong></span>
        </div>
    </div>

    <div class="stats-bar">
        <span>Total Items Bought: <span
                class="spent-badge"><?php echo $_SESSION['user_stats'][$user_name]['purchase_count']; ?></span></span>
        <span>Total Amount Spent: <span
                class="spent-badge">$<?php echo number_format($_SESSION['user_stats'][$user_name]['spent'], 2); ?></span></span>
        <span>Available Tokens: <span class="token-badge">🪙
                <?php echo $_SESSION['user_stats'][$user_name]['tokens']; ?></span></span>
    </div>

    <div style="padding: 20px 40px;">
        <?php if ($message)
            echo "<div style='padding:15px; background:#e3f2fd; color:#0d47a1; border-radius:8px; margin-bottom:20px;'>$message</div>"; ?>

        <div class="shop-container">
            <?php if (!empty($_SESSION['all_products'])): ?>
                <?php foreach ($_SESSION['all_products'] as $product): ?>
                    <div class="product-card">
                        <h3 style="margin-top:0;"><?php echo $product['name']; ?></h3>

                        <?php if ($has_discount): ?>
                            <span class="discount-tag">Token Discount Active</span>
                            <span class="original-price">$<?php echo number_format($product['price'], 2); ?></span>
                            <span class="discount-price">$<?php echo number_format($product['price'] * 0.90, 2); ?></span>
                        <?php else: ?>
                            <span class="normal-price">$<?php echo number_format($product['price'], 2); ?></span>
                            <?php if ($_SESSION['user_stats'][$user_name]['tokens'] < 10): ?>
                                <p style="font-size:0.7em; color:#777;">Get 10 tokens for 10% off<br>(1 token per $1000)</p>
                            <?php endif; ?>
                        <?php endif; ?>

                        <p style="color:#555;">Stock: <strong><?php echo $product['stock']; ?></strong></p>

                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button name="buy_item" class="btn-buy" <?php echo ($product['stock'] <= 0) ? 'disabled' : ''; ?>>
                                <?php echo ($product['stock'] > 0) ? 'Purchase Now' : 'Out of Stock'; ?>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center; width:100%; color:#888;">
                    <h3>The shop is empty. Admin needs to add products!</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="padding: 0 40px 40px;">
        <a href="logout.php" style="color:#d32f2f; text-decoration:none; font-weight:bold;">Logout Account</a>
    </div>

</body>

</html>
