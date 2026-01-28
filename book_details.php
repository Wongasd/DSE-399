<?php
// Include the database connection file
include_once("database/db.php");

// Get the BookID from the URL
if (isset($_GET['BookID'])) {
    $BookID = $_GET['BookID'];

    // Fetch the book details from the database using the BookID
    $queryBookDetails = "SELECT books.*, authors.FirstName, authors.LastName, genres.GenreName, publishers.PublisherName 
                         FROM books
                         LEFT JOIN authors ON books.AuthorID = authors.AuthorID
                         LEFT JOIN genres ON books.GenreID = genres.GenreID
                         LEFT JOIN publishers ON books.PublisherID = publishers.PublisherID
                         WHERE books.BookID = ?";
    $stmt = $conn->prepare($queryBookDetails);
    $stmt->bind_param("i", $BookID);
    $stmt->execute();
    $resultBook = $stmt->get_result();

    // Check if the book exists
    if ($resultBook->num_rows > 0) {
        $book = $resultBook->fetch_assoc();
    } else {
        echo "<script>alert('Book not found'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid BookID'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Book Details</title>
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

    <style>
        /* Disable the link visually and functionally */
        .disabled-link {
            pointer-events: none; /* Prevent clicks */
            opacity: 0.6;         /* Make it look disabled */
            cursor: not-allowed;  /* Show "not-allowed" cursor */
            text-decoration: none; /* Remove underline */
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

	<?php include "header.php"; ?>

	<div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="text-center"><?php echo htmlspecialchars($book['Title']); ?></h2>
                
                <div class="book-details">
                    <div class="row">
                        <!-- Display book image -->
                        <div class="col-md-4">
                            <img src="<?php echo !empty($book['Image']) ? htmlspecialchars($book['Image']) : 'db_image/default.jpg'; ?>" alt="Book Image" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($book['Description']); ?></p>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['FirstName']) . ' ' . htmlspecialchars($book['LastName']); ?></p>
                            <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['GenreName']); ?></p>
                            <p><strong>Publisher:</strong> <?php echo htmlspecialchars($book['PublisherName']); ?></p>
                            <p><strong>Published Year:</strong> <?php echo htmlspecialchars($book['PublishedYear']); ?></p>
                            <p><strong>Copies Available:</strong> 
                                <?php 
                                    if ($book['Quantity'] == 0 || $book['Status'] == "Unavailable") {
                                        echo "Out of Stock";
                                    } else {
                                        echo htmlspecialchars($book['Quantity']);
                                    }
                                ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Optionally, you can add a "Go back" button -->
                     <div class="text-center mt-4">
                        <a href="#" class="btn btn-secondary me-2"
                            onclick="if (document.referrer) { history.back(); } else { window.location.href='index.php'; }">
                            Go back
                        </a>

                        <?php if ($book['Quantity'] > 0 && $book['Status'] != "Unavailable"): ?>
                            <?php if (isset($_SESSION['UserID'])): ?>
                                <!-- User is logged in → Show Borrow Button -->
                                <a href="borrow.php?BookID=<?php echo $BookID; ?>" class="btn btn-success">
                                    Borrow This Book
                                </a>
                            <?php else: ?>
                                <!-- User NOT logged in → Show Login Prompt -->
                                <a href="login.php" class="btn btn-outline-success">
                                    Login to Borrow
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Book is out of stock → Disable Borrow Button -->
                                <a href="#" class="btn btn-secondary disabled-link" tabindex="-1" aria-disabled="true">
                                    Out of Stock
                                </a>
                        <?php endif; ?>

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