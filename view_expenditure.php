<?php
require_once('bhavidb.php');
session_start();

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int) $_POST['delete_id'];
    $conn->query("DELETE FROM expenditure_tbl WHERE id = $deleteId");
    $conn->query("DELETE FROM expenditure_desc_tbl WHERE main_expenditure_id = $deleteId");
    echo "<script>location.reload();</script>";
    exit;
}

// Filters
$search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';
$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Build dynamic SQL
$sql = "SELECT 
            e.id, e.date, e.total_amount, e.amount_in_words, e.exp_note,
            d.exp_name, d.exp_description, d.mode_payment, d.amount
        FROM expenditure_tbl e
        LEFT JOIN expenditure_desc_tbl d ON e.id = d.main_expenditure_id
        WHERE 1=1";

// Add search filter
if (!empty($search)) {
    $sql .= " AND (
        d.exp_name LIKE '%$search%' OR 
        d.exp_description LIKE '%$search%' OR 
        d.mode_payment LIKE '%$search%' OR 
        e.exp_note LIKE '%$search%' OR 
        e.amount_in_words LIKE '%$search%'
    )";
}

// Add date range filter
if (!empty($fromDate)) {
    $sql .= " AND e.date >= '$fromDate'";
}
if (!empty($toDate)) {
    $sql .= " AND e.date <= '$toDate'";
}

$sql .= " ORDER BY e.id ASC";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">


<?php include('header.php'); ?>


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


                        <!-- WRAPPER WITHOUT X-SCROLL -->
                        <div class="container-fluid px-0"> <!-- removes container-side padding -->
                            <form method="GET" class="p-3 bg-light border rounded">

                                <div class="row g-3 align-items-end">

                                    <!-- Search -->
                                    <div class="col-md-3 col-sm-6">
                                        <label for="search" class="form-label">Search</label>
                                        <input type="text" name="search" id="search" class="form-control"
                                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                                            placeholder="Search Name, Desc, Mode, etc.">
                                    </div>

                                    <!-- From Date -->
                                    <div class="col-md-2 col-sm-6">
                                        <label for="from_date" class="form-label">From</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control"
                                            value="<?= isset($_GET['from_date']) ? $_GET['from_date'] : '' ?>">
                                    </div>

                                    <!-- To Date -->
                                    <div class="col-md-2 col-sm-6">
                                        <label for="to_date" class="form-label">To</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control"
                                            value="<?= isset($_GET['to_date']) ? $_GET['to_date'] : '' ?>">
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-md-2 col-sm-6">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>

                                    <!-- Reset -->
                                    <div class="col-md-2 col-sm-6">
                                        <a href="view_expenditure.php" class="btn btn-secondary w-100">Reset</a>
                                    </div>

                                </div>
                            </form>
                        </div>



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
                                                <div class="btn-group" role="group">

                                                    <!-- Edit Button -->
                                                    <a href="edit_expenditure.php?id=<?= $row['id'] ?>" class="btn btn-sm me-2" style="background-color: #ffc107; color: white;" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <form method="POST" action="" onsubmit="return confirm('Delete this entry?');" style="display:inline;">
                                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                                        <button type="submit" class="btn btn-sm" style="background-color: #dc3545; color: white;" title="Delete">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
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

        </div>
    </div>


</body>

</html>

<?php

$conn->close();
?>