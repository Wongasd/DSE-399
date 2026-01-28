<?php
include_once("database/db.php");

if (!isset($_SESSION['Permission']) || $_SESSION['Permission'] !== '1') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $FirstName = trim($_POST['FirstName']);
    $LastName = trim($_POST['LastName']);
    $Description = trim($_POST['Description']);

    // Handling image upload
    $imageDir = "db_image/";
    $ImageName = $_FILES['Image']['name'];
    $ImageTemp = $_FILES['Image']['tmp_name'];

    // If user uploaded an image
    if (!empty($ImageName)) {
        // Get the image extension (e.g., jpg, png)
        $extension = pathinfo($ImageName, PATHINFO_EXTENSION);

        // Create unique filename -> author_64feb21d3d3a6.jpg
        $uniqueImageName = 'author_' . uniqid() . '.' . $extension;

        // Final path to save in database
        $ImagePath = $imageDir . $uniqueImageName;
    }

    // Check if the author already exists
    $qry = "SELECT * FROM authors WHERE FirstName = '$FirstName' AND LastName = '$LastName'";
    $result = mysqli_query($conn, $qry);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('That person is already in the database');</script>";
    } else {

        if (!empty($ImageName)) {
            if (move_uploaded_file($ImageTemp, $ImagePath)) {
                $query = "INSERT INTO authors (FirstName, LastName, Image, Description) 
                          VALUES ('$FirstName', '$LastName', '$ImagePath', '$Description')";
            } else {
                echo "<script>alert('Image upload failed. Please try again.');</script>";
                exit();
            }
        } else {
            // If no image uploaded, assign a default image
            $defaultImage = "db_image/default_author.png";
            $query = "INSERT INTO authors (FirstName, LastName, Image, Description) 
                      VALUES ('$FirstName', '$LastName', '$defaultImage', '$Description')";
        }

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Author created successfully'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error, Please Try Again');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Author</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <style>
        label {
            color: white;
        }
    </style>
</head>

<body>

    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1>Add Author</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="add_author.php" method="POST" enctype="multipart/form-data" class="registration-form">

                                    <div class="form-group">
                                        <label for="FirstName">First Name:</label>
                                        <input type="text" name="FirstName" placeholder="First name..." class="form-first-name form-control" id="FirstName" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="LastName">Last Name:</label>
                                        <input type="text" name="LastName" placeholder="Last name..." class="form-last-name form-control" id="LastName" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="Image">Profile Image:</label>
                                        <input type="file" name="Image" class="form-control" id="Image" accept="image/*" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="Description">Description:</label>
                                        <textarea name="Description" placeholder="Short description about the author..." class="form-control" id="Description" rows="4" required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button type="submit" class="btn btn-primary btn-block">Create</button>
                                            </div>
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-secondary btn-block" onclick="window.location.href='index.php'">Go Back</button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>

