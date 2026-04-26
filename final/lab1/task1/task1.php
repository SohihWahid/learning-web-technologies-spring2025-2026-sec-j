<?php
session_start();

$message = '';
$messageType = '';
$lastName = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';

// Check if we have a message from the previous submission
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['messageType'];
    // Clear the message so it won't show on refresh
    unset($_SESSION['message']);
    unset($_SESSION['messageType']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $_SESSION['last_name'] = $name;
    if (!empty($name)) {
        $_SESSION['message'] = "Welcome, <strong>$name</strong>. Hover the mouse over here.";
        $_SESSION['messageType'] = 'welcome';
    } else {
        $_SESSION['message'] = "Please enter your name!";
        $_SESSION['messageType'] = 'warning';
    }
    // Redirect to same page - this clears POST data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Name</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (!empty($message)): ?>
        <?php if ($messageType == 'welcome'): ?>
            <p id='echo'><?php echo $message; ?></p>
        <?php else: ?>
            <p id='warning' style="color: red;">⚠️ <?php echo $message; ?></p>
        <?php endif; ?>
    <?php endif; ?>
    
    <form action="" method="post">
        <fieldset>
            <legend><h1>Name</h1></legend>
            <input type="text" id="name" name="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($lastName); ?>">
            <hr>
            <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
        </fieldset>
    </form>

    <script>
        const submitBtn = document.getElementById('submitBtn');
        let pressTimer;
        let colorTimer;

        submitBtn.addEventListener('mousedown', function() {
            colorTimer = setTimeout(function() {
                submitBtn.style.backgroundColor = 'red';
            }, 400); 
            

            pressTimer = setTimeout(function() {
                const name = document.getElementById('name').value;
                window.location.href = 'process.php?name=' + encodeURIComponent(name);
            }, 1000); 
        });

        submitBtn.addEventListener('mouseup', function() {
            clearTimeout(pressTimer);
            clearTimeout(colorTimer);
            submitBtn.style.backgroundColor = ''; 
        });

        submitBtn.addEventListener('mouseleave', function() {
            clearTimeout(pressTimer);
            clearTimeout(colorTimer);
            submitBtn.style.backgroundColor = ''; 
        });
    </script>
</body>
</html>
