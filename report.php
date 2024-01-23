<?php



session_start();
if(!isset($_SESSION['email'])){
    header('Location:index.php');
    exit();
}




require_once('bhavidb.php');

// echo $database;

$sql1 = "SELECT * FROM invoice WHERE `status` = 'pending';";

$result = $conn->query($sql1);

$sql2 = "SELECT * FROM invoice WHERE `status`= 'paid';";
$result2 = $conn->query($sql2);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHAVIINVOICE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>






    <link rel="stylesheet" href="img/style.css">
    <link rel="stylesheet" href="img/stylemi.css">


 

</head>

<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-2">
                <nav id="sidebarMenu" class="  collapse d-lg-block sidebar collapse bg-white">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#"><img src="img/Bhavi-Logo-2.png" alt="" height="88px" width="191px"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class=" navbar-collapse  " id="navbarNav">
                            <ul class="navbar-nav " style="margin-left: 10%; text-align: center;">
                            <li class="nav-item ">
                                    <a class="nav-link text-dark nav-links " href="viewcustomers.php">Customers</a>
                                </li>
                               



                                <li class="dropdown nav-item pt-4">
                                    <a class="nav-link text-dark nav-links" href="#">Quotation <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg></a>
                                    <div class="dropdown-content">
                                        <a class="nav-link text-dark" href="quotation.php">
                                            <h6>Create Quotation</h6>
                                        </a>

                                        <a class="nav-link text-dark" href="viewquotes.php">
                                            <h6>View Quotations</h6>
                                        </a>
                                    </div>
                                </li>

                                <!-- Invoice dropdown -->
                                <li class="dropdown nav-item pt-4">
                                    <a class="nav-link text-dark  nav-links " href="#">Invoice <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg></a>
                                    <div class="dropdown-content">

                                        <a class="nav-link text-dark " href="createinvoice.php">
                                            <h6>Create Invoice</h6>
                                        </a>
                                        <a class="nav-link text-dark" href="viewinvoices.php">
                                            <h6>View Invoices</h6>
                                        </a>

                                    </div>
                                </li>

                                <!-- <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="viewinvoices.php">View Invoices</a>
                        </li> -->
                        <li class="nav-item pt-4">
                                    <a class="nav-link text-dark nav-links" href="customized_edits.php">Customized Edits</a>
                                </li>
                               
                                <li class="nav-item pt-4">
                                    <a class="nav-link nav-links active-link" href="report.php">Reports</a>
                                </li>

                                <li class="nav-item pt-4">
                                    <a class="nav-link text-dark nav-links btn-danger" href="index.php">Sign Out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- SMALL SCREEN AND MEDIUM SCREEN  NAVBAR -->

                <nav class="navbar navbar-expand-lg navbar-light bg-light d-block d-lg-none ">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <!-- <a class="navbar-brand" href="#"><img src="img/Bhavi-Logo-2.png" alt="" height="50%" width="50%"></a> -->
                            <a class="navbar-brand" href="#">Navbar</a>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="customized_edits.php">Customized Edits</a>
                                </li>
                                <li class="dropdown nav-item pe-4">
                                    <a class="nav-link active text-primary" href="#">Invoice <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg></a>
                                    <div class="dropdown-content">
                                        <a class="nav-link text-dark" href="quotation.php">
                                            <h6>Create Quotation</h6>
                                        </a>
                                        <a class="nav-link text-dark" href="createinvoice.php">
                                            <h6>Create Invoice</h6>
                                        </a>
                                        <a class="nav-link text-dark" href="viewinvoices.php">
                                            <h6>View Invoices</h6>
                                        </a>
                                        <a class="nav-link text-dark" href="viewquotes.php">
                                            <h6>View Quotes</h6>
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="viewinvoices.php">VIEW INVOICES</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="viewcustomers.php">VIEW CUSTOMERS</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </section>

            <section class="col-lg-10">
                <div class="container " style="margin-top: 70px;">
                    <h5 style="text-align: center;" class="mb-4"><strong>Pending Invoices</strong></h5>
                    <div class="table-responsive ms-5" style="max-height: 350px; max-width: 1194px; overflow-y: auto;">
                        <table class="table table-bordered viewinvoicetable">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: #f2f2f2;">
                                <tr>
                                    <th class="text-center" style="width: 10%;">Invoice No</th>
                                    <th style="width: 20%;">Customer Name</th>
                                    <th style="width: 20%;">Issued Date</th>
                                    <th style="width: 20%;">Invoice Amount</th>
                                    <th style="width: 15%;">Advance</th>
                                    <th style="width: 15%;">Balance</th>
                                    <th style="width: 10%;">Status</th>
                                    <!-- <th style="width: 20%;">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody id="product_tbody viewinvoicetable">
                                <?php

                                $totalAdvance = 0;
                                $totalBalance = 0;
                                // Loop through the fetched data and display it in the table
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['Invoice_no'] . "</td>";
                                    echo "<td>" . $row['Cname'] . "</td>";
                                    echo "<td>" . $row['Invoice_date'] . "</td>";
                                    echo "<td>" . $row['Grandtotal'] . "</td>";
                                    echo "<td>" . $row['advance'] . "</td>";
                                    $totalAdvance += $row['advance'];
                                    echo "<td>" . $row['balance'] . "</td>";
                                    $totalBalance += $row['balance'];
                                    echo "<td>" . $row['status'] .  "</td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                            <tfoot style="background-color: #f2f2f2; position: sticky; bottom: 0; z-index: 1;">
                                <tr>
                                    <td>Total</td>
                                    <td colspan="3"></td>
                                    <td style="font-weight: bold;"><?php echo $totalAdvance ?></td>
                                    <td style="font-weight: bold;"><?php echo $totalBalance ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


                <div class="container pb-5" style="margin-top: 70px;">
                    <h5 style="text-align: center;" class="mb-4"><strong>Paid Invoices</strong></h5>
                    <div class="table-responsive ms-5" style="max-height: 350px; max-width: 1194px; overflow-y: auto;">
                        <table class="table table-bordered viewinvoicetable">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: #f2f2f2;">
                                <tr>
                                    <th class="text-center" style="width: 10%;">Invoice No</th>
                                    <th style="width: 30%;">Customer Name</th>
                                    <th style="width: 20%;">Issued Date</th>
                                    <th style="width: 20%;">Invoice Amount</th>
                                    <th style="width: 10%;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="product_tbody viewinvoicetable">
                                <?php
                                // Loop through the fetched data and display it in the table
                                while ($col = $result2->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $col['Invoice_no'] . "</td>";
                                    echo "<td>" . $col['Cname'] . "</td>";
                                    echo "<td>" . $col['Invoice_date'] . "</td>";
                                    echo "<td>" . $col['Grandtotal'] . "</td>";
                                    echo "<td>" . $col['status'] .  "</td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <?php




                require_once('bhavidb.php');

                // Fetch paid invoices
                $sql = "SELECT MONTH(Invoice_date) AS month, SUM(Grandtotal) AS totalAmount
        FROM invoice
        WHERE `status` = 'paid'
        GROUP BY MONTH(Invoice_date);";

                $result = $conn->query($sql);

                $dataPoints = array();

                // Loop through the fetched data and organize it for the chart
                while ($row = $result->fetch_assoc()) {
                    $dataPoints[] = array("label" => getMonthName($row['month']), "y" => $row['totalAmount']);
                }

                function getMonthName($monthNumber)
                {
                    $dateObj = DateTime::createFromFormat('!m', $monthNumber);
                    return $dateObj->format('F');
                }

                ?>

                <script>
                    window.onload = function() {
                        var chart = new CanvasJS.Chart("chartContainer", {
                            animationEnabled: true,
                            exportEnabled: true,
                            theme: "light1",
                            title: {
                                text: "Monthly Income Status"
                            },
                            axisX: {
                                title: "Months"
                            },
                            axisY: {
                                title: "Income",
                                includeZero: true
                            },
                            data: [{
                                type: "column",
                                indexLabel: "{y}",
                                indexLabelFontColor: "#5A5757",
                                indexLabelPlacement: "outside",
                                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                            }]
                        });
                        chart.render();
                    }
                </script>


                <!-- Monthly Invoice Amount Chart -->
                <div class="container  mt-5">
                    <h5 style="text-align: center;" class="mb-4"><strong>Monthly Invoice Amount (Paid) Chart</strong></h5>
                    <div id="chartContainer" style="height: 300px; width: 90%;"></div>
                </div>




                <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
            </section>
        </div>
    </div>
</body>

</html>