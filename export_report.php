<?php
session_start();
ob_start(); // Prevent unwanted output

require 'vendor/autoload.php';
include_once("database/db.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ✅ Check if User is Logged In
if (!isset($_SESSION['UserID'])) {
    die("Error: You must be logged in to generate reports.");
}
$userID = $_SESSION['UserID'];

// ✅ Get limit from URL
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 0;
$limitQuery = $limit > 0 ? "LIMIT $limit" : "";

// ✅ Summary Data
$totalBooks = $conn->query("SELECT COUNT(*) AS total FROM books")->fetch_assoc()['total'];
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$bannedUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE Status = 'Banned'")->fetch_assoc()['total'];
$borrowedBooks = $conn->query("SELECT COUNT(*) AS total FROM transactions WHERE ReturnDate IS NULL")->fetch_assoc()['total'];
$availableBooks = $conn->query("SELECT COUNT(*) AS total FROM books WHERE Status = 'Available'")->fetch_assoc()['total'];
$unavailableBooks = $conn->query("SELECT COUNT(*) AS total FROM books WHERE Status = 'Unavailable'")->fetch_assoc()['total'];

// ✅ Authors Table Data
$authorsQuery = $conn->query("SELECT AuthorID, CONCAT(FirstName, ' ', LastName) AS AuthorName, Description FROM authors $limitQuery");

// ✅ Books Table Data (With Author & Genre Name)
$booksQuery = $conn->query("
    SELECT 
        books.BookID, books.Title,
        CONCAT(authors.FirstName, ' ', authors.LastName) AS AuthorName,
        genres.GenreName AS GenreName,
        publishers.PublisherName AS PublisherName,
        books.Status
    FROM books
    LEFT JOIN authors ON books.AuthorID = authors.AuthorID
    LEFT JOIN genres ON books.GenreID = genres.GenreID
    LEFT JOIN publishers ON books.PublisherID = publishers.PublisherID
    $limitQuery
");

// ✅ Create Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ===== Title =====
$sheet->setCellValue('A1', 'Library Management System Report');
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

// ===== Summary Section =====
$sheet->setCellValue('A3', 'Category');
$sheet->setCellValue('B3', 'Count');
$sheet->getStyle('A3:B3')->getFont()->setBold(true);

$dataSummary = [
    ['Total Books', $totalBooks],
    ['Total Users', $totalUsers],
    ['Banned Users', $bannedUsers],
    ['Borrowed Books', $borrowedBooks],
    ['Available Books', $availableBooks],
    ['Unavailable Books', $unavailableBooks]
];

$row = 4;
foreach ($dataSummary as $summary) {
    $sheet->setCellValue("A$row", $summary[0]);
    $sheet->setCellValue("B$row", $summary[1]);
    $row++;
}

// ===== Authors Table Section =====
$row += 2;
$sheet->setCellValue("A$row", 'Author ID');
$sheet->setCellValue("B$row", 'Author Name');
$sheet->setCellValue("C$row", 'Description');
$sheet->getStyle("A$row:C$row")->getFont()->setBold(true);

$row++;
while ($author = $authorsQuery->fetch_assoc()) {
    $shortDesc = strlen($author['Description']) > 50 ? substr($author['Description'], 0, 50) . "..." : $author['Description'];
    $sheet->setCellValue("A$row", $author['AuthorID']);
    $sheet->setCellValue("B$row", $author['AuthorName']);
    $sheet->setCellValue("C$row", $shortDesc);
    $sheet->getStyle("C$row")->getAlignment()->setWrapText(true);
    $row++;
}

// ===== Book List Section =====
$row += 2;
$sheet->setCellValue("A$row", 'Book ID');
$sheet->setCellValue("B$row", 'Title');
$sheet->setCellValue("C$row", 'Author Name');
$sheet->setCellValue("D$row", 'Genre');
$sheet->setCellValue("E$row", 'Publisher');
$sheet->setCellValue("F$row", 'Status');
$sheet->getStyle("A$row:F$row")->getFont()->setBold(true);

$row++;
while ($book = $booksQuery->fetch_assoc()) {
    $shortTitle = strlen($book['Title']) > 40 ? substr($book['Title'], 0, 40) . "..." : $book['Title'];
    $sheet->setCellValue("A$row", $book['BookID']);
    $sheet->setCellValue("B$row", $shortTitle);
    $sheet->setCellValue("C$row", $book['AuthorName'] ?? 'Unknown');
    $sheet->setCellValue("D$row", $book['GenreName'] ?? 'Unknown');
    $sheet->setCellValue("E$row", $book['PublisherName'] ?? 'Unknown');
    $sheet->setCellValue("F$row", $book['Status']);
    $sheet->getStyle("B$row")->getAlignment()->setWrapText(true);
    $row++;
}

// ✅ Auto-size Columns
foreach (range('A', 'F') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// ✅ Insert Report Record into Database
$conn->query("INSERT INTO report (GenerateBy, ReportType, GeneratedDate)
              VALUES ('$userID', 'Excel Report', NOW())");

// ✅ Output Excel File
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="library_report.xlsx"');
header('Cache-Control: max-age=0');


$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
