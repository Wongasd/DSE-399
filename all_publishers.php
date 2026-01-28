<?php
// Include the database connection file
include_once("database/db.php");

// Fetch publishers from the database (including image)
$queryPublishers = "SELECT PublisherID, PublisherName, Address, Phone, Image 
                    FROM publishers 
                    ORDER BY PublisherName ASC";
$resultPublishers = mysqli_query($conn, $queryPublishers);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>All Publishers</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
    <link rel="stylesheet" type="text/css" href="css/vendor.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include 'header.php'; ?> <!-- Include the header -->

    <div class="container mt-5">
        <h2 class="section-title">All Publishers</h2>
        <div class="row">
            <?php while ($publisher = mysqli_fetch_assoc($resultPublishers)): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- Check if the publisher has an image -->
                            <img src="<?php echo !empty($publisher['Image']) ? '' . htmlspecialchars($publisher['Image']) : 'db_image/default.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($publisher['PublisherName']); ?>" 
                                 class="card-img-top" style="max-height: 200px; object-fit: cover;">

                            <h5 class="card-title"><?php echo htmlspecialchars($publisher['PublisherName']); ?></h5>
                            <p class="card-text">Address: <?php echo htmlspecialchars($publisher['Address']); ?></p>
                            <p class="card-text">Phone: <?php echo htmlspecialchars($publisher['Phone']); ?></p>

                            <?php if ($Permission == '1') { ?>
										<!-- If the user is an admin, the button redirects to the edit page -->
										<button type="button" class="btn btn-primary" data-product-tile="add-to-cart" onclick="window.location.href='edit_publisher.php?PublisherID=<?=$publisher['PublisherID']?>'">Edit</button>
                                        <button type="button" class="btn btn-danger" data-product-tile="add-to-cart" 
                                        onclick="confirmDeletion('<?=$publisher['PublisherID']?>')">Delete</button>
									<?php } elseif ($Permission == '2') { ?>
										<!-- If the user is not an admin, the button redirects to the borrow page -->
										<button type="button" class="btn btn-primary" data-product-tile="add-to-cart" onclick="window.location.href='publisher_details.php?PublisherID=<?=$publisher['PublisherID']?>'">View</button>
									<?php } elseif($Permission == '3') { ?>
										<button type="button" class="btn btn-primary" data-product-tile="add-to-cart" onclick="window.location.href='edit_publisher.php?PublisherID=<?=$publisher['PublisherID']?>'">Edit</button>
									<?php } else { ?>
                                        <!-- If the user is not logged in or has no permission, show an alert and redirect to login -->
                                        <button type="button" class="btn btn-primary" data-product-tile="add-to-cart" onclick="alert('Please login first'); window.location.href='login.php';">Login to Continue</button>
                                    <?php } ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Include the footer -->
    
    <script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
    
    <script>
        function confirmDeletion(PublisherID) {
    if (confirm('Are you sure you want to delete this Publisher?')) {
        window.location.href = 'delete.php?ACTION=Delete&PublisherID=' + PublisherID;
    }
}
    </script>
</body>
</html>