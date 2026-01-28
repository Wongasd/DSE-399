<?php
// Include the database connection file
include_once("database/db.php");

// Handle User Deletion
if (isset($_GET['ACTION']) && $_GET['ACTION'] === 'Delete' && isset($_GET['UserID'])) {
    $UserID = intval($_GET['UserID']);
    $query = "DELETE FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $UserID);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully'); window.location.href='all_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user'); window.location.href='all_users.php';</script>";
    }
    exit();
}

// Handle Author Deletion
if (isset($_GET['ACTION']) && $_GET['ACTION'] === 'Delete' && isset($_GET['AuthorID'])) {
    $AuthorID = intval($_GET['AuthorID']);
    $query = "DELETE FROM authors WHERE AuthorID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $AuthorID);

    if ($stmt->execute()) {
        echo "<script>alert('Author deleted successfully'); window.location.href='all_authors.php';</script>";
    } else {
        echo "<script>alert('Error deleting author'); window.location.href='all_authors.php';</script>";
    }
    exit();
}

// Handle Publisher Deletion
if (isset($_GET['ACTION']) && $_GET['ACTION'] === 'Delete' && isset($_GET['PublisherID'])) {
    $PublisherID = intval($_GET['PublisherID']);
    $query = "DELETE FROM publishers WHERE PublisherID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $PublisherID);

    if ($stmt->execute()) {
        echo "<script>alert('Publisher deleted successfully'); window.location.href='all_publishers.php';</script>";
    } else {
        echo "<script>alert('Error deleting publisher'); window.location.href='all_publishers.php';</script>";
    }
    exit();
}

// Handle Book Deletion
if (isset($_GET['ACTION']) && $_GET['ACTION'] === 'Delete' && isset($_GET['BookID'])) {
    $BookID = intval($_GET['BookID']);
    $query = "DELETE FROM books WHERE BookID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $BookID);

    if ($stmt->execute()) {
        echo "<script>alert('Book deleted successfully'); window.location.href='all_books.php';</script>";
    } else {
        echo "<script>alert('Error deleting book'); window.location.href='all_books.php';</script>";
    }
    exit();
}

// If no valid parameters are set, redirect to the homepage
echo "<script>alert('Invalid request'); window.location.href='index.php';</script>";
exit();
