<?php
// Include the database connection file
include_once("database/db.php");

// Get the PublisherID from the URL
if (isset($_GET['PublisherID'])) {
    $PublisherID = $_GET['PublisherID'];

    // Fetch the publisher's details from the database using the PublisherID
    $queryPublisherDetails = "SELECT * FROM publishers WHERE PublisherID = ?";
    $stmt = $conn->prepare($queryPublisherDetails);
    $stmt->bind_param("i", $PublisherID);
    $stmt->execute();
    $resultPublisher = $stmt->get_result();

    // Check if the publisher exists
    if ($resultPublisher->num_rows > 0) {
        $publisher = $resultPublisher->fetch_assoc();
    } else {
        echo "<script>alert('Publisher not found'); window.location.href='all_publishers.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid PublisherID'); window.location.href='all_publishers.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Publisher Details</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
    <link rel="stylesheet" type="text/css" href="css/vendor.css">
    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

    <?php include "header.php"; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="text-center"><?php echo htmlspecialchars($publisher['PublisherName']); ?></h2>
                
                <div class="publisher-details">
                    <div class="row">
                        <!-- Display publisher image -->
                        <div class="col-md-4">
                            <img src="<?php echo !empty($publisher['Image']) ? htmlspecialchars($publisher['Image']) : 'db_image/default.jpg'; ?>" alt="Publisher Image" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                            <p><strong>Publisher Name:</strong> <?php echo htmlspecialchars($publisher['PublisherName']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($publisher['Address']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($publisher['Phone']); ?></p>
                        </div>
                    </div>

                    <!-- Optionally, you can add a "Go back" button -->
                    <div class="text-center mt-4">
                        <a href="all_publishers.php" class="btn btn-secondary">Go back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>

    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
            crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>

</body>

</html>
