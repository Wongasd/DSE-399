<?php
include_once('database/db.php');

if (isset($_GET['transactionID']) && isset($_GET['status'])) {

    $transactionID = intval($_GET['transactionID']);
    $status = $_GET['status'];

    // 1. Get current transaction + book info
    $sql = "SELECT t.*, b.Quantity AS AvailableQuantity 
            FROM transactions AS t 
            LEFT JOIN books AS b ON b.BookID = t.BookID 
            WHERE t.TransactionID = '$transactionID'";
    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($res);

    $borrowQuantity = $data['Quantity'];
    $currentQuantity = $data['AvailableQuantity'];
    $bookID = $data['BookID'];
    $currentStatus = $data['Status'];

    // 2. Set Return Date (if returning)
    $returnDateQuery = ($status == "RETURNED") 
        ? "ReturnDate = '" . date('Y-m-d') . "'" 
        : "ReturnDate = NULL";

    // 3. Calculate quantity change
    $afterUpdateQuantity = null;
    if ($status == "APPROVE") {
        $afterUpdateQuantity = $currentQuantity - $borrowQuantity;
    } elseif (($status == "PENDING" || $status == "RETURNED") && $currentStatus != "DENIED") {
        $afterUpdateQuantity = $currentQuantity + $borrowQuantity;
    }

    // 4. Update transaction row
    $updateTransactionSql = "UPDATE transactions SET Status = '$status', $returnDateQuery 
                             WHERE TransactionID = '$transactionID'";
    $updateTransaction = mysqli_query($conn, $updateTransactionSql);

    // 5. Update book quantity if needed
    $updateBook = true;
    if ($currentStatus != "DENIED" && $afterUpdateQuantity !== null) {
        $updateBookSql = "UPDATE books SET Quantity = $afterUpdateQuantity WHERE BookID = $bookID";
        $updateBook = mysqli_query($conn, $updateBookSql);
    }

    // 6. If returned, check if it is late → update to "ReturnedLate"
    if ($status == "RETURNED") {
        $checkSql = "SELECT DueDate, ReturnDate FROM transactions WHERE TransactionID = '$transactionID'";
        $checkRes = mysqli_query($conn, $checkSql);
        $rowCheck = mysqli_fetch_assoc($checkRes);

        if (!empty($rowCheck['DueDate']) && !empty($rowCheck['ReturnDate'])) {
            if (strtotime($rowCheck['ReturnDate']) > strtotime($rowCheck['DueDate'])) {
                // 🔹 Update late return permanently in DB
                mysqli_query($conn, "UPDATE transactions SET Status = 'ReturnedLate' WHERE TransactionID = '$transactionID'");
                $status = "ReturnedLate"; // update displayed status also
            }
        }
    }

    // 7. Send back updated row
    if ($updateTransaction && $updateBook) {
        $fetchQuery = "SELECT t.*, CONCAT(u.FirstName, ' ', u.LastName) AS FullName, b.Title
                       FROM transactions AS t
                       LEFT JOIN users AS u ON t.UserID = u.UserID
                       LEFT JOIN books AS b ON b.BookID = t.BookID
                       WHERE t.TransactionID = '$transactionID'";
        $fetchData = mysqli_query($conn, $fetchQuery);

        while ($row = mysqli_fetch_array($fetchData)) {
            echo "
            <td>{$row['TransactionID']}</td>
            <td>{$row['Title']}</td>
            <td>{$row['FullName']}</td>
            <td>{$row['BorrowDate']}</td>
            <td>{$row['ReturnDate']}</td>
            <td>{$row['DueDate']}</td>
            <td>{$status}</td>
            <td>{$row['Quantity']}</td>
            <td>";

            if ($status == "PENDING") {
                echo "<button class='btn btn-success' onclick='updateStatus(\"{$row['TransactionID']}\", \"APPROVE\", this)'>Approve</button>
                      <button class='btn btn-danger' onclick='updateStatus(\"{$row['TransactionID']}\", \"DENIED\", this)'>Denied</button>";
            } elseif ($status == "APPROVE") {
                echo "<button class='btn btn-primary' onclick='updateStatus(\"{$row['TransactionID']}\", \"RETURNED\", this)'>Return</button>";
            } elseif ($status != "RETURNED" && $status != "ReturnedLate") {
                echo "<button class='btn btn-warning' onclick='updateStatus(\"{$row['TransactionID']}\", \"PENDING\", this)'>Undo</button>";
            }

            echo "</td>";
        }
    }
}
?>
