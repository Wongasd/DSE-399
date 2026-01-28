<?php
// Include the database connection file
include_once("database/db.php");

// ✅ Handle User Ban (Instead of Delete)
if (isset($_GET['ACTION']) && $_GET['ACTION'] === 'BAN' && isset($_GET['UserID'])) {
    $UserID = intval($_GET['UserID']);
    
    // Update user status to 'Banned'
    $query = "UPDATE users SET Status = 'Banned' WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $UserID);

    if ($stmt->execute()) {
        echo "<script>alert('User has been banned successfully'); window.location.href='all_users.php';</script>";
    } else {
        echo "<script>alert('Error banning user'); window.location.href='all_users.php';</script>";
    }
    exit();
}

if (isset($_GET['ACTION']) && $_GET['ACTION'] === 'BAN2' && isset($_GET['UserID'])) {
    $UserID = intval($_GET['UserID']);
    
    // Update user status to 'Banned'
    $query = "UPDATE users SET Status = 'Banned2' WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $UserID);

    if ($stmt->execute()) {
        echo "<script>alert('User has been banned successfully'); window.location.href='all_users.php';</script>";
    } else {
        echo "<script>alert('Error banning user'); window.location.href='all_users.php';</script>";
    }
    exit();
}

if (isset($_GET['ACTION']) && $_GET['ACTION'] === 'UNBAN' && isset($_GET['UserID'])) {
    $UserID = intval($_GET['UserID']);
    
    // Update user status to 'Banned'
    $query = "UPDATE users SET Status = 'Available' WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $UserID);

    if ($stmt->execute()) {
        echo "<script>alert('User has been unbanned successfully'); window.location.href='all_users.php';</script>";
    } else {
        echo "<script>alert('Error banning user'); window.location.href='all_users.php';</script>";
    }
    exit();
}

if (isset($_GET['ACTION']) && $_GET['ACTION'] === 'UNBAN2' && isset($_GET['UserID'])) {
    $UserID = intval($_GET['UserID']);
    
    // Update user status to 'Banned'
    $query = "UPDATE users SET Status = 'Borrowed' WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $UserID);

    if ($stmt->execute()) {
        echo "<script>alert('User has been unbanned successfully'); window.location.href='all_users.php';</script>";
    } else {
        echo "<script>alert('Error banning user'); window.location.href='all_users.php';</script>";
    }
    exit();
}
?>
