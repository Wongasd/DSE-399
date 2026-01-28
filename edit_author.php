<?php
// Include the database connection file
include_once("database/db.php");

// Check if AuthorID is passed in the URL
if (isset($_GET['AuthorID'])) {
    $AuthorID = $_GET['AuthorID'];

    // Fetch the author's details from the database
    $query = "SELECT * FROM authors WHERE AuthorID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $AuthorID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the author exists
    if ($result->num_rows > 0) {
        $author = $result->fetch_assoc();
    } else {
        echo "<script>alert('Author not found'); window.location.href='index.php';</script>";
        exit();
    }

    // Handle form submission (editing)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $FirstName = $_POST['FirstName'];
        $LastName = $_POST['LastName'];
        $Description = $_POST['Description'];

        // Handle Image Upload
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
            // If no new image is uploaded, retain the existing image
            $Image = $author['Image'];
        }

        // Update the author's details
        $queryUpdate = "UPDATE authors SET FirstName = ?, LastName = ?, Image = ?, Description = ? WHERE AuthorID = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("ssssi", $FirstName, $LastName, $Image, $Description, $AuthorID);
        $stmtUpdate->execute();

        // Redirect back to the authors list page after update
        echo "<script>alert('Author updated successfully'); window.location.href='all_authors.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid AuthorID'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Author</title>
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

<?php include "header.php"; ?>

<div class="container mt-4">
    <h2>Edit Author</h2>

    <form method="POST" action="edit_author.php?AuthorID=<?php echo $_GET['AuthorID']; ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="FirstName">First Name</label>
            <input type="text" class="form-control" id="FirstName" name="FirstName" value="<?php echo htmlspecialchars($author['FirstName']); ?>" required>
        </div>
        <div class="form-group">
            <label for="LastName">Last Name</label>
            <input type="text" class="form-control" id="LastName" name="LastName" value="<?php echo htmlspecialchars($author['LastName']); ?>" required>
        </div>
        
        <!-- Description Field -->
        <div class="form-group">
            <label for="Description">Description</label>
            <textarea class="form-control" id="Description" name="Description" rows="4"><?php echo htmlspecialchars($author['Description']); ?></textarea>
        </div>

        <!-- Image Upload Field -->
        <div class="form-group">
            <label for="Image">Author Image</label>
            <input type="file" class="form-control" id="Image" name="Image">
            <small>Leave blank to keep the current image</small><br>
            <?php if (!empty($author['Image'])) { ?>
                <img src="<?php echo htmlspecialchars($author['Image']); ?>" alt="Current Image" class="img-thumbnail mt-2" style="width: 150px;">
            <?php } ?>
        </div>

        <div class="text-center mt-4">
            <input type="submit" class="btn btn-primary" value="Save Changes">
            <input type="button" class="btn btn-secondary" onclick="window.location.href='all_authors.php'" value="Go Back">
        </div>
    </form>
</div>

<?php include "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>
