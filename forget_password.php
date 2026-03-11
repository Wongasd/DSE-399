<?php
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Forgot Password</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <style>
        label {
            color: white;
        }
    </style>
</head>

<body>

<div class="top-content">
<div class="inner-bg">
<div class="container">

    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
            <h1>Forgot Your Password?</h1>
            <p>Enter your email to receive a password reset link.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">

            <div class="form-box">

                <div class="form-bottom">

                    <form role="form" method="POST" class="registration-form">

                        <div class="form-group">
                            <label for="Email">Email Address</label>
                            <input type="email" name="Email"
                                placeholder="Enter Your Email..."
                                class="form-email form-control"
                                id="Email" required>
                        </div>

                        <div class="form-group">
                            <div class="row">

                                <div class="col-xs-6">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Send Reset Link
                                    </button>
                                </div>

                                <div class="col-xs-6">
                                    <button type="button"
                                        class="btn btn-secondary btn-block"
                                        onclick="window.location.href='login.php'">
                                        Back to Login
                                    </button>
                                </div>

                            </div>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>
</div>
</div>

<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.backstretch.min.js"></script>
<script src="assets/js/scripts.js"></script>

</body>
</html>