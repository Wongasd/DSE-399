<?php
include_once("database/db.php");

$book_id = (int)$_GET['book_id'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

/* total pages */
$total = $conn->query("
    SELECT COUNT(*) AS total
    FROM manga_pages
    WHERE BookID = $book_id
")->fetch_assoc()['total'];

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
<html>
<head>
    <title>Manga Reader</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="manga-body">

<div class="manga-paged-container">

    <!-- page image -->
    <img class="manga-single-page"
         src="<?php echo $data['ImagePath']; ?>">

    <!-- navigation -->
    <div class="manga-nav">

        <?php if ($page > 1) { ?>
            <a href="?book_id=<?php echo $book_id; ?>&page=<?php echo $page-1; ?>">
                ⬅ Prev
            </a>
        <?php } ?>

        <span class="page-indicator">
            Page <?php echo $page; ?> / <?php echo $total; ?>
        </span>

        <?php if ($page < $total) { ?>
            <a href="?book_id=<?php echo $book_id; ?>&page=<?php echo $page+1; ?>">
                Next ➡
            </a>
        <?php } ?>

    </div>

    <!-- jump to page -->
    <form method="get" class="page-jump">
        <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
        <input type="number" name="page" min="1" max="<?php echo $total; ?>" placeholder="Page">
        <button type="submit">Go</button>
    </form>

</div>

</body>
</html>