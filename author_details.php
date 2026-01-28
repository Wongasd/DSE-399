<?php
// Include the database connection file
include_once("database/db.php");

// Get the AuthorID from the URL
if (isset($_GET['AuthorID'])) {
    $AuthorID = $_GET['AuthorID'];

    // Fetch the author's details from the database using the AuthorID
    $queryAuthorDetails = "SELECT *, CONCAT('First Name', ' ' , 'Last Name') AS FullName FROM authors WHERE AuthorID = ?";
    $stmt = $conn->prepare($queryAuthorDetails);
    $stmt->bind_param("i", $AuthorID);
    $stmt->execute();
    $resultAuthor = $stmt->get_result();

    // Check if the author exists
    if ($resultAuthor->num_rows > 0) {
        $author = $resultAuthor->fetch_assoc();
    } else {
        echo "<script>alert('Author not found'); window.location.href='all_authors.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid AuthorID'); window.location.href='all_authors.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Author Details</title>
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
                <h2 class="text-center"><?php echo htmlspecialchars($author['FirstName']) . ' ' . htmlspecialchars($author['LastName']); ?></h2>
                
                <div class="author-details">
                    <div class="row">
                        <!-- Display author image -->
                        <div class="col-md-4">
                            <img src="<?php echo !empty($author['Image']) ? htmlspecialchars($author['Image']) : 'db_image/default.jpg'; ?>" alt="Author Image" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($author['FirstName']) . ' ' . htmlspecialchars($author['LastName']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($author['Description']); ?></p>
                            <p><strong>Books Written:</strong> <?php 
                                // Fetch the number of books written by the author
                                $queryBooksCount = "SELECT COUNT(*) AS BookCount FROM books WHERE AuthorID = ?";
                                $stmtBooksCount = $conn->prepare($queryBooksCount);
                                $stmtBooksCount->bind_param("i", $AuthorID);
                                $stmtBooksCount->execute();
                                $resultBooksCount = $stmtBooksCount->get_result();
                                $bookCount = $resultBooksCount->fetch_assoc();
                                echo $bookCount['BookCount'];
                            ?></p>
                        </div>
                    </div>
                    
                    <!-- Optionally, you can add a "Go back" button -->
                    <div class="text-center mt-4">
                        <a href="all_authors.php" class="btn btn-secondary">Go back</a>
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
