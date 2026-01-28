<?php
// Include the database connection file
include_once("database/db.php");

$currentTime = date('Y-m-d');

// Check if BookID is passed in the URL
if (isset($_GET['BookID'])) {
    $BookID = $_GET['BookID'];

    // Fetch the book details from the database
    $query = "SELECT * FROM books WHERE BookID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $BookID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the book exists
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "<script>alert('Book not found'); window.location.href='index.php';</script>";
        exit();
    }

    // Handle form submission (editing)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $Title = $_POST['Title'];
        $Description = $_POST['Description'];
        $AuthorID = $_POST['AuthorID'];
        $PublisherID = $_POST['PublisherID'];
        
        $PublishedYear = $_POST['PublishedYear']; 

        // Check if a new image has been uploaded
        if (isset($_FILES['Image']) && $_FILES['Image']['error'] == 0) {
            // Define the target directory for uploaded images
            $targetDir = "db_image/";
            $targetFile = $targetDir . basename($_FILES["Image"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the file is an image
            if (getimagesize($_FILES["Image"]["tmp_name"]) === false) {
                echo "<script>alert('File is not an image');</script>";
                exit();
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["Image"]["tmp_name"], $targetFile)) {
                $Image = $targetFile;
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                exit();
            }
        } else {
            // If no image is uploaded, retain the existing image
            $Image = $book['Image'];
        }

        // Update book details, including the image if uploaded
        $queryUpdate = "UPDATE books SET Title = ?, Description = ?, AuthorID = ?, PublisherID = ?, PublishedYear = ?, Image = ? WHERE BookID = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("ssiissi", $Title, $Description, $AuthorID, $PublisherID, $PublishedYear, $Image, $BookID);
        $stmtUpdate->execute();

        // Redirect back to the list page after update
        echo "<script>alert('Book updated successfully'); window.location.href='all_books.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid BookID'); window.location.href='index.php';</script>";
    exit();
}

// Fetch authors for dropdown
$queryAuthors = "SELECT * FROM authors";
$resultAuthors = $conn->query($queryAuthors);

// Fetch publishers for dropdown
$queryPublishers = "SELECT * FROM publishers";
$resultPublishers = $conn->query($queryPublishers);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Book</title>
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

    <!-- <script>
        flatpickr("#PublishedYear", {
            dateFormat: "Y-m-d", // Matches the database format
        });
    </script> -->

</head>

<body>

<?php include "header.php"; ?>

<div class="container mt-4">
    <h2>Edit Book</h2>

    <form method="POST" action="edit_book.php?BookID=<?=$_GET['BookID']?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="Title">Title</label>
            <input type="text" class="form-control" id="Title" name="Title" value="<?php echo htmlspecialchars($book['Title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="Description">Description</label>
            <textarea class="form-control" id="Description" name="Description" rows="4" required><?php echo htmlspecialchars($book['Description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="AuthorID">Author</label>
            <select class="form-control" id="AuthorID" name="AuthorID" required>
                <option value="">Select Author</option>
                <?php while ($author = $resultAuthors->fetch_assoc()) { ?>
                    <option value="<?php echo $author['AuthorID']; ?>" <?php echo $author['AuthorID'] == $book['AuthorID'] ? 'selected' : ''; ?>><?php echo $author['FirstName'] . ' ' . $author['LastName']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="PublisherID">Publisher</label>
            <select class="form-control" id="PublisherID" name="PublisherID" required>
                <option value="">Select Publisher</option>
                <?php while ($publisher = $resultPublishers->fetch_assoc()) { ?>
                    <option value="<?php echo $publisher['PublisherID']; ?>" <?php echo $publisher['PublisherID'] == $book['PublisherID'] ? 'selected' : ''; ?>><?php echo $publisher['PublisherName']; ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="PublishedYear">Year Published </label>
            <input type="date" class="form-control" id="PublishedYear" name="PublishedYear" value="<?php echo htmlspecialchars($book['PublishedYear']); ?>"  max="<?php echo $currentDate; ?>" required>
        </div>

        
        <!-- Image upload field -->
        <div class="form-group">
            <label for="Image">Book Image</label>
            <input type="file" class="form-control" id="Image" name="Image">
            <small>Leave blank to keep the current image</small><br>
            <?php if (!empty($book['Image'])) { ?>
                <img src="<?php echo htmlspecialchars($book['Image']); ?>" alt="Current Image" class="img-thumbnail mt-2" style="width: 150px;">
            <?php } ?>
        </div>

        <div class="text-center mt-4">
            <input type="submit" class="btn btn-primary" value="Save Changes">
            <input type="button" class="btn btn-secondary" onclick="window.location.href='all_books.php'" value="Go Back">
            <input type="button" class="btn btn-danger" onclick="confirmDeletion(<?=$_GET['BookID']?>)" value="Delete">
        </div>
    </form>
</div>

<?php include "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
    
<script>
function confirmDeletion(bookID) {
    if (confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
        window.location.href = 'delete.php?ACTION=Delete&BookID=' + bookID;
    }
}
</script>

</body>

</html>
