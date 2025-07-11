<?php
include("bhavidb.php");

// Fetch distinct customers who have quotations

$sql = "SELECT DISTINCT Cname, Cphone, Caddress, Company_name AS company FROM quotation ORDER BY Cname ASC";

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
    <style>
        .table th,
        .table td {
            border: 1px solid #dee2e6 !important;
            vertical-align: middle;
        }

        .nav-links {
            font-size: 15px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include('sidebar.php'); ?>


            <!-- Main Content -->
            <section class="col-lg-10">

                <!-- Main Content -->
                <div class="main-content mt-5">
                    <div class="container-box">
                        <h3 class="mb-4">Customers with Quotations</h3>

                        <div class="mb-3">
                            <input type="text" id="customer_filter" class="form-control" placeholder="Search customer by name or phone">
                        </div>

                        <table class="table table-bordered table-striped table-hover" id="customer_table">
                            <thead class="table-light">
                                <tr>
                                    <th>S.No</th>
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
                                    $sno = 1;
                                    while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $sno++ ?></td>
                                            <td><?= htmlspecialchars($row['Cname']) ?></td>
                                            <td><?= htmlspecialchars($row['Cphone']) ?></td>
                                            <td><?= htmlspecialchars($row['company']) ?></td>
                                            <td><?= htmlspecialchars($row['Caddress']) ?></td>
                                            <td>
                                                <a href="customer_quotes.php?name=<?= urlencode($row['Cname']) ?>" class="btn btn-primary btn-sm">
                                                    View Quotes
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No customers found with quotations.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById("customer_filter").addEventListener("input", function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#customer_table tbody tr");

            rows.forEach(row => {
                let name = row.cells[1].textContent.toLowerCase();
                let phone = row.cells[2].textContent.toLowerCase();
                row.style.display = (name.includes(filter) || phone.includes(filter)) ? "" : "none";
            });
        });
    </script>
</body>

</html>