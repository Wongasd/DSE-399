<?php
include_once("database/db.php");

// Get book_id from URL
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;


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


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['PageTitle']);
    $content = trim($_POST['Content']);
    $pageOrder = (int)$_POST['PageOrder'];

    $illustrationPath = NULL;

    /* Upload Image */
    if (!empty($_FILES['Illustration']['name'])) {

        // Get / create book folder
        $uploadDir = createBookFolder($conn, $book_id) . "/";

        // Create unique filename
        $fileName = "page_" . time() . "_" . basename($_FILES['Illustration']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['Illustration']['tmp_name'], $targetFile)) {
            $illustrationPath = $targetFile;
        } else {
            echo "<script>alert('Failed to upload illustration.');</script>";
        }
    }

    /* Insert into database */
    $stmt = $conn->prepare("
        INSERT INTO novel_pages (BookID, PageTitle, Content, Illustration, PageOrder)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("isssi", $book_id, $title, $content, $illustrationPath, $pageOrder);
    $stmt->execute();

    echo "<script>
            alert('Page added successfully');
            window.location.href='view_book.php?book_id=$book_id';
          </script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Book Datas</title>

<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

<style>
body{
    background:#f4f4f4;
}
.container-box{
    max-width:700px;
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

<h2>Add New Page</h2>
<hr>

<form method="POST" enctype="multipart/form-data">

<div class="form-group">
<label>Page Title</label>
<input type="text" name="PageTitle" class="form-control" required>
</div>

<br>

<div class="form-group">
<label>Page Content</label>
<textarea name="Content" class="form-control" rows="6" required></textarea>
</div>

<br>

<div class="form-group">
<label>Illustration (Optional)</label>
<input type="file" name="Illustration" class="form-control">
</div>

<br>

<div class="form-group">
<label>Page Order</label>
<input type="number" name="PageOrder" class="form-control" required>
</div>

<br>

<button type="submit" class="btn btn-success">Add Page</button>

<a href="view_book.php?book_id=<?php echo $book_id; ?>" class="btn btn-secondary">
Back
</a>

</form>

</div>

</body>
</html>