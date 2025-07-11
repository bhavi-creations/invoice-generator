<?php
include("bhavidb.php");

// Get customer name from query string
if (!isset($_GET['name'])) {
    die("Customer name not provided.");
}

$cname = urldecode($_GET['name']);

// Fetch quotations for the customer
$stmt = $conn->prepare("SELECT * FROM quotation WHERE Cname = ?");
$stmt->bind_param("s", $cname);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($cname) ?> - Quotations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding-top: 20px;
        }
        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
        }
        .content {
            margin-left: 230px;
            padding: 30px;
        }
        .container-box {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        .btn-sm {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>Invoice System</h4>
    <a href="index.php">Dashboard</a>
    <a href="viewinvoices.php">Invoices</a>
    <a href="viewquotes.php" class="active">Quotations</a>
    <a href="viewcustomers.php">Customers</a>
    <a href="add_invoice.php">Add Invoice</a>
    <a href="add_quotation.php">Add Quotation</a>
</div>

<div class="content">
    <div class="container-box">
        <h3 class="mb-4">Quotations for <?= htmlspecialchars($cname) ?></h3>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Quotation No</th>
                    <th>Date</th>
                    <th>Company</th>
                    <th>Final Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    $sno = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$sno}</td>";
                        echo "<td>{$row['quotation_no']}</td>";
                        echo "<td>{$row['quotation_date']}</td>";
                        echo "<td>{$row['Company_name']}</td>";
                        echo "<td>₹ " . number_format($row['Grandtotal'], 2) . "</td>";
                        echo "<td>
                            <a href='view_quotation.php?quotation_no={$row['quotation_no']}' class='btn btn-info btn-sm'>View</a>
                            
                            <a href='edit_quotation.php?quotation_no={$row['quotation_no']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='convert_to_invoice.php?quotation_no={$row['quotation_no']}' class='btn btn-success btn-sm'>Convert</a>
                            <a href='delete_quotation.php?quotation_no={$row['quotation_no']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>";
                        
                        echo "</tr>";
                        $sno++;
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No quotations found for this customer.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
