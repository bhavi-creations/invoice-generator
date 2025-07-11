<?php
require_once('bhavidb.php');
session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $conn->query("DELETE FROM expenditure_tbl WHERE id = $deleteId");
    $conn->query("DELETE FROM expenditure_desc_tbl WHERE main_expenditure_id = $deleteId");
    echo "<script>location.reload();</script>";
    exit;
}

// Fetch full data
$sql = "SELECT 
            e.id, e.date, e.total_amount, e.amount_in_words, e.exp_note,
            d.exp_name, d.exp_description, d.mode_payment, d.amount
        FROM expenditure_tbl e
        LEFT JOIN expenditure_desc_tbl d ON e.id = d.main_expenditure_id
        ORDER BY e.id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHAVIINVOICE</title>

    <!-- BOOTSTRAP PLUGIN -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!-- jQuery -->


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>

    <!-- ADDING STYLE SHEET  -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

    <link rel="stylesheet" href="img/style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="img/stylemi.css">
    <style>
        .table thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #f2f2f2;
        }

        .btn-group .btn {
            margin-right: 5px;
        }
    </style>

</head>

<body>

    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>





            <!--  Table-->
            <section class="col-lg-10">
                <div class="container mt-5">
                    <h4 class="text-center mb-4">All Expenditure Records</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Mode of Payment</th>
                                    <th>Amount Paid</th>
                                    <th>Total</th>
                                    <th>In Words</th>
                                    <th>Note</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0):
                                    while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= htmlspecialchars($row['exp_name']) ?></td>
                                            <td><?= htmlspecialchars($row['date']) ?></td>
                                            <td><?= htmlspecialchars($row['exp_description']) ?></td>
                                            <td><?= htmlspecialchars($row['mode_payment']) ?></td>
                                            <td><?= htmlspecialchars($row['amount']) ?></td>
                                            <td><?= htmlspecialchars($row['total_amount']) ?></td>
                                            <td><?= htmlspecialchars($row['amount_in_words']) ?></td>
                                            <td><?= htmlspecialchars($row['exp_note']) ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="edit_expenditure.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <form method="POST" action="" onsubmit="return confirm('Delete this entry?');" style="display:inline;">
                                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No expenditure records found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>

            <?php include('addcus-model.php'); ?>


        </div>
    </div>

    

    
 
    <?php include('changepass-modal.php') ?>



</body>

</html>

<?php

$conn->close();
?>