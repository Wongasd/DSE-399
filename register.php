<?php
// Include the database connection file
include_once("database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $FirstName = trim($_POST['FirstName']);
    $LastName = trim($_POST['LastName']);
    $Email = trim($_POST['Email']);
    $Password = trim($_POST['Password']);
    $Phone = trim($_POST['Phone']);
    $CountryCode = trim($_POST['CountryCode']); // Get the country code
    $Address = trim($_POST['Address']);
    $MembershipDate = date('Y-m-d');  // Automatically sets the current date as membership date
    $Permission = 2;  // Automatically set permission as "borrower"

    $hashedPassword = password_hash($Password, PASSWORD_BCRYPT);

    // Handling image upload
    $imageDir = "db_image/";
    $ImageName = $_FILES['Image']['name'];
    $ImageTemp = $_FILES['Image']['tmp_name'];
    $ImagePath = $imageDir . basename($ImageName);

        if (empty($ImageName)) {
        $ImagePath = $imageDir . "default_pic.png";
    } else {
        // If user uploads an image → save it
        $ImagePath = $imageDir . basename($ImageName);
        move_uploaded_file($ImageTemp, $ImagePath);
    }

    // Check if the required fields are empty
    if (empty($FirstName) || empty($LastName) || empty($Email)) {
        echo "<script>alert('First Name, Last Name, Email, and Profile Image are required')</script>";
    } else {
        
        // Check if the email is already registered
        $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
        $stmt->bind_param("s", $Email); // Bind the email parameter
        $stmt->execute();
        $result = $stmt->get_result();

        // If email is found, show an error message
        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists')</script>";
        } else {
        
            // Concatenate the country code with the phone number
            $Phone = $CountryCode . $Phone;

            // Validate and upload the profile image
            if (move_uploaded_file($ImageTemp, $ImagePath)) {
                // If image upload is successful, insert data into the database
                $stmt = $conn->prepare("INSERT INTO users (FirstName, LastName, Email, Password, Phone, Address, MembershipDate, Permission, Image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $FirstName, $LastName, $Email, $hashedPassword, $Phone, $Address, $MembershipDate, $Permission, $ImagePath);

                // Execute the statement and check for success
                if ($stmt->execute()) {
                    echo "<script>alert('Register Success');window.location.href='login.php';</script>";
                    exit();  // Stop further execution of code
                } else {
                    echo "<script>alert('Error!, Please Try Again')</script>";
                }
            } else {
                echo "<script>alert('Image upload failed. Please try again.');</script>";
            }

            // Close statement
            $stmt->close();
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
    <title>Sign Up Your Account Here!</title>

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
                        <h1>Sign Up Now!</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="register.php" method="post" enctype="multipart/form-data" class="registration-form">
                                    <div class="form-group">
                                        <label for="FirstName">First name</label>
                                        <input type="text" name="FirstName" placeholder="First name..." class="form-first-name form-control" id="FirstName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="LastName">Last name</label>
                                        <input type="text" name="LastName" placeholder="Last name..." class="form-last-name form-control" id="LastName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Email">Email</label>
                                        <input type="email" name="Email" placeholder="Email..." class="form-email form-control" id="Email" required>
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
                                        <label for="Image">Profile Image</label>
                                        <input type="file" name="Image" class="form-control" id="Image" accept="image/*">
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button type="submit" class="btn btn-primary btn-block">Sign me up!</button>
                                            </div>
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-secondary btn-block" onclick="window.location.href='login.php'">Login</button>
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
