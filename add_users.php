<?php
include_once("database/db.php");

if (!isset($_SESSION['Permission']) || $_SESSION['Permission'] !== '1') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $FirstName = trim($_POST['FirstName']);
    $LastName = trim($_POST['LastName']);
    $Password = trim($_POST['Password']);
    $Email = trim($_POST['Email']);
    $Phone = trim($_POST['Phone']);
    $CountryCode = trim($_POST['CountryCode']);
    $Address = trim($_POST['Address']);
    $MembershipDate = date('Y-m-d');  // Set the current date for MembershipDate
    $Permission = trim($_POST['Permission']); // Assuming "Permission" is a field (e.g. 'admin', 'user')

    // Hash the password
    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

   // Handle the image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

    $imageDir = "db_image/"; // Correct folder path
    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageError = $image['error'];
    $imageType = $image['type'];

    // Allowed file extensions
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    // Check if file type is allowed
    if (in_array($imageExt, $allowed)) {
        // Check file size (limit to 2MB)
        if ($imageSize <= 2000000) {
            // Create a unique name for the image and move it to the upload folder
            $newImageName = uniqid('', true) . "." . $imageExt;
            $uploadPath = $imageDir . $newImageName; // Save to the correct path

            if (move_uploaded_file($imageTmpName, $uploadPath)) {
                // Proceed with inserting the user data and image path into the database
                $qry = "SELECT * FROM users WHERE Email = '$Email'";
                $result = mysqli_query($conn, $qry);
                $rows = mysqli_num_rows($result);

                if ($rows == 1) {
                    echo "<script>alert('User with this email already exists.');</script>";
                } else {
                    $Phone = $CountryCode . $Phone;
                    // Insert user data into the database
                    $query = "INSERT INTO users (FirstName, LastName, Password, Email, Phone, Address, MembershipDate, Permission, Image) 
                              VALUES ('$FirstName', '$LastName', '$hashedPassword', '$Email', '$Phone', '$Address', '$MembershipDate', '$Permission', '$newImageName')";
                    if ($sql = mysqli_query($conn, $query)) {
                        echo "<script>window.location.href='index.php';alert('User created successfully');</script>";
                    } else {
                        echo "<script>alert('Error, Please Try Again');</script>";
                    }
                }
            } else {
                echo "<script>alert('Error uploading the image.');</script>";
            }
        } else {
            echo "<script>alert('Image size exceeds 2MB limit.');</script>";
        }
    } else {
        echo "<script>alert('Invalid image type. Allowed types are jpg, jpeg, png, gif.');</script>";
    }
} else {
    $newImageName = null; // If no image is uploaded, set it as null
}


}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User</title>

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

        /* Custom image styles */
        .img-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .img-container img {
            max-width: 200px;
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>

    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1>Add User</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="add_users.php" method="POST" enctype="multipart/form-data" class="registration-form">

                                    <div class="form-group">
                                        <label for="FirstName">First Name:</label>
                                        <input type="text" name="FirstName" placeholder="First name..." class="form-control" id="FirstName" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="LastName">Last Name:</label>
                                        <input type="text" name="LastName" placeholder="Last name..." class="form-control" id="LastName" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Profile Image:</label>
                                        <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                    </div>

                                    <div class="form-group">
                                        <label for="Password">Password</label>
                                        <div class="input-group">
                                            <input type="password" name="Password" placeholder="Password..." class="form-password form-control" id="Password" required>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" onclick="togglePassword()">
                                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Email">Email:</label>
                                        <input type="email" name="Email" placeholder="Email..." class="form-control" id="Email" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <textarea name="Address" placeholder="Your Address" class="form-address form-control" id="Address"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="CountryCode">Country Code</label>
                                        <select name="CountryCode" id="CountryCode" class="form-control" onchange="updatePhoneLength()">
                                            <option value="+60">Malaysia (+60)</option>
                                            <option value="+65">Singapore (+65)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="Phone">Phone Number</label>
                                        <input type="tel" name="Phone" placeholder="Phone number..." class="form-phone form-control" id="Phone" required maxlength="10">
                                    </div>

                                    <div class="form-group">
                                        <label for="Permission">Permission:</label>
                                        <select name="Permission" class="form-control" id="Permission" required>
                                            <option value="1">Admin</option>
                                            <option value="2">User</option>
                                            <option value="3">Librarian</option>
                                        </select>
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
        
        function togglePassword() {
        const passwordField = document.getElementById("Password");
        const toggleIcon = document.getElementById("togglePasswordIcon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }
    </script>

</body>

</html>
