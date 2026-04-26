<?php
session_start();
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

$message = "";

if (isset($_POST['register'])) {
    $user = $_POST['reg_user'];
    $pass = $_POST['reg_pass'];
    $email = $_POST['reg_email'];
    $gender = $_POST['reg_gender'];

    // Store everything in a nested array
    $_SESSION['users'][$user] = [
        'password' => $pass,
        'email' => $email,
        'gender' => $gender
    ];

    $message = "Registration successful! <a href='login.php'>Login here</a>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            background: #f4f4f4;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            width: 300px;
        }

        input,
        select {
            display: block;
            margin: 10px 0;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
        }

        .msg {
            color: green;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Create Account</h2>
        <p class="msg"><?php echo $message; ?></p>
        <form method="POST">
            <input type="text" name="reg_user" placeholder="Username" required>
            <input type="email" name="reg_email" placeholder="Email" required>
            <select name="reg_gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="password" name="reg_pass" placeholder="Password" required>
            <button name="register">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>

</html>