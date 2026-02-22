<?php
include_once('database/db.php');

if (isset($_GET['transactionID'], $_GET['status'])) {

    $transactionID = intval($_GET['transactionID']);
    $status = $_GET['status'];

    // Get transaction + book info
    $sql = "SELECT t.*, b.Quantity AS AvailableQuantity 
            FROM transactions t 
            LEFT JOIN books b ON b.BookID = t.BookID
            WHERE t.TransactionID='$transactionID'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    $borrowQty = $row['Quantity'];
    $bookID = $row['BookID'];
    $currentStatus = $row['Status'];

    // Update book quantity
    if ($status === "APPROVE") {
        mysqli_query($conn, "UPDATE books SET Quantity = Quantity - $borrowQty WHERE BookID=$bookID");
    } elseif ($status === "RETURNED") {
        mysqli_query($conn, "UPDATE books SET Quantity = Quantity + $borrowQty WHERE BookID=$bookID");
    }

    // Update transaction
    if ($status === "RETURN") {
        mysqli_query($conn, "UPDATE transactions SET Status='RETURN' WHERE TransactionID='$transactionID'");
    } else {
        $returnDate = $status === "RETURNED" ? date('Y-m-d') : "NULL";
        mysqli_query($conn, "UPDATE transactions SET Status='$status', ReturnDate=" . ($returnDate === "NULL" ? "NULL" : "'$returnDate'") . " WHERE TransactionID='$transactionID'");
    }

    // Late check
    if ($status === "RETURNED") {
        $check = mysqli_query($conn, "SELECT DueDate, ReturnDate FROM transactions WHERE TransactionID='$transactionID'");
        $c = mysqli_fetch_assoc($check);
        if (!empty($c['DueDate']) && strtotime($c['ReturnDate']) > strtotime($c['DueDate'])) {
            mysqli_query($conn, "UPDATE transactions SET Status='ReturnedLate' WHERE TransactionID='$transactionID'");
            $status = "ReturnedLate";
        }
    }

    // Fetch updated row
    $fetch = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT t.*, CONCAT(u.FirstName,' ',u.LastName) AS FullName, b.Title
        FROM transactions t
        LEFT JOIN users u ON u.UserID=t.UserID
        LEFT JOIN books b ON b.BookID=t.BookID
        WHERE t.TransactionID='$transactionID'
    "));

    // Render all <td>
    echo "
    <td>{$fetch['TransactionID']}</td>
    <td>{$fetch['Title']}</td>
    <td>{$fetch['FullName']}</td>
    <td>{$fetch['BorrowDate']}</td>
    <td>{$fetch['ReturnDate']}</td>
    <td>{$fetch['DueDate']}</td>
    <td>{$fetch['Status']}</td>
    <td>{$fetch['Quantity']}</td>
    <td><div class='row'>";

    if ($_SESSION['Permission'] === '1') { // Admin
        if ($fetch['Status'] === 'PENDING') {
            echo "<div class='col'><button class='form-control btn btn-success' onclick='updateStatus({$fetch['TransactionID']},\"APPROVE\",this)'>Approve</button></div>
                  <div class='col'><button class='form-control btn btn-danger' onclick='updateStatus({$fetch['TransactionID']},\"DENIED\",this)'>Deny</button></div>";
        } elseif ($fetch['Status'] === 'RETURN') {
            echo "<div class='col'><button class='form-control btn btn-primary' onclick='updateStatus({$fetch['TransactionID']},\"RETURNED\",this)'>Confirm Returned</button></div>";
        }
    } else { // User
        if ($fetch['Status'] === 'APPROVE') {
            echo "<div class='col'><button class='form-control btn btn-warning' onclick='updateStatus({$fetch['TransactionID']},\"RETURN\",this)'>Return</button></div>";
        }
    }

    echo "</div></td>";
}
?>