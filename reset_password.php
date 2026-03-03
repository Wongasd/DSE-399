<?php
session_start();
include_once("database/db.php");

if(isset($_GET['token'])){
    $token = $_GET['token'];

    // Secure prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows != 1){
        echo "<script>alert('Invalid or expired token');window.location.href='login.php';</script>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $token = $_POST['token'];
    $passwordInput = $_POST['Password'];

    // Optional: validate password strength
    if(strlen($passwordInput) < 8){
        echo "<script>alert('Password must be at least 8 characters');</script>";
        exit;
    }

    $newPassword = password_hash($passwordInput, PASSWORD_DEFAULT);

    // Update password securely
    $update = $conn->prepare("UPDATE users SET Password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?");
    $update->bind_param("ss", $newPassword, $token);
    $update->execute();

    echo "<script>alert('Password Reset Successful');window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <h2>Reset Password</h2>

    <form method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="Password" class="form-control" required>
        </div>
        <br>
        <button type="submit" class="btn btn-success">Reset Password</button>
    </form>

</body>
</html>