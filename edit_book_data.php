<?php
include_once("database/db.php");

$page_id = isset($_GET['page_id']) ? (int)$_GET['page_id'] : 0;

// Fetch current page data
$stmt = $conn->prepare("SELECT * FROM novel_pages WHERE PageID=?");
$stmt->bind_param("i", $page_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "<script>alert('Page not found');window.location.href='all_books_online.php';</script>";
    exit;
}

$data = $result->fetch_assoc();
$book_id = $data['BookID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['PageTitle']);
    $content = trim($_POST['Content']);
    $pageOrder = (int)$_POST['PageOrder'];

    // Handle illustration upload (optional)
    $illustrationPath = $data['Illustration']; // keep old if no new upload
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

    // Update page securely
    $stmt = $conn->prepare("
        UPDATE novel_pages
        SET PageTitle=?, Content=?, Illustration=?, PageOrder=?
        WHERE PageID=?
    ");
    $stmt->bind_param("sssii", $title, $content, $illustrationPath, $pageOrder, $page_id);
    $stmt->execute();

    echo "<script>alert('Page updated successfully');window.location.href='view_book.php?book_id=$book_id';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book Page</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Edit Page for Book ID: <?php echo $book_id; ?></h2>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Page Title</label>
        <input type="text" name="PageTitle" class="form-control" value="<?php echo htmlspecialchars($data['PageTitle']); ?>" required>
    </div>

    <div class="mb-3">
        <label>Content</label>
        <textarea name="Content" class="form-control" rows="8" required><?php echo htmlspecialchars($data['Content']); ?></textarea>
    </div>

    <div class="mb-3">
        <label>Current Illustration</label><br>
        <?php if (!empty($data['Illustration'])): ?>
            <img src="<?php echo htmlspecialchars($data['Illustration']); ?>" style="max-width:200px; margin-bottom:10px;">
        <?php else: ?>
            <p>No illustration uploaded</p>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label>Upload New Illustration (optional)</label>
        <input type="file" name="Illustration" class="form-control">
    </div>

    <div class="mb-3">
        <label>Page Order</label>
        <input type="number" name="PageOrder" class="form-control" value="<?php echo $data['PageOrder']; ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Update Page</button>
    <a href="view_book.php?book_id=<?php echo $book_id; ?>" class="btn btn-secondary">Back</a>
</form>

</body>
</html>