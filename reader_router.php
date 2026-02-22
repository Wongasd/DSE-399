<?php
include_once("database/db.php");

// Safety check: must be logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

$book_id = (int)$_GET['book_id'];
$type_id = (int)$_GET['type_id'];
$user_id = (int)$_SESSION['UserID'];

/* ===============================
   GET USER MEMBERSHIP + STATUS
================================= */
$stmt = $conn->prepare("
    SELECT MembershipDate, Status 
    FROM users 
    WHERE UserID = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$userRow = $userResult->fetch_assoc();

if (!$userRow) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$membershipDate = $userRow['MembershipDate'];
$userStatus = strtolower($userRow['Status']);

/* ===============================
   🚫 CHECK IF USER IS BANNED
================================= */
if ($userStatus === 'banned') {
    echo "<script>
        alert('Your account has been banned from reading online books.');
        window.location.href='index.php';
    </script>";
    exit;
}

/* ===============================
   CHECK MEMBERSHIP EXPIRY
================================= */
$membershipExpire = strtotime($membershipDate . ' +1 month');
$today = time();

if ($today > $membershipExpire) {
    echo "<script>
        alert('Your membership has expired. You cannot read online books.');
        window.location.href='all_books_online.php';
    </script>";
    exit;
}

/* ===============================
   DETECT BOOK TYPE
================================= */
$stmt = $conn->prepare("
    SELECT t.TypeName
    FROM books b
    INNER JOIN type t ON b.TypeID = t.TypeID
    WHERE b.BookID = ?
");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    header("Location: all_books_online.php");
    exit;
}

$type = strtolower($row['TypeName']);

/* ===============================
   REDIRECT BASED ON TYPE
================================= */
if ($type === 'manga') {
    header("Location: manga_reader.php?book_id=$book_id&type_id=$type_id");
} else {
    header("Location: novel_reader.php?book_id=$book_id&type_id=$type_id");
}
exit;
?>