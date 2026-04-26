<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
} 
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <table class="custom-table">
        <tr><td colspan="2">
            <div class="header-row">
                <nav class="navbar">
                    <div class="logo">
                        <span class="logo-icon">X</span>
                        <span class="logo-text">Company</span>
                    </div>
                </nav>
                <div class="logedin">
                        <div>Logedin as <?= htmlspecialchars($username)?> | 
                        <a href="logout.php">Logout</a></div>
                </div>
            </div>
        </td></tr>
        <tr>
            <td>
                <h2 class="acc">Account</h2><hr>
                <ul>
                    <li><a href="dashBoard.php">Dashboard</a></li>
                    <li><a href="viewProfile.php">View Profile</a></li>
                    <li><a href="editProfile.php">Edit Profile</a></li>
                    <li><a href="">Change Profile Picture</a></li>
                    <li><a href="">Change password</a></li>
                    <li><a href="">Logout</a></li>
                </ul>
            </td>
            <th>
                Welcome <?= htmlspecialchars($username) ?>
            </th>
        </tr>
        <tr><td colspan="2"><div class="footer">Copyright &copy; 2017</div></td></tr>
    </table>
</body>
</html>