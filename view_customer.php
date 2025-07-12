<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

require_once('bhavidb.php');

// Validate and fetch customer
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid customer ID.");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM customer WHERE Id = $id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    die("Customer not found.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>


<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include('sidebar.php'); ?>

            <!-- Main Content -->
            <section class="col-lg-10">

                <div class="container mt-5">
                    <a href="viewcustomers.php" class="btn btn-secondary mb-4">
                        <i class="bi bi-arrow-left-circle"></i> Back to Customer List
                    </a>

                    <div class="card shadow p-4">
                        <h4 class="mb-4 text-center">Customer / Firm Details</h4>

                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">Firm Name:</label>
                            <div class="col-sm-9"><?= htmlspecialchars($row['Company_name']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">Client Name:</label>
                            <div class="col-sm-9"><?= htmlspecialchars($row['Name']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">Phone:</label>
                            <div class="col-sm-9"><?= htmlspecialchars($row['Phone']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">Email:</label>
                            <div class="col-sm-9"><?= htmlspecialchars($row['Email']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">Address:</label>
                            <div class="col-sm-9"><?= htmlspecialchars($row['Address']) ?></div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 fw-bold">GST Number:</label>
                            <div class="col-sm-9"><?= htmlspecialchars($row['Gst_no']) ?></div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>