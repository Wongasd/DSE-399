<?php 
include_once("database/db.php");

if (!isset($_SESSION['Permission']) || $_SESSION['Permission'] !== '1') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $GenreName = trim($_POST['GenreName']);

    // Check if the genre already exists
    $qry = "SELECT * FROM genres WHERE GenreName = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("s", $GenreName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('This genre already exists in the database');</script>";
    } else {
        // Insert new genre
        $query = "INSERT INTO genres (GenreName) VALUES (?)";
        $stmtInsert = $conn->prepare($query);
        $stmtInsert->bind_param("s", $GenreName);

        if ($stmtInsert->execute()) {
            echo "<script>alert('Genre added successfully'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error adding genre. Please try again.');</script>";
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
    <title>Add Genre</title>

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
                        <h1>Add Genre</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="add_genre.php" method="POST" class="registration-form">
                                    <div class="form-group">
                                        <label for="GenreName">Genre Name :</label>
                                        <input type="text" name="GenreName" placeholder="Enter genre name..." class="form-control" id="GenreName" required>
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
</body>

</html>
