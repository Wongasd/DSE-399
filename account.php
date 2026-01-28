<?php
// Include the database connection file
include_once("database/db.php");

// Check if UserID is passed in the URL
if (isset($_GET['UserID'])) {
    $UserID = $_GET['UserID'];

    // Fetch the user details from the database
    $queryUserDetails = "SELECT *, CONCAT(FirstName, ' ', LastName) AS FullName FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($queryUserDetails);
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $resultUser = $stmt->get_result();

    // Check if the user exists
    if ($resultUser->num_rows > 0) {
        $user = $resultUser->fetch_assoc();
    } else {
        echo "<script>alert('User not found'); window.location.href='index.php';</script>";
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
	<title>BookSaw - Free Book Store HTML CSS Template</title>
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

	<?php include "header.php"; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="text-center"><?php echo htmlspecialchars($user['FullName']); ?></h2>
                
                <div class="user-details">
                    <div class="row">
                        <!-- Display user profile image -->
                        <div class="col-md-4">
                            <img src="<?php echo !empty($user['Image']) ? htmlspecialchars($user['Image']) : 'db_image/default.jpg'; ?>" 
                                 alt="Profile Image" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['Phone']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['Address']); ?></p>
                            <p><strong>Membership Date:</strong> <?php echo htmlspecialchars($user['MembershipDate']); ?></p>
                        </div>
                    </div>

                    <!-- Optionally, you can add a "Go back" button -->
                    <div class="text-center mt-4">
                        <a href="edit_account.php?UserID=<?=$_SESSION['UserID']?>" class="btn btn-secondary">Edit Account</a>
                        <a href="index.php" class="btn btn-secondary">Go back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<?php include "footer.php"; ?>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

</body>

</html>
