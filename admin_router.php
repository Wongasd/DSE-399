<?php
include_once("database/db.php");

// Get book_id from URL and validate
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

// if ($book_id <= 0) {
//     // Invalid book_id, redirect back
//     header("Location: all_books_data.php");
//     exit;
// }

/* ===============================
   FETCH BOOK TYPE
================================= */
$stmt = $conn->prepare("
    SELECT b.TypeID, t.TypeName
    FROM books b
    INNER JOIN type t ON b.TypeID = t.TypeID
    WHERE b.BookID = ?
");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Check if book exists
// if (!$row) {
//     header("Location: all_books_data.php");
//     exit;
// }

// Store type info
$type_id = (int)$row['TypeID'];
$type_name = strtolower($row['TypeName']);

/* ===============================
   REDIRECT BASED ON TYPE
================================= */
if ($type_name === 'manga') {
    header("Location: add_book_manga_page.php?book_id=$book_id&type_id=$type_id");
} else {
    header("Location: add_book_novel_page.php?book_id=$book_id&type_id=$type_id");
}
exit;
?>