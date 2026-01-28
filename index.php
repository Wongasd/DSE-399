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

$queryBooks2 = "SELECT * FROM books ORDER BY BookID ASC LIMIT 3"; // Adjust table and column names as needed
$resultBooks2 = mysqli_query($conn, $queryBooks2);

$Permission = isset($_SESSION['Permission']) ? $_SESSION['Permission'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Index</title>
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

	</div><!--header-wrap-->

	<section id="billboard">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<button class="prev slick-arrow">
						<i class="icon icon-arrow-left"></i>
					</button>

					<div class="main-slider pattern-overlay">
						<?php while ($banner = mysqli_fetch_assoc($resultBooks2)): ?>
						<div class="slider-item">
							<div class="banner-content">
								<h2 class="banner-title"><?php echo htmlspecialchars($banner['Title']); ?></h2>
								<p><?php echo htmlspecialchars($banner['Description']); ?></p>
								<div class="btn-wrap">
									<a href="book_details.php?BookID=<?=$banner['BookID']?>" class="btn btn-outline-accent btn-accent-arrow">Read More<i class="icon icon-ns-arrow-right"></i></a>
								</div>
							</div><!--banner-content-->
							<!-- Check if the image exists and display it -->
							<img style="width:250px;height:350px;" src="<?php echo !empty($banner['Image']) ? '' . htmlspecialchars($banner['Image']) : 'db_image/default.jpg'; ?>" alt="banner" class="banner-image">
						</div><!--slider-item-->
						<?php endwhile; ?>
					</div><!--slider-->

					<button class="next slick-arrow">
						<i class="icon icon-arrow-right"></i>
					</button>

				</div>
			</div>
		</div>
	</section>

	<section id="client-holder" data-aos="fade-up">
		<div class="container">
			<div class="row">
				<div class="inner-content">
					<div class="logo-wrap">
						<div class="grid">
							<a href="#"><img src="images/client-image1.png" alt="client"></a>
							<a href="#"><img src="images/client-image2.png" alt="client"></a>
							<a href="#"><img src="images/client-image3.png" alt="client"></a>
							<a href="#"><img src="images/client-image4.png" alt="client"></a>
							<a href="#"><img src="images/client-image5.png" alt="client"></a>
						</div>
					</div><!--image-holder-->
				</div>
			</div>
		</div>
	</section>

	<section id="featured-books" class="py-5 my-5">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<div class="section-header align-center">
						<div class="title">
							<span>Some quality items</span>
						</div>
						<h2 class="section-title">Featured Books</h2>
					</div>

					<div class="product-list" data-aos="fade-up">
						<div class="row">
							<?php
							// Check if there are books to display
							if (mysqli_num_rows($resultBooks) > 0) {
								while ($book = mysqli_fetch_assoc($resultBooks)) {
									$bookImage = !empty($book['Image']) ? htmlspecialchars($book['Image']) : 'images/default-book.jpg';
									$bookTitle = htmlspecialchars($book['Title']);
									$authorName = htmlspecialchars($book['AuthorName']);
									 // Default price if not set
									?>
									<div class="col-md-3">
										<div class="product-item">
											<figure class="product-style">
												<img src="<?php echo $bookImage; ?>" alt="Book Cover" class="product-item">

												<?php if ($Permission == '1') { ?>
													<!-- If the user is an admin, the button redirects to the edit page -->
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart" onclick="window.location.href='edit_book.php?BookID=<?=$book['BookID']?>'">Edit</button>
												<?php } elseif ($Permission == '2') { ?>
													<!-- If the user is not an admin, the button redirects to the borrow page -->
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart" onclick="window.location.href='book_details.php?BookID=<?=$book['BookID']?>'">View</button>
												<?php } else { ?>
													<!-- If the user is not logged in or has no permission, show an alert and redirect to login -->
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart" onclick="alert('Please login first'); window.location.href='login.php';">Login to Continue</button>
												<?php } ?>

											</figure>
											<figcaption>
												<h3><?php echo $bookTitle; ?></h3>
												<span><?php echo $authorName; ?></span>
											
											</figcaption>
										</div>
									</div>
									<?php
								}
							} else {
								echo "<p>No featured books available.</p>";
							}
							?>
						</div><!--row-->
					</div><!--product-list-->

				</div><!--col-md-12-->
			</div><!--row-->

			<div class="row">
				<div class="col-md-12">
					<div class="btn-wrap align-right">
						<a href="all_books.php" class="btn-accent-arrow">View all books <i class="icon icon-ns-arrow-right"></i></a>
					</div>
				</div>
			</div>
		</div><!--container-->
	</section>


	<?php include 'footer.php'; ?>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

</body>

</html>