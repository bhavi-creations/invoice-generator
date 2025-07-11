<?php
session_start();
require_once('bhavidb.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

// Fetch customers who have invoices (match by Name)
$sql = "SELECT DISTINCT c.Id, c.Name, c.Phone, c.Address, c.Company_name
        FROM customer c
        INNER JOIN invoice i ON c.Name = i.Cname
        ORDER BY c.Name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customers with Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="img/style.css">
    <link rel="stylesheet" href="img/stylemi.css">
 
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
                        <?php include('sidebar.php'); ?>


            <!-- Main Content -->
            <section class="col-lg-10">
                <div class="container mt-5">
                    <h3 class="mb-4">Customers with Invoices</h3>

                    <div class="mb-3">
                        <input type="text" id="customer_filter" class="form-control" placeholder="Search customer by name or phone">
                    </div>

                    <table class="table table-bordered table-striped table-hover" id="customer_table">
                        <thead class="table-light">
                            <tr>
                                <th>S.No</th> <!-- NEW -->
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Company</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && $result->num_rows > 0):
                                $sno = 1; // Initialize serial number
                                while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $sno++ ?></td> <!-- S.No. -->
                                        <td><?= htmlspecialchars($row['Name']) ?></td>
                                        <td><?= htmlspecialchars($row['Phone']) ?></td>
                                        <td><?= htmlspecialchars($row['Company_name']) ?></td>
                                        <td><?= htmlspecialchars($row['Address']) ?></td>
                                        <td>
                                            <a href="customer_invoices.php?name=<?= urlencode($row['Name']) ?>" class="btn btn-primary btn-sm">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No customers found with invoices.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Filter customer table
        $('#customer_filter').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#customer_table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>

</body>

</html>