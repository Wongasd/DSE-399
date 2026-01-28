<?php
// Include the database connection file
include_once("database/db.php");

// Check if PublisherID is passed in the URL
if (isset($_GET['PublisherID'])) {
    $PublisherID = $_GET['PublisherID'];

    // Fetch the publisher's details from the database
    $query = "SELECT * FROM publishers WHERE PublisherID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $PublisherID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the publisher exists
    if ($result->num_rows > 0) {
        $publisher = $result->fetch_assoc();
    } else {
        echo "<script>alert('Publisher not found'); window.location.href='index.php';</script>";
        exit();
    }

    // Handle form submission (editing)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $PublisherName = $_POST['PublisherName'];
        $Address = $_POST['Address'];
        $Phone = $_POST['Phone'];

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
            $Image = $publisher['Image'];
        }

        // Update the publisher's details
        $queryUpdate = "UPDATE publishers SET PublisherName = ?, Address = ?, Phone = ?, Image = ? WHERE PublisherID = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("ssssi", $PublisherName, $Address, $Phone, $Image, $PublisherID);
        $stmtUpdate->execute();

        // Redirect back to the publishers list page after update
        echo "<script>alert('Publisher updated successfully'); window.location.href='all_publishers.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid PublisherID'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Publisher</title>
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
    <h2>Edit Publisher</h2>

    <form method="POST" action="edit_publisher.php?PublisherID=<?php echo $_GET['PublisherID']; ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="PublisherName">Publisher Name</label>
            <input type="text" class="form-control" id="PublisherName" name="PublisherName" value="<?php echo htmlspecialchars($publisher['PublisherName']); ?>" required>
        </div>
        <div class="form-group">
            <label for="Address">Address</label>
            <input type="text" class="form-control" id="Address" name="Address" value="<?php echo htmlspecialchars($publisher['Address']); ?>" required>
        </div>
        <div class="form-group">
            <label for="Phone">Phone</label>
            <input type="text" class="form-control" id="Phone" name="Phone" value="<?php echo htmlspecialchars($publisher['Phone']); ?>" required>
        </div>
        
        <!-- Image Upload Field -->
        <div class="form-group">
            <label for="Image">Publisher Image</label>
            <input type="file" class="form-control" id="Image" name="Image">
            <small>Leave blank to keep the current image</small><br>
            <?php if (!empty($publisher['Image'])) { ?>
                <img src="<?php echo htmlspecialchars($publisher['Image']); ?>" alt="Current Image" class="img-thumbnail mt-2" style="width: 150px;">
            <?php } ?>
        </div>

        <div class="text-center mt-4">
            <input type="submit" class="btn btn-primary" value="Save Changes">
            <input type="button" class="btn btn-secondary" onclick="window.location.href='all_publishers.php'" value="Go Back">
        </div>
    </form>
</div>

<?php include "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
    
</script>
</body>

</html>
