<?php
include_once("database/db.php");

$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

/* total pages */
$total = $conn->query("
    SELECT COUNT(*) AS total
    FROM manga_pages
    WHERE BookID = $book_id
")->fetch_assoc()['total'];

// Check if book has no pages
if ($total == 0) {
    echo "<script>
            alert('This manga is under maintenance.');
            window.location.href='all_manga_online.php';
          </script>";
    exit;
}

/* prevent overflow */
if ($page < 1) $page = 1;
if ($page > $total) $page = $total;

/* get current page */
$result = $conn->query("
    SELECT *
    FROM manga_pages
    WHERE BookID = $book_id
    ORDER BY PageOrder ASC
    LIMIT " . ($page-1) . ",1
");

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manga Reader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f7f7;
            padding: 40px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .manga-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        }
        .manga-page {
            width: 100%;
            height: auto;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .manga-nav a {
            margin: 0 10px;
        }
    </style>
</head>
<body>

<div class="container manga-container">

    <h2 class="mb-4"><?php echo htmlspecialchars($data['PageTitle']); ?></h2>

    <?php if (!empty($data['Image'])) { ?>
        <img class="manga-page img-fluid" src="<?php echo htmlspecialchars($data['Image']); ?>" alt="Manga Page">
    <?php } ?>

    <div class="d-flex justify-content-between mb-4 manga-nav">
        <?php if ($page > 1) { ?>
            <a href="?book_id=<?php echo $book_id; ?>&page=<?php echo $page-1; ?>" class="btn btn-outline-primary">⬅ Previous</a>
        <?php } else { ?>
            <div></div>
        <?php } ?>

        <?php if ($page < $total) { ?>
            <a href="?book_id=<?php echo $book_id; ?>&page=<?php echo $page+1; ?>" class="btn btn-outline-primary">Next ➡</a>
        <?php } else { ?>
            <div></div>
        <?php } ?>
    </div>

    <div class="text-center">
        <a href="all_manga_online.php" class="btn btn-secondary">Back to All Manga</a>
    </div>

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("keydown", function(e) {
    const tag = document.activeElement.tagName.toLowerCase();
    if (tag === "input" || tag === "textarea") return;

    const currentPage = <?php echo (int)$page; ?>;
    const totalPages = <?php echo (int)$total; ?>;
    const bookId = <?php echo (int)$book_id; ?>;

    // ArrowRight = Next
    if (e.key === "ArrowRight") {
        if (currentPage < totalPages) {
            window.location.href = `?book_id=${bookId}&page=${currentPage + 1}`;
        }
    }

    // ArrowLeft = Previous
    if (e.key === "ArrowLeft") {
        if (currentPage > 1) {
            window.location.href = `?book_id=${bookId}&page=${currentPage - 1}`;
        }
    }
});
</script>

</body>
</html>