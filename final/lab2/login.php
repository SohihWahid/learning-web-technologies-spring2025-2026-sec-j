<?php
$error ="";
if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['name']??'');
    $password = $_POST['password']??'';
    $found = false;
    if(file_exists("users.txt")){
        $lines = file("users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line){
            list($storedUser, $storedPass) =explode("|",$line);
            if($username === $storedUser && $password === $storedPass){
                $found = true;
                break;
            }
        }
    }
    if($found){
        session_start();
        $_SESSION['username'] = $username;
        if(isset($_POST['checkbox'])){
            setcookie("remember_user", $username, time()+(30*24*60*60));
        }
        header("Location: dashBoard.php");
        exit();
    }else{
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <table class="custom-table">
        <tr><td><div class="header-row">
                            <nav class="navbar">
                                <div class="logo">
                                    <span class="logo-icon">X</span>
                                    <span class="logo-text">Company</span>
                                </div>
                            </nav>
                            <div class="header-links">
                                <a href="publicHome.php">Home</a>
                                <a href="Login">Login</a>
                                <a href="registration.php">Registration</a>
                            </div>
                     </div></td></tr>
        <tr><td>
            <form action="" method="post" class="frm">
                <fieldset class="fld-set">
                    <legend><h1>Login</h1></legend>
                    <div class="frm-grp">
                        <label for="name">User Name :</label>
                        <input type="text" name="name"><br><br>
                    </div>
                    <div class="frm-grp">
                        <label for="password:">Password :</label>
                        <input type="password" name="password">
                    </div>
                    <hr>
                    <div>
                        <input type="checkbox" name="checkbox">
                        <label for="checkbox">Remember Me</label>
                    </div><br>
                    <input type="submit" name="submit">
                    <a href="forgetPassword.php">Forget Password?</a>
                </fieldset>
            </form>
        </td></tr>
        <tr><td class="footer"><h4>copyright © 2017</h4></td></tr>
    </table>
</body>
</html>