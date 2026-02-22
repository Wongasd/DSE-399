<?php
include_once('database/db.php');

// Fetch borrow records based on permission
if ($_SESSION['Permission'] == '2') { // Regular user
    $UserID = $_SESSION['UserID'];
    $qry = mysqli_query($conn, "
        SELECT t.TransactionID, b.Title, CONCAT(u.FirstName, ' ', u.LastName) AS FullName,
               t.BorrowDate, t.ReturnDate, t.DueDate, t.Status, t.Quantity
        FROM transactions t
        JOIN books b ON t.BookID = b.BookID
        JOIN users u ON t.UserID = u.UserID
        WHERE t.UserID = '$UserID'
        ORDER BY t.BorrowDate DESC
    ");
} else { // Admin
    $qry = mysqli_query($conn, "
        SELECT t.TransactionID, b.Title, CONCAT(u.FirstName, ' ', u.LastName) AS FullName,
               t.BorrowDate, t.ReturnDate, t.DueDate, t.Status, t.Quantity
        FROM transactions t
        JOIN books b ON t.BookID = b.BookID
        JOIN users u ON t.UserID = u.UserID
        ORDER BY t.BorrowDate DESC
    ");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Borrow History</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

</head>

<body>
<div class="container mt-5">
    <button class="btn btn-danger mb-3" onclick="window.location.href='index.php'">Back</button>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Transaction ID</th>
                <th>Book Title</th>
                <th>Borrower Name</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($qry) == 0): ?>
            <tr>
                <td colspan="9" class="text-center text-muted">No borrow history available.</td>
            </tr>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($qry)): ?>
                <tr id="row-<?= $row['TransactionID'] ?>">
                    <td><?= $row['TransactionID'] ?></td>
                    <td><?= $row['Title'] ?></td>
                    <td><?= $row['FullName'] ?></td>
                    <td><?= $row['BorrowDate'] ?></td>
                    <td><?= $row['ReturnDate'] ?></td>
                    <td><?= $row['DueDate'] ?></td>
                    <td id="status-<?= $row['TransactionID'] ?>"><?= $row['Status'] ?></td>
                    <td><?= $row['Quantity'] ?></td>
                    <td>
                        <div class="row">
                            <?php if ($_SESSION['Permission'] == '1'): // Admin actions ?>
                                <?php if ($row['Status'] == 'PENDING'): ?>
                                    <div class="col">
                                        <button class="form-control btn btn-success"
                                                onclick="updateStatus(<?= $row['TransactionID'] ?>,'APPROVE', this)">Approve
                                        </button>
                                    </div>
                                    <div class="col">
                                        <button class="form-control btn btn-danger"
                                                onclick="updateStatus(<?= $row['TransactionID'] ?>,'DENIED', this)">Deny
                                        </button>
                                    </div>
                                <?php elseif ($row['Status'] == 'RETURN'): ?>
                                    <div class="col">
                                        <button class="form-control btn btn-primary"
                                                onclick="updateStatus(<?= $row['TransactionID'] ?>,'RETURNED', this)">Confirm Returned
                                        </button>
                                    </div>
                                <?php endif; ?>
                            <?php else: // User actions ?>
                                <?php if ($row['Status'] == 'APPROVE'): ?>
                                    <div class="col">
                                        <button class="form-control btn btn-warning"
                                                onclick="updateStatus(<?= $row['TransactionID'] ?>,'RETURN', this)">Return
                                        </button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
    
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
<script>

    function updateStatus(transactionID, newStatus, button) {
    let msg = newStatus === "RETURNED" ? "You cannot undo this action." : "";
    if (confirm("Are you sure you want to change the status to " + newStatus + "? " + msg)) {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                // Replace the whole row
                document.getElementById("row-" + transactionID).innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "ajax.php?transactionID=" + transactionID + "&status=" + newStatus, true);
        xhttp.send();
    }
}
</script>
