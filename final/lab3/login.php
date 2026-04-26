<?php
session_start();
$message = "";

if (isset($_POST['login'])) {
    $user = $_POST['login_user'];
    $pass = $_POST['login_pass'];

    // 1. Admin Logic (Static check)
    if ($user === "sohih" && $pass === "1234") {
        $_SESSION['user_data'] = ['name' => $user, 'role' => 'admin'];
        header("Location: admindashboard.php");
        exit();
    }
    // 2. Normal User Logic (Check Session Array)
    elseif (isset($_SESSION['users'][$user]) && $_SESSION['users'][$user]['password'] === $pass) {
        $_SESSION['user_data'] = [
            'name' => $user,
            'role' => 'user',
            'email' => $_SESSION['users'][$user]['email']
        ];
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
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

        input {
            display: block;
            margin: 10px 0;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <p class="error"><?php echo $message; ?></p>
        <form method="POST">
            <input type="text" name="login_user" placeholder="Username" required>
            <input type="password" name="login_pass" placeholder="Password" required>
            <button name="login">Login</button>
        </form>
        <p>New here? <a href="register.php">Register</a></p>
    </div>
</body>

</html>