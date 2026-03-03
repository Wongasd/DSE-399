<?php
include_once("database/db.php");

// Get book_id from URL
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['PageTitle']);
    $content = trim($_POST['Content']);
    $pageOrder = (int)$_POST['PageOrder'];

    // Handle illustration upload
    $illustrationPath = NULL;
    if (!empty($_FILES['Illustration']['name'])) {
        $uploadDir = 'db_image/';
        $fileName = time() . "_" . basename($_FILES['Illustration']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['Illustration']['tmp_name'], $targetFile)) {
            $illustrationPath = $targetFile;
        } else {
            echo "<script>alert('Failed to upload illustration.');</script>";
        }
    }

    // Insert new page securely
    $stmt = $conn->prepare("
        INSERT INTO novel_pages (BookID, PageTitle, Content, Illustration, PageOrder)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssi", $book_id, $title, $content, $illustrationPath, $pageOrder);
    $stmt->execute();

    echo "<script>alert('Page added successfully');window.location.href='view_book.php?book_id=$book_id';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book Page</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Add New Page for Book ID: <?php echo $book_id; ?></h2>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Page Title</label>
        <input type="text" name="PageTitle" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Content</label>
        <textarea name="Content" class="form-control" rows="8" required></textarea>
    </div>

    <div class="mb-3">
        <label>Illustration (optional)</label>
        <input type="file" name="Illustration" class="form-control">
    </div>

    <div class="mb-3">
        <label>Page Order</label>
        <input type="number" name="PageOrder" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Add Page</button>
    <a href="view_book.php?book_id=<?php echo $book_id; ?>" class="btn btn-secondary">Back</a>
</form>

</body>
</html>