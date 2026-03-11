<?php
include_once("database/db.php");

$Permission = isset($_SESSION['Permission']) ? $_SESSION['Permission'] : '';
$UserID = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';

// Define menu items for all users
$menuItems = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'All Books', 'url' => 'all_books.php'],
    ['title' => 'All Authors', 'url' => 'all_authors.php'],
    ['title' => 'All Publishers', 'url' => 'all_publishers.php'],
    ['title' => 'Read Online', 'url' => 'all_books_online.php']
];

// Admin / Editor additional pages
$adminItems = [
    ['title' => 'All Users', 'url' => 'all_users.php'],
    ['title' => 'Add Publishers', 'url' => 'add_publisher.php'],
    ['title' => 'Add Authors', 'url' => 'add_author.php'],
    ['title' => 'Add Users', 'url' => 'add_users.php'],
    ['title' => 'Add Books', 'url' => 'add_books.php'],
    ['title' => 'Add Genre', 'url' => 'add_genre.php'],
    ['title' => 'Add Book Pages', 'url' => 'all_books_data.php'],
    ['title' => 'Generate Report', 'url' => 'report.php'],
    ['title' => 'Borrow History', 'url' => 'borrow_list.php']
];

// Regular user pages
$userItems = [
    ['title' => 'Borrow History', 'url' => 'borrow_list.php']
];

// Combine based on permission
$menuToShow = $menuItems;

if ($Permission === '1') { // Admin
    $menuToShow = array_merge($menuToShow, $adminItems);
} elseif ($Permission === '3') { // Editor
    $menuToShow = array_merge($menuToShow, $adminItems); // same as editor
} elseif ($Permission === '2') { // Regular user
    $menuToShow = array_merge($menuToShow, $userItems);
}

// Optional: auto-redirect to first menu item (comment this if you don't want auto-redirect)
// header("Location: " . $menuToShow[count($menuItems)][url]); exit;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Pages</title>
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<style>
body {
    background: #f4f4f4;
    padding: 50px;
}
.container-box {
    max-width: 800px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
ul.page-list {
    list-style: none;
    padding: 0;
}
ul.page-list li {
    padding: 10px 0;
}
ul.page-list li a {
    text-decoration: none;
    font-size: 16px;
    color: #007bff;
}
ul.page-list li a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="container-box">
    <h2>All Accessible Pages</h2>
    <hr>
    <ul class="page-list">
        <?php foreach($menuToShow as $item): ?>
            <li>
                <a href="<?php echo $item['url']; ?>">
                    <?php echo htmlspecialchars($item['title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>