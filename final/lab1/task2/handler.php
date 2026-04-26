<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = $_POST['email'];
    echo"<h1>The submitted email is: " . htmlspecialchars($email) . "</h1>";
} else{
    echo "<h1>No email submitted yet</h1>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="email.php" method="post">
        <fieldset>
            <legend><h1>Handler Page</h1></legend>
            <button type="back" id="back" name="back">Back</button>
        </fieldset>
    </form>
</body>
</html>
