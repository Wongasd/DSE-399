<?php
include_once("database/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>About Us</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
    <link rel="stylesheet" type="text/css" href="css/vendor.css">
    <link rel="stylesheet" type="text/css" href="style.css">

    <style>
        .about-section {
            background: #f8f9fa;
            padding: 60px 0;
            border-radius: 10px;
        }
        .about-title {
            font-weight: 700;
            color: #2c3e50;
        }
        .about-text {
            font-size: 1.1rem;
            color: #555;
        }
        .info-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s ease-in-out;
        }
        .info-box:hover {
            transform: translateY(-5px);
        }
        .icon-large {
            font-size: 2.5rem;
            color: #007bff;
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#header">

<?php include 'header.php'; ?>

<section class="about-section container mt-5">
    <div class="text-center mb-4">
        <h1 class="about-title">About Our Library</h1>
        <p class="about-text">We strive to make reading and book management simple, smart, and enjoyable.</p>
    </div>

    <div class="row g-4">
        <!-- Mission Box -->
        <div class="col-md-6">
            <div class="info-box">
                <h3><span class="icon-books icon-large"></span> Our Mission</h3>
                <p class="about-text">
                    To empower libraries with a seamless system to manage resources efficiently while ensuring readers have
                    easy access to their favorite books — anytime, anywhere.
                </p>
            </div>
        </div>

        <!-- What We Offer Box -->
        <div class="col-md-6">
            <div class="info-box">
                <h3><span class="icon-cog icon-large"></span> What We Offer</h3>
                <ul class="about-text">
                    <li>📚 Smart book inventory management</li>
                    <li>👥 User & borrowing management system</li>
                    <li>💻 Easy-to-use interface and navigation</li>
                    <li>🔐 Secure storage for user accounts & data</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <h3 class="about-title">Contact Us</h3>
        <p class="about-text mb-1">📧 Email: support@librarysystem.com</p>
        <p class="about-text">📞 Phone: +123 456 7890</p>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/jquery-1.11.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/script.js"></script>

</body>
</html>
