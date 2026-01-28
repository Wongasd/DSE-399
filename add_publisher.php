<?php 
include_once("database/db.php");

// Check for admin permission (if required)
if (!isset($_SESSION['Permission']) || $_SESSION['Permission'] !== '1') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $PublisherName = trim($_POST['PublisherName']);
    $Address = trim($_POST['Address']);
    $Phone = trim($_POST['Phone']);
    $CountryCode = trim($_POST['CountryCode']);
    $PublisherImage = trim($_POST['PublisherImage']); 

    // Handle image upload
    if (isset($_FILES['PublisherImage']) && $_FILES['PublisherImage']['error'] == 0) {
        $imageTmpPath = $_FILES['PublisherImage']['tmp_name'];
        $imageName = $_FILES['PublisherImage']['name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageNewName = uniqid('publisher_') . '.' . $imageExtension;

        // Set the upload directory
        $uploadDir = 'db_image/';

        // Check if the directory exists, if not, create it
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the directory
        $imagePath = $uploadDir . $imageNewName;
        move_uploaded_file($imageTmpPath, $imagePath);
        $PublisherImage = $imagePath; // Save the path to the image
    }

    // Check if publisher already exists
    $qry = "SELECT * FROM publishers WHERE PublisherName = '".$PublisherName."'";
    $result = mysqli_query($conn, $qry);
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        echo "<script>alert('This publisher already exists in the database');</script>";
    } else {
        $Phone = $CountryCode . $Phone;
        // Insert new publisher with image path
        $query = "INSERT INTO publishers (PublisherName, Address, Phone, Image) 
                  VALUES ('$PublisherName', '$Address', '$Phone', '$PublisherImage')";
        if ($sql = mysqli_query($conn, $query)) {
            echo "<script>window.location.href='index.php';alert('Publisher added successfully');</script>";
        } else {
            echo "<script>alert('Error, please try again');</script>";
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
    <title>Add Publishers</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <style>
        label {
            color: white; /* Set label text color to white */
        }
    </style>
</head>

<body>
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1>Add Publishers</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="add_publisher.php" method="POST" enctype="multipart/form-data" class="registration-form">

                                    <div class="form-group">
                                        <label for="PublisherName">Publisher Name :</label>
                                        <input type="text" name="PublisherName" placeholder="Publisher name..." class="form-control" id="PublisherName" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="PublisherImage">Profile Image:</label>
                                        <input type="file" name="PublisherImage" class="form-control" id="PublisherImage" accept="image/*" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="Address">Address :</label>
                                        <textarea name="Address" placeholder="Address" class="form-address form-control" id="Address"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="CountryCode">Country Code :</label>
                                        <select name="CountryCode" id="CountryCode" class="form-control" onchange="updatePhoneLength()">
                                            <option value="+60">Malaysia (+60)</option>
                                            <option value="+65">Singapore (+65)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="Phone">Phone Number :</label>
                                        <input type="tel" name="Phone" placeholder="Phone number..." class="form-phone form-control" id="Phone" required maxlength="10">
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button type="submit" class="btn btn-primary btn-block">Create</button>
                                            </div>
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-secondary btn-block" onclick="window.location.href='index.php'">Go back</button>
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

    <script>
        // Function to update the phone number length based on the selected country code
        function updatePhoneLength() {
            var countryCode = document.getElementById('CountryCode').value;
            var phoneField = document.getElementById('Phone');

            // Set maxlength based on selected country code
            if (countryCode === "+60") { // Malaysia
                phoneField.setAttribute('maxlength', '10');
            } else if (countryCode === "+65") { // Singapore
                phoneField.setAttribute('maxlength', '8');
            }
        }

        // Call the function initially to set the correct length
        updatePhoneLength();


    </script>

</body>

</html>
