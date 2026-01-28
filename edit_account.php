<?php
// Include the database connection file
include_once("database/db.php");

// Get the UserID from the session or URL
if (isset($_SESSION['UserID'])) {
    $UserID = $_SESSION['UserID'];

    // Fetch the user's details from the database using the UserID
    $query = "SELECT * FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('User not found'); window.location.href='index.php';</script>";
        exit();
    }

    // Handle form submission (editing)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $FirstName = $_POST['FirstName'];
        $LastName = $_POST['LastName'];
        $Email = $_POST['Email'];
        $Phone = $_POST['Phone'];
        $Address = $_POST['Address'];
        $MembershipDate = $user['MembershipDate'];  // Keep the existing MembershipDate
        $Password = $_POST['Password'];
        
        // Handle password hashing if a new password is provided
        if (!empty($Password)) {
            $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
        } else {
            // If no new password is provided, retain the existing password
            $hashedPassword = $user['Password'];
        }

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
            $Image = $user['Image'];
        }

        // Update the user's details in the database
        $queryUpdate = "UPDATE users SET FirstName = ?, LastName = ?, Email = ?, Phone = ?, Address = ?, Image = ?, Password = ? WHERE UserID = ?";
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bind_param("sssssssi", $FirstName, $LastName, $Email, $Phone, $Address, $Image, $hashedPassword, $UserID);
        $stmtUpdate->execute();

        // Redirect back to the account page after update
        echo "<script>alert('Account updated successfully'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid UserID'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Account</title>
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
    <h2>Edit Account</h2>

    <form method="POST" action="edit_account.php?UserID=<?=$_GET['UserID']?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="FirstName">First Name</label>
            <input type="text" class="form-control" id="FirstName" name="FirstName" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required>
        </div>
        <div class="form-group">
            <label for="LastName">Last Name</label>
            <input type="text" class="form-control" id="LastName" name="LastName" value="<?php echo htmlspecialchars($user['LastName']); ?>" required>
        </div>
                <!-- Password Field -->
        <div class="form-group">
            <label for="Password">Password</label>
            <input type="password" class="form-control" id="Password" name="Password" placeholder="Leave blank to keep current password">
        </div>
        <div class="form-group">
            <label for="Email">Email</label>
            <input type="email" class="form-control" id="Email" name="Email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="Phone">Phone</label>
            <input type="text" class="form-control" id="Phone" name="Phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
        </div>
        <div class="form-group">
            <label for="Address">Address</label>
            <textarea class="form-control" id="Address" name="Address" rows="3" required><?php echo htmlspecialchars($user['Address']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="MembershipDate">Membership Date</label>
            <input type="text" class="form-control" id="MembershipDate" name="MembershipDate" value="<?php echo htmlspecialchars($user['MembershipDate']); ?>" disabled>
        </div>

        <!-- Image Upload Field -->
        <div class="form-group">
            <label for="Image">Profile Image</label>
            <input type="file" class="form-control" id="Image" name="Image">
            <small>Leave blank to keep the current image</small><br>
            <?php if (!empty($user['Image'])) { ?>
                <img src="<?php echo htmlspecialchars($user['Image']); ?>" alt="Current Image" class="img-thumbnail mt-2" style="width: 150px;">
            <?php } ?>
        </div>

        <div class="text-center mt-4">
            <input type="submit" class="btn btn-primary" value="Save Changes">
            <input type="button" class="btn btn-secondary" onclick="window.location.href='account.php?UserID=<?=$_GET['UserID']?>'" value="Go Back">
        </div>
    </form>
</div>

<?php include "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>
