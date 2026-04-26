<?php
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob_day = trim($_POST['dob_day'] ?? '');
    $dob_month = trim($_POST['dob_month'] ?? '');
    $dob_year = trim($_POST['dob_year'] ?? '');

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";
    if (empty($username)) $errors[] = "User Name is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";
    if (empty($gender)) $errors[] = "Please select a gender.";
    if (empty($dob_day) || empty($dob_month) || empty($dob_year)) $errors[] = "Date of Birth is required.";

    if (file_exists("users.txt")) {
        $lines = file("users.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($storedUser) = explode("|", $line);
            if ($username === $storedUser) {
                $errors[] = "Username already taken!";
                break;
            }
        }
    }

    if (empty($errors)) {
        $dob = $dob_day . "/" . $dob_month . "/" . $dob_year;
        $line = $username . "|" . $password . "|" . $name . "|" . $email . "|" . $gender . "|" . $dob . "\n";
        file_put_contents("users.txt", $line, FILE_APPEND);
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <table class="custom-table">

        <!-- Header -->
        <tr><td>
            <div class="header-row">
                <nav class="navbar">
                    <div class="logo">
                        <span class="logo-icon">X</span>
                        <span class="logo-text">Company</span>
                    </div>
                </nav>
                <div class="header-links">
                    <a href="index.php">Home</a>
                    <a href="login.php">Login</a>
                    <a href="register.php">Registration</a>
                </div>
            </div>
        </td></tr>

        <!-- Middle Row -->
        <tr><td>
            <div class="frm">
                <fieldset class="fld-set">
                    <legend>REGISTRATION</legend>

                    <?php if ($success): ?>
                        <p style="color:green;">Registration successful! <a href="login.php">Login here</a></p>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <?php foreach ($errors as $err): ?>
                            <p style="color:red;"><?= htmlspecialchars($err) ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <div class="frm-grp">
                            <label for="name">Name :</label>
                            <input type="text" id="name" name="name"
                            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        </div><hr>

                        <div class="frm-grp">
                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div><hr>

                        <div class="frm-grp">
                            <label for="username">User Name :</label>
                            <input type="text" id="username" name="username"
                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        </div><hr>

                        <div class="frm-grp">
                            <label for="password">Password :</label>
                            <input type="password" id="password" name="password">
                        </div><hr>

                        <div class="frm-grp">
                            <label for="confirm_password">Confirm Password :</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div><hr>

                        <!-- Gender inside fieldset -->
                        <fieldset>
                            <legend>Gender</legend>
                            <div class="frm-grp">
                                <input type="radio" name="gender" value="Male"
                                <?= (($_POST['gender'] ?? '') === 'Male') ? 'checked' : '' ?>> Male
                                <input type="radio" name="gender" value="Female"
                                <?= (($_POST['gender'] ?? '') === 'Female') ? 'checked' : '' ?>> Female
                                <input type="radio" name="gender" value="Other"
                                <?= (($_POST['gender'] ?? '') === 'Other') ? 'checked' : '' ?>> Other
                            </div>
                        </fieldset>

                        <!-- Date of Birth inside fieldset -->
                        <fieldset>
                            <legend>Date of Birth</legend>
                            <div class="frm-grp">
                                <input type="text" name="dob_day" maxlength="2" placeholder="dd" style="width:30px"
                                value="<?= htmlspecialchars($_POST['dob_day'] ?? '') ?>">
                                /
                                <input type="text" name="dob_month" maxlength="2" placeholder="mm" style="width:30px"
                                value="<?= htmlspecialchars($_POST['dob_month'] ?? '') ?>">
                                /
                                <input type="text" name="dob_year" maxlength="4" placeholder="yyyy" style="width:46px"
                                value="<?= htmlspecialchars($_POST['dob_year'] ?? '') ?>">
                                <small>(dd/mm/yyyy)</small>
                            </div>
                        </fieldset>

                        <br>
                        <div>
                            <input type="submit" value="Submit">
                            <input type="reset" value="Reset" class="btn">
                        </div>

                    </form>
                </fieldset>
            </div>
        </td></tr>

        <!-- Footer -->
        <tr><td>
            <div class="footer">Copyright &copy; 2017</div>
        </td></tr>

    </table>
</body>
</html>
