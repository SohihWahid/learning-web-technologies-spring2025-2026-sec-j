<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$success = "";
$error = "";

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['profile_pic'])){
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    $file = $_FILES['profile_pic'];
    if($file['error']===UPLOAD_ERR_NO_FILE){
        $error = "Please select a picture!";
    }else if(!in_array($file['type'], $allowed)){
        $error = "Only JPG, PNG, GIF allowed";
    }else if($file['size'] > 2 * 1024 * 1024){
        $error = "File too large! Max 2MB.";
    }else{
        if(!is_dir("uploads/")){
            mkdir("uploads/");
        }
        $savePath = "uploads/" . $username . ".jpg";
        if(move_uploaded_file($file['tmp_name'], $savePath)){
            $success = "Profile picture updated!";
        } else {
            $error = "Upload failed!";
        }
    }
}

$name = $email = $gender = $date_of_birth = "";
if(file_exists("users.txt")){
    $lines = file("users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach($lines as $line){
        $parts = explode("|", $line);
        if($parts[0] === $username){
            $name = $parts[2];
            $email = $parts[3];
            $gender = $parts[4];
            $date_of_birth = $parts[5];
            break;
        }
    }
}

$picPath = "uploads/" . $username . ".jpg";
if(!file_exists($picPath)){
    $picPath = "naruto.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        input[type="file"] {
            display: none;
        }
        .change-label {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
            font-size: 14px;
        }
        .change-label:hover {
            color: darkblue;
        }
    </style>
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
                    <div>Logedin as <?= htmlspecialchars($username) ?> | 
                    <a href="logout.php">Logout</a></div>
                </div>
            </div>
        </td></tr>

        <!-- Middle Row -->
        <tr>
            <!-- Left: Account Menu -->
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

            <!-- Right: Profile -->
            <td valign="top">
                <fieldset>
                    <legend>Profile</legend>

                    <?php if($error): ?>
                        <p style="color:red;"><?= $error ?></p>
                    <?php endif; ?>

                    <table class="tb">
                        <tr class="tb">
                            <!-- Profile Info -->
                            <td class="tb" valign="top">
                                <div>Name : <?= htmlspecialchars($name) ?></div><hr>
                                <div>Email : <?= htmlspecialchars($email) ?></div><hr>
                                <div>Gender : <?= htmlspecialchars($gender) ?></div><hr>
                                <div>Date of Birth : <?= htmlspecialchars($date_of_birth) ?></div><hr>
                                <a href="editProfile.php">Edit Profile</a>
                            </td>

                            <!-- Profile Picture -->
                            <td valign="top" align="center" class="tb">
                                <img src="<?= $picPath ?>" width="200" height="200" alt="Profile Picture" class="img">
                                <br><br>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <label for="profile_pic" class="change-label">Change</label>
                                    <input type="file" id="profile_pic" name="profile_pic"
                                           accept="image/*" onchange="this.form.submit()">
                                </form>
                            </td>
                        </tr>
                    </table>

                </fieldset>
            </td>
        </tr>

        <!-- Footer -->
        <tr><td colspan="2">
            <div class="footer">Copyright &copy; 2017</div>
        </td></tr>

    </table>
</body>
</html>