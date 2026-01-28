<?php 
include_once('database/db.php');

if ($_SESSION['Permission'] == '1') {
    $where = " where t.UserID = '$_SESSION[UserID]'";
}else{
    $where ="";
}

if ($_SESSION['Permission'] == '2') {
    $UserID = $_SESSION['UserID'];
    $qry = mysqli_query($conn, "
        SELECT t.TransactionID, b.Title, 
               CONCAT(u.FirstName, ' ', u.LastName) AS FullName,
               t.BorrowDate, t.ReturnDate, t.DueDate, 
               t.Status, t.Quantity
        FROM transactions t
        JOIN books b ON t.BookID = b.BookID
        JOIN users u ON t.UserID = u.UserID
        WHERE t.UserID = '$UserID'
        ORDER BY t.BorrowDate DESC
    ");
}
// If admin → show all records
else if ($_SESSION['Permission'] == '1') {
    $qry = mysqli_query($conn, "
        SELECT t.TransactionID, b.Title, 
               CONCAT(u.FirstName, ' ', u.LastName) AS FullName,
               t.BorrowDate, t.ReturnDate, t.DueDate,
               t.Status, t.Quantity
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
        <button class="btn btn-danger" onclick="window.location.href='index.php'">back</button>
        
        <div class=" mt-2">
            <table class="table table-striped table-bordered table-hover ">
                <thead class="table-dark">
                    <th>transaction id</th>
                    <th>book title</th>
                    <th>borrower name</th>
                    <th>borrow date</th>
                    <th>return date</th>
                    <th>due date</th>
                    <th>status</th>
                    <th>borrow quantity</th>
                    <?php if ($_SESSION['Permission'] !== '2') { ?>
                    <th>action</th>
                    <?php } ?>
                </thead>
<tbody>
<?php
// If no borrow history exists
if (mysqli_num_rows($qry) == 0) { ?>
    <tr>
        <td colspan="<?=$_SESSION['Permission'] !== '2' ? '9' : '8'?>" class="text-center text-muted">
            You have not made any borrow yet.
        </td>
    </tr>
<?php
} else {
    // Show all records if available
    while($fetch = mysqli_fetch_array($qry)) { ?>
        <tr id="row-<?=$fetch['TransactionID']?>">
            <td><?=$fetch['TransactionID']?></td>
            <td><?=$fetch['Title']?></td>
            <td><?=$fetch['FullName']?></td>
            <td><?=$fetch['BorrowDate']?></td>
            <td><?=$fetch['ReturnDate']?></td>
            <td><?=$fetch['DueDate']?></td>
            <td><?=$fetch['Status']?></td>
            <td><?=$fetch['Quantity']?></td>
            
            <?php if ($_SESSION['Permission'] !== '2') { // Only admins see actions ?>
                <td>
                    <?php if($fetch['Status'] == 'PENDING'){ ?>
                        <div class='row'>
                            <div class='col'>
                                <button class="form-control btn btn-success" onclick="updateStatus(<?=$fetch['TransactionID']?>, 'APPROVE', this)">Approve</button>
                            </div> 
                            <div class='col'>
                                <button class="form-control btn btn-danger" onclick="updateStatus(<?=$fetch['TransactionID']?>, 'DENIED', this)">Denied</button>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class='row'>
                        <?php if($fetch['Status'] == 'APPROVE'){ ?>
                            <div class='col'>
                                <button class="form-control btn btn-primary" onclick="updateStatus(<?=$fetch['TransactionID']?>, 'RETURNED', this)">Return</button>
                            </div>
                        <?php } ?>
                        <?php if($fetch['Status'] !== 'RETURNED'){ ?>
                            <div class='col'>
                                <button class="form-control btn btn-danger" onclick="updateStatus(<?=$fetch['TransactionID']?>, 'PENDING', this)">Undo</button>
                            </div>
                        <?php } ?>
                        </div>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
<?php } } ?>
</tbody>
            </table>
        </div>
    </div>
    
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
<script>
    function updateStatus(transactionID, approvalStatus ,button) {

        if(approvalStatus == "RETURNED"){
            $extraMsg = " ,you can not undo the changes";
        }else{
            $extraMsg = "";
        }

        $msg = "are you sure you want to change this borrow status to "+ approvalStatus + $extraMsg;
        if (confirm($msg) == true) {
            const xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the specific row's status
                    document.getElementById("row-" + transactionID).innerHTML = this.responseText;
                }
            };

            xhttp.open("GET", `ajax.php?transactionID=${transactionID}&status=${approvalStatus}`, true);
            xhttp.send();
        }
    }
</script>
