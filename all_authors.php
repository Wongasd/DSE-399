<?php
// Include the database connection file
include_once("database/db.php");

// Fetch authors data from the database
$queryAuthors = "SELECT *, CONCAT(FirstName, ' ', LastName) AS FullName
                 FROM authors 
                 ORDER BY FirstName ASC";
$resultAuthors = mysqli_query($conn, $queryAuthors);

// Check for database errors
if (!$resultAuthors) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>All Authors</title>
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

<body>

    <?php include 'header.php'; ?> <!-- Include the header -->

    <div class="container mt-5">
        <div class="section-header align-center">
            <h2 class="section-title">All Authors</h2>
        </div>
        <div class="row">

        <?php while ($author = mysqli_fetch_assoc($resultAuthors)): ?>
            <div class="col-md-3 mb-4">
                <div class="author-item text-center">
                    <figure class="author-profile">
                        <img src="<?php echo htmlspecialchars(!empty($author['Image']) ? $author['Image'] : 'db_image/default.jpg'); ?>" 
                            alt="<?php echo htmlspecialchars($author['FullName']); ?>" 
                            class="img-fluid rounded-circle mb-3" 
                            style="width: 150px; height: 150px; object-fit: cover;">
                    </figure>
                    <h5 class="author-name"><?php echo htmlspecialchars($author['FullName']); ?></h5>
                    <p class="author-description text-muted">
                        <?php echo htmlspecialchars(!empty($author['Description']) ? $author['Description'] : 'default'); ?>
                    </p>

                                    <?php if ($Permission == '1') { ?>
										<!-- If the user is an admin, the button redirects to the edit page -->
										<button type="button" class="btn btn-primary" data-product-tile="add-to-cart" onclick="window.location.href='edit_author.php?AuthorID=<?=$author['AuthorID']?>'">Edit</button>
                                        <button type="button" class="btn btn-danger" data-product-tile="add-to-cart" 
                                        onclick="confirmDeletion('<?=$author['AuthorID']?>')">Delete</button>
									<?php } elseif ($Permission == '3') { ?>
                                        <button type="button" class="btn btn-primary" data-product-tile="add-to-cart" onclick="window.location.href='edit_author.php?AuthorID=<?=$author['AuthorID']?>'">Edit</button>
									<?php } elseif($Permission == '2') { ?>
										<!-- If the user is not an admin, the button redirects to the borrow page -->
										<button type="button" class="btn btn-primary" data-product-tile="add-to-cart" onclick="window.location.href='author_details.php?AuthorID=<?=$author['AuthorID']?>'">View</button>																				
									<?php } else { ?>
                                    <!-- If the user is not logged in or has no permission, show an alert and redirect to login -->
										<button type="button" class="btn btn-primary" data-product-tile="add-to-cart" onclick="alert('Please login first'); window.location.href='login.php';">Login to Continue</button>
                                    <?php } ?>
                </div>

            </div>
        <?php endwhile; ?>

        </div>
    </div>

    <?php include 'footer.php'; ?>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

    <script>
function confirmDeletion(authorID) {
    if (confirm('Are you sure you want to delete this author?')) {
        window.location.href = 'delete.php?ACTION=Delete&AuthorID=' + authorID;
    }
}
</script>
    
</body>

</html>
