<?php
session_start();
// Only remove the logged-in user's data
unset($_SESSION['user_data']);
// Redirect to login
header("Location: login.php");
exit();