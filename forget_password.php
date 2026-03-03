<?php
session_start();
include_once("database/db.php");

// Include PHPMailer & Dotenv
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php'; // Composer autoload

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['Email']);

    // Clear old expired tokens (optional)
    $conn->query("UPDATE users SET reset_token=NULL, token_expiry=NULL WHERE token_expiry < NOW()");

    // Use prepared statement for security
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Always show same message to prevent email enumeration
    $alertMessage = "If the email exists, a reset link has been sent.";

    if($result->num_rows == 1){

        $token = bin2hex(random_bytes(32)); // secure token
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Update token securely
        $update = $conn->prepare("UPDATE users SET reset_token=?, token_expiry=? WHERE Email=?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        $resetLink = "http://localhost/DSE399_Project/reset_password.php?token=$token";

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = getenv('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('MAIL_USERNAME');
            $mail->Password   = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = getenv('MAIL_PORT');

            $mail->setFrom(getenv('MAIL_USERNAME'), 'Library System');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <h3>Password Reset</h3>
                <p>Click the link below to reset your password:</p>
                <a href='$resetLink'>$resetLink</a>
                <p>This link will expire in 1 hour.</p>
            ";

            $mail->send();

        } catch (Exception $e) {
            // Log error for debugging (optional)
            error_log("Mailer Error: {$mail->ErrorInfo}");
        }
    }

    // Show alert regardless of email existence
    echo "<script>alert('$alertMessage');window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forget Password</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <h2>Forget Password</h2>

    <form method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="Email" class="form-control" required>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
        <a href="login.php" class="btn btn-secondary">Back</a>
    </form>

</body>
</html>