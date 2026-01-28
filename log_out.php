<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Destroy the session
    session_unset();
    session_destroy();

    // Redirect to login page or homepage
    echo "<script>alert('You have successfully logged out'); window.location.href='login.php';</script>";
    exit();
}

?>
