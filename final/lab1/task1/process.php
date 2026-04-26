<?php
$name = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $name = htmlspecialchars($_POST['name']);
} elseif (isset($_GET['name'])) {
    $name = htmlspecialchars($_GET['name']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .process-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        .process-container h1 {
            margin-top: 0;
            color: #007bff;
        }
        .process-container button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="process-container">
        <?php if (!empty($name)): ?>
            <h1>Welcome, <?php echo $name; ?></h1>
            <p>Your Name is: <strong><?php echo $name; ?></strong></p>
            <p>Thank you for submitting the form.</p>
        <?php else: ?>
            <p>No data submitted. Please go back and fill the form.</p>
        <?php endif; ?>

        <form action="task1.php" method="get">
            <button type="submit" class="btn btn-primary">Go Back</button>
        </form>
    </div>
</body>
</html>