<?php
include_once("database/db.php");

// Get book_id from URL
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

/* =====================================================
   FUNCTION: CREATE BOOK FOLDER BASED ON BOOK TITLE
===================================================== */
function createBookFolder($conn, $book_id) {

    // Get book title
    $stmt = $conn->prepare("SELECT Title FROM books WHERE BookID=?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        return false;
    }

    // Clean book title for folder name
    $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $book['Title']);

    // Base directory
    $baseDir = "db_image/";

    // Full folder path
    $folderPath = $baseDir . $folderName;

    // Create folder if not exist
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    return $folderPath;
}

/* =====================================================
   HANDLE FORM SUBMISSION
===================================================== */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $pageOrder = (int)$_POST['PageOrder'];
    $imagePath = NULL;

    /* Upload Manga Page Image */
    if (!empty($_FILES['PageImage']['name'])) {

        // Get / create book folder
        $uploadDir = createBookFolder($conn, $book_id) . "/";

        // Create unique filename
        $fileName = "page_" . time() . "_" . basename($_FILES['PageImage']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['PageImage']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            echo "<script>alert('Failed to upload manga page image.');</script>";
        }
    } else {
        echo "<script>alert('Please select an image.');</script>";
        exit;
    }

    /* Insert into database */
    $stmt = $conn->prepare("
        INSERT INTO manga_pages (BookID, ImagePath, PageOrder)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("isi", $book_id, $imagePath, $pageOrder);
    $stmt->execute();

    echo "<script>
            alert('Manga page added successfully');
            window.location.href='view_manga.php?book_id=$book_id';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Manga Page</title>

<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

<style>
body{
    background:#f4f4f4;
}
.container-box{
    max-width:600px;
    margin:auto;
    margin-top:60px;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<div class="container-box">

<h2>Add Manga Page</h2>
<hr>

<form method="POST" enctype="multipart/form-data">

<div class="form-group">
<label>Manga Page Image</label>
<input type="file" name="PageImage" class="form-control" required>
</div>

<br>

<div class="form-group">
<label>Page Order</label>
<input type="number" name="PageOrder" class="form-control" required>
</div>

<br>

<button type="submit" class="btn btn-success">Add Page</button>

<a href="view_manga.php?book_id=<?php echo $book_id; ?>" class="btn btn-secondary">Back</a>

</form>

</div>

</body>
</html>