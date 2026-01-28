<?php
// Include the database connection file
include_once("database/db.php");

// Fetch books data from the database
$queryBooks = "SELECT b.BookID, b.Title, b.Image, b.Quantity, 
                      CONCAT(a.FirstName, ' ', a.LastName) AS AuthorName 
               FROM books b 
               JOIN authors a ON b.AuthorID = a.AuthorID
               ORDER BY b.Title ASC 
               LIMIT 4"; // Limit to 4 featured books for display
$resultBooks = mysqli_query($conn, $queryBooks);

// Fetch genres from the database
$queryGenres = "SELECT * FROM genres ORDER BY GenreName ASC";
$resultGenres = mysqli_query($conn, $queryGenres);

$Permission = isset($_SESSION['Permission']) ? $_SESSION['Permission'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>All Books</title>
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

	<?php include 'header.php'; ?>

	<section id="popular-books" class="bookshelf">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<div class="section-header align-center">
						<h2 class="section-title">All Books</h2>
					</div>

					<ul class="tabs">
						<li data-tab-target="#all-genre" class="active tab">All Genre</li>
						<?php while ($genre = mysqli_fetch_assoc($resultGenres)): ?>
							<li data-tab-target="#genre-<?php echo $genre['GenreID']; ?>" class="tab">
								<?php echo htmlspecialchars($genre['GenreName']); ?>
							</li>
						<?php endwhile; ?>
					</ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- All Books Section -->
            <div id="all-genre" data-tab-content class="active">
                <div class="row">
                    <?php
                    // Fetch all books
                    $queryAllBooks = "
                        SELECT b.BookID, b.Title, b.Image, 
                               CONCAT(a.FirstName, ' ', a.LastName) AS AuthorName 
                        FROM books b 
                        JOIN authors a ON b.AuthorID = a.AuthorID";
                    $resultAllBooks = mysqli_query($conn, $queryAllBooks);

                    // Check for database errors
                    if (!$resultAllBooks) {
                        die("Database query failed: " . mysqli_error($conn));
                    }

                    while ($book = mysqli_fetch_assoc($resultAllBooks)): ?>

					<div class="col-md-3">
						<div class="product-item">
							<figure class="product-style">
								<img src="<?php echo htmlspecialchars($book['Image']); ?>" 
									alt="<?php echo htmlspecialchars($book['Title']); ?>" class="product-item">

									<?php if ($Permission == '1') { ?>
										<!-- If the user is an admin, the button redirects to the edit page -->
										<button type="button" class="add-to-cart" data-product-tile="add-to-cart" onclick="window.location.href='edit_book.php?BookID=<?=$book['BookID']?>'">Edit</button>
									<?php } elseif($Permission == '3') { ?>
										<button type="button" class="add-to-cart" data-product-tile="add-to-cart" onclick="window.location.href='edit_book.php?BookID=<?=$book['BookID']?>'">Edit</button>
									<?php } elseif($Permission == '2') { ?>
										<!-- If the user is not an admin, the button redirects to the borrow page -->
                                        <button type="button" class="add-to-cart" data-product-tile="add-to-cart" onclick="window.location.href='book_details.php?BookID=<?=$book['BookID']?>'">View</button>									
                                    <?php } else { ?>
                                    <!-- If the user is not logged in or has no permission, show an alert and redirect to login -->
										<button type="button" class="add-to-cart" data-product-tile="add-to-cart" onclick="alert('Please login first'); window.location.href='login.php';">Login to Continue</button>                                    
                                    <?php }  ?>

							</figure>
							<figcaption>
								<h3><?php echo htmlspecialchars($book['Title']); ?></h3>
								<span><?php echo htmlspecialchars($book['AuthorName']); ?></span>
							</figcaption>
						</div>
					</div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Individual Genre Sections -->
            <?php
            // Reset pointer for genres
            mysqli_data_seek($resultGenres, 0);

            while ($genre = mysqli_fetch_assoc($resultGenres)): ?>
                <div id="genre-<?php echo $genre['GenreID']; ?>" data-tab-content>
                    <div class="row">
                        <?php
                        // Fetch books for this genre
                        $queryBooksByGenre = "
                            SELECT b.BookID, b.Title, b.Image, 
                                   CONCAT(a.FirstName, ' ', a.LastName) AS AuthorName 
                            FROM books b 
                            JOIN authors a ON b.AuthorID = a.AuthorID
                            WHERE b.GenreID = " . intval($genre['GenreID']);
                        $resultBooksByGenre = mysqli_query($conn, $queryBooksByGenre);

                        // Check for database errors
                        if (!$resultBooksByGenre) {
                            die("Database query failed: " . mysqli_error($conn));
                        }

                        while ($book = mysqli_fetch_assoc($resultBooksByGenre)): ?>
                            <div class="col-md-3">
                                <div class="product-item">
                                    <figure class="product-style">
                                        <img src="<?php echo htmlspecialchars($book['Image']); ?>" 
                                             alt="<?php echo htmlspecialchars($book['Title']); ?>" class="product-item">
                                        <button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to Cart</button>
                                    </figure>
                                    <figcaption>
                                        <h3><?php echo htmlspecialchars($book['Title']); ?></h3>
                                        <span><?php echo htmlspecialchars($book['AuthorName']); ?></span>
                                    </figcaption>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

				</div><!--inner-tabs-->

			</div>
		</div>
	</section>

	<?php include 'footer.php'; ?>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

	<script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('[data-tab-content]').forEach(c => c.classList.remove('active'));

                tab.classList.add('active');
                document.querySelector(tab.getAttribute('data-tab-target')).classList.add('active');
            });
        });
    </script>
	
</body>

</html>