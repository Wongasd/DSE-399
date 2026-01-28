<?php
// Include the database connection file
include_once("database/db.php");

// Fetch users from the database
$queryUsers = "
    SELECT u.UserID, CONCAT(u.FirstName, ' ', u.LastName) AS FullName, u.Email, u.Permission, u.Phone, u.Address, u.Status
    FROM users u
    ORDER BY FullName ASC";
$resultUsers = mysqli_query($conn, $queryUsers);

// Check for database errors
if (!$resultUsers) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>All Users</title>
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

    <?php include 'header.php'; ?>

    <section id="all-users" class="users-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="section-header align-center">
                        <h2 class="section-title">All Users</h2>
                    </div>

                    <div class="row">
                        <?php while ($user = mysqli_fetch_assoc($resultUsers)): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($user['FullName']); ?></h5>
                                        <p class="card-text">
                                            <strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?><br>
                                            <strong>Phone:</strong> <?php echo htmlspecialchars($user['Phone']); ?><br>
                                            <strong>Address:</strong> <?php echo htmlspecialchars($user['Address']); ?><br>
                                            <strong>Permission:</strong>
                                            <?php
                                            if ($user['Permission'] == '1') {
                                                echo "Admin";
                                            } elseif ($user['Permission'] == '3') {
                                                echo "Librarian";
                                            } else {
                                                echo "User";
                                            }
                                            ?>
                                        </p>
                                        <div class="text-center">
                                            <?php if ($_SESSION['Permission'] == '1') { ?>
                                                <button class="btn btn-primary"
                                                        onclick="window.location.href='edit_user.php?UserID=<?php echo $user['UserID']; ?>'">
                                                    Edit
                                                </button>
                                            <?php if ($user['Status'] == 'Banned') { ?>
                                                <button class="btn btn-success"
                                                        onclick="if(confirm('Unban this user?')) { window.location.href='ban_user.php?ACTION=UNBAN&UserID=<?php echo $user['UserID']; ?>'; }">
                                                    Unban
                                                </button> 
                                            <?php }elseif($user['Status'] == 'Borrowed') { ?>
                                                <button class="btn btn-warning"
                                                         onclick="if(confirm('Are you sure you want to ban this user?')) { window.location.href='ban_user.php?ACTION=BAN2&UserID=<?php echo $user['UserID']; ?>'; }">
                                                    BAN
                                                </button>                                         
                                            <?php }elseif($user['Status'] == 'Available'){ ?>
                                                <button class="btn btn-warning"
                                                         onclick="if(confirm('Are you sure you want to ban this user?')) { window.location.href='ban_user.php?ACTION=BAN&UserID=<?php echo $user['UserID']; ?>'; }">
                                                    BAN
                                                </button>                                               
                                            <?php }else{ ?>
                                                <button class="btn btn-success"
                                                         onclick="if(confirm('Are you sure you want to ban this user?')) { window.location.href='ban_user.php?ACTION=UNBAN2&UserID=<?php echo $user['UserID']; ?>'; }">
                                                    UnBAN
                                                </button>                                              
                                            <?php } ?>       
                                                <button class="btn btn-danger"
                                                        onclick="if(confirm('Are you sure you want to delete this user?')) { window.location.href='delete.php?ACTION=Delete&UserID=<?php echo $user['UserID']; ?>'; }">
                                                    Delete
                                                </button>
                                            <?php } else { ?>
                                                <button class="btn btn-secondary"
                                                        onclick="alert('You do not have permission to perform this action.');">
                                                    View
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
            crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>

</body>

</html>
