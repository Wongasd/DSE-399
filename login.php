<?php 
include_once("database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Email = trim($_POST['Email']);
    $Password = trim($_POST['Password']);

    $query = "SELECT u.*,CONCAT(u.FirstName, ' ', u.LastName) as FullName,p.PermissionName FROM `users` as u inner join permission as p on u.Permission = p.PermissionID WHERE Email = '".$Email."'";
    $sql = mysqli_query($conn,$query);
    $rows = mysqli_num_rows($sql);
	
    if($rows == 1){
        $row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

        $hashedPassword = $row['Password'];

        if(password_verify($Password, $hashedPassword)){

            $_SESSION['UserID'] = $row['UserID'];
            $_SESSION['Permission'] = $row['Permission'];
            $_SESSION['Status'] = $row['Status'];
            $_SESSION['UserName'] = $row['FullName'];

            echo "<script>alert('Login Success');window.location.href='index.php';</script>";
        }else{
            echo "<script>alert('Invalid Password');</script>";
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
    <title>Login!</title>

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
                        <h1>Login Now!</h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                                <form role="form" action="login.php" method="POST" class="registration-form">

                                    <div class="form-group">
                                        <label for="Email">Email</label>
                                        <input type="text" name="Email" placeholder="Enter Your Email..." class="form-email form-control" id="Email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Password">Password</label>
                                        <input type="text" name="Password" placeholder="Enter Your Password..." class="form-password form-control" id="Password" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                                            </div>
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-secondary btn-block" onclick="window.location.href='register.php'">Sign up</button>
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
