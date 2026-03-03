<?php
include_once("database/db.php");

$book_id = (int)$_GET['book_id'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

/* total pages */
$total = $conn->query("
    SELECT COUNT(*) AS total
    FROM novel_pages
    WHERE BookID = $book_id
")->fetch_assoc()['total'];

// Check if no pages exist
if ($total == 0) {
    echo "<script>
            alert('This book is under maintenance.');
            window.location.href='all_books_online.php';
          </script>";
    exit;
}

/* current page */
$result = $conn->query("
    SELECT *
    FROM novel_pages
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
    <title>Light Novel Reader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 0;
        }
        .novel-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        }
        .novel-illustration {
            width: 45%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .novel-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .novel-nav a {
            margin: 0 10px;
        }
    </style>
</head>
<body>

<div class="container novel-container">

    <h2 class="mb-4"><?php echo htmlspecialchars($data['PageTitle']); ?></h2>

    <?php if (!empty($data['Illustration'])) { ?>
        <img class="novel-illustration img-fluid" src="<?php echo htmlspecialchars($data['Illustration']); ?>" alt="Illustration">
    <?php } ?>

    <div class="novel-text">
        <?php echo nl2br(htmlspecialchars($data['Content'])); ?>
    </div>

    <div class="d-flex justify-content-between mb-4">
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
        <a href="all_books_online.php" class="btn btn-secondary">Back to All Books</a>
    </div>

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("keydown", function(e) {
    // Ignore when user is typing in inputs/textareas
    const tag = document.activeElement.tagName.toLowerCase();
    if (tag === "input" || tag === "textarea") return;

    const currentPage = <?php echo (int)$page; ?>;
    const totalPages = <?php echo (int)$total; ?>;
    const bookId = <?php echo (int)$book_id; ?>;

    // ➡ Arrow Right = Next
    if (e.key === "ArrowRight") {
        if (currentPage < totalPages) {
            window.location.href =
                `?book_id=${bookId}&page=${currentPage + 1}`;
        }
    }

    // ⬅ Arrow Left = Previous
    if (e.key === "ArrowLeft") {
        if (currentPage > 1) {
            window.location.href =
                `?book_id=${bookId}&page=${currentPage - 1}`;
        }
    }
});
</script>

</body>
</html>