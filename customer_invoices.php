<?php
session_start();
require_once('bhavidb.php');

if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['name']) || empty($_GET['name'])) {
    echo "Customer name not specified.";
    exit();
}

$customer_name = $_GET['name'];

$stmt = $conn->prepare("SELECT * FROM invoice WHERE Cname = ? ORDER BY Invoice_date DESC");
$stmt->bind_param("s", $customer_name);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>


<body>
    <div class="container-fluid">
        <div class="row">


            <?php include('sidebar.php'); ?>


            <!-- Main Content -->
            <section class="col-lg-10">
                <div class="container mt-5">
                    <h4 class="mb-4">Invoices for: <strong><?= htmlspecialchars($customer_name) ?></strong></h4>

                    <div class="mb-3">
                        <input type="text" id="invoice_filter" class="form-control" placeholder="Search Invoice by Customer Name, Invoice No, Date...">
                    </div>

                    <div class="table-responsive mango" style="max-height: 650px; max-width: 1194px; overflow-y: auto;">
                        <table class="table table-bordered table-striped table-hover viewinvoicetable" id="invoice_table">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: white;">
                                <tr>
                                    <th class="text-center">Invoice No</th>
                                    <th>Customer Name</th>
                                    <th style="padding-right: 30px; padding-left: 30px;">Issued Date</th>
                                    <th>Invoice Amount</th>
                                    <th class="status">Status</th>
                                    <th style="padding-right: 30px; padding-left: 30px;">Advance Actions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr style="border: hidden;">
                                        <td><?= $row['Invoice_no'] ?></td>
                                        <td><?= $row['Cname'] ?></td>
                                        <td><?= $row['Invoice_date'] ?></td>
                                        <td><?= $row['Grandtotal'] ?></td>
                                        <td class="status" data-invoice-no="<?= $row['Invoice_no'] ?>"><?= $row['status'] ?></td>


                                        <td>
                                            <div class="btn-group">
                                                <!-- Edit Button with Blue Background -->
                                                <a class="view-button" href="edit.php?Sid=<?= $row['Sid'] ?>" title="Edit" style="background-color:rgb(54, 132, 215); padding: 6px 10px; border-radius: 5px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg>
                                                </a>

                                                <!-- History Button with Purple Background -->
                                                <span style="margin-left: 10px;"></span>
                                                <button style="border:none; background-color: #6f42c1; padding: 6px 10px; border-radius: 5px;" class="history-button" data-bs-toggle="modal" data-bs-target="#advance_frm" data-id="<?= $row['Invoice_no'] ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-clock-history" viewBox="0 0 16 16">
                                                        <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                        <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="btn-group">
                                                <!-- View Button with Green Background -->
                                                <a class="view-button" href="print.php?Sid=<?= $row['Sid'] ?>" title="View" style="background-color: #28a745; padding: 6px 10px; border-radius: 5px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                    </svg>
                                                </a>

                                                <!-- Delete Button with Red Background -->
                                                <span style="margin-left: 10px;"></span>
                                                <form method="POST" action="delete_invoice.php" onsubmit="return confirm('Are you sure you want to delete this invoice?');" style="display:inline;">
                                                    <input type="hidden" name="sid" value="<?= $row['Sid'] ?>">
                                                    <button type="submit" class="delete-button" style="border:none; background-color: #dc3545; padding: 6px 10px; border-radius: 5px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
                                                            <path d="M5.5 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5z" />
                                                            <path d="M8 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5z" />
                                                            <path d="M10.5 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5z" />
                                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1-1H2.5a1 1 0 0 1-1 1H1v1h14V3h-.5z" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <!-- Status Dropdown (unchanged) -->
                                                <span style="margin-left: 10px;"></span>
                                                <select name="status" class="status-dropdown" data-invoice-no="<?= $row['Invoice_no'] ?>">
                                                    <option value="">Status</option>
                                                    <option value="paid" <?= $row['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                                    <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                </select>
                                            </div>
                                        </td>


                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Advance Modal -->
                <div class="container">
                    <div class="modal" tabindex="-1" id="advance_frm">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Advance History</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- To be filled dynamically via JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>