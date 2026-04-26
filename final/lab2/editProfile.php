<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$success = "";
$error = "";

$name = $email = $gender = $date_of_birth = $password = "";
$allLines = [];

if(file_exists("users.txt")){
    $lines = file("users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach($lines as $line){
        $parts = explode("|", $line);
        if($parts[0] === $username){
            $password      = $parts[1];
            $name          = $parts[2];
            $email         = $parts[3];
            $gender        = $parts[4];
            $date_of_birth = $parts[5];
        }
        $allLines[] = $line;
    }
}


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $newName  = trim($_POST['name'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    $newGender = $_POST['gender'] ?? '';
    $newDob   = trim($_POST['date_of_birth'] ?? '');

    if(empty($newName))  $error = "Name is required!";
    else if(empty($newEmail) || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) $error = "Valid email is required!";
    else if(empty($newGender)) $error = "Please select gender!";
    else if(empty($newDob))  $error = "Date of birth is required!";
    else {
        $updatedLines = [];
        foreach($allLines as $line){
            $parts = explode("|", $line);
            if($parts[0] === $username){
                $updatedLines[] = $username."|".$password."|".$newName."|".$newEmail."|".$newGender."|".$newDob;
            } else {
                $updatedLines[] = $line;
            }
        }
        file_put_contents("users.txt", implode("\n", $updatedLines) . "\n");

        $name          = $newName;
        $email         = $newEmail;
        $gender        = $newGender;
        $date_of_birth = $newDob;
        $success       = "Profile updated!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <table class="custom-table">

        <!-- Header -->
        <tr><td colspan="2">
            <div class="header-row">
                <nav class="navbar">
                    <div class="logo">
                        <span class="logo-icon">X</span>
                        <span class="logo-text">Company</span>
                    </div>
                </nav>
                <div class="logedin">
                    Logged in as <?= htmlspecialchars($username) ?> |
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </td></tr>

        <tr>
            <td valign="top">
                <h2 class="acc">Account</h2><hr>
                <ul>
                    <li><a href="dashBoard.php">Dashboard</a></li>
                    <li><a href="viewProfile.php">View Profile</a></li>
                    <li><a href="editProfile.php">Edit Profile</a></li>
                    <li><a href="">Change Profile Picture</a></li>
                    <li><a href="">Change Password</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </td>
            <td valign="top">
                <fieldset>
                    <legend>EDIT PROFILE</legend>

                    <?php if($success): ?>
                        <p style="color:green;"><?= $success ?></p>
                    <?php endif; ?>
                    <?php if($error): ?>
                        <p style="color:red;"><?= $error ?></p>
                    <?php endif; ?>
                    <form action="" method="post">
                        <table class="tb">
                            <tr class="tb">
                                <td class="tb">Name :</td>
                                <td class="tb">
                                    <input type="text" name="name" 
                                    value="<?= htmlspecialchars($name) ?>">
                                </td>
                            </tr>
                            <tr class="tb"><td colspan="2" class="tb"><hr></td></tr>

                            <tr class="tb">
                                <td class="tb">Email :</td>
                                <td class="tb">
                                    <input type="email" name="email" 
                                    value="<?= htmlspecialchars($email) ?>">
                                </td>
                            </tr>
                            <tr class="tb"><td colspan="2" class="tb"><hr></td></tr>

                            <tr class="tb">
                                <td class="tb">Gender :</td>
                                <td class="tb">
                                    <input type="radio" name="gender" value="Male"
                                    <?= $gender==='Male' ? 'checked' : '' ?>> Male
                                    <input type="radio" name="gender" value="Female"
                                    <?= $gender==='Female' ? 'checked' : '' ?>> Female
                                    <input type="radio" name="gender" value="Other"
                                    <?= $gender==='Other' ? 'checked' : '' ?>> Other
                                </td>
                            </tr>
                            <tr class="tb"><td colspan="2" class="tb"><hr></td></tr>

                            <tr class="tb">
                                <td class="tb">Date of Birth :</td>
                                <td class="tb">
                                    <input type="text" name="date_of_birth" 
                                    placeholder="dd/mm/yyyy"
                                    value="<?= htmlspecialchars($date_of_birth) ?>">
                                </td>
                            </tr>
                            <tr class="tb"><td colspan="2" class="tb"><hr></td></tr>

                            <tr class="tb">
                                <td colspan="2" class="tb">
                                    <input type="submit" value="Submit">
                                </td>
                            </tr>
                        </table>
                    </form>

                </fieldset>
            </td>
        </tr>
        <tr><td colspan="2">
            <div class="footer">Copyright &copy; 2017</div>
        </td></tr>

    </table>
</body>
</html>