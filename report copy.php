<?php
session_start();
if (!isset($_SESSION['email'])) {
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


<?php include('header.php'); ?>

<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">

            <?php include('sidebar.php'); ?>

            <section class="col-lg-10 ">
                <div class="container mango" style="margin-top: 70px;">
                    <h5 style="text-align: center;" class="mb-4"><strong>Pending Invoices</strong></h5>
                    <div class="table-responsive  " style="max-height: 350px; max-width: 1194px; overflow-y: auto;">
                        <div class="mb-3">
                            <input type="text" id="report_filter" class="form-control" placeholder="Search Report by Name, Invoice No, etc.">
                        </div>
                        <table class="table table-bordered viewinvoicetable" id="report_table">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: #f2f2f2;">
                                <tr>
                                    <th class="text-center">Invoice No</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Customer Name</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Issued Date</th>
                                    <th>Invoice Amount</th>
                                    <th>Advance</th>
                                    <th>Balance</th>
                                    <th>Status</th>
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


                <div class="container mango pb-5" style="margin-top: 70px;">
                    <h5 style="text-align: center;" class="mb-4"><strong>Paid Invoices</strong></h5>
                    <div class=" " style="max-height: 350px; max-width: 1194px; overflow-y: auto;">

                        <div class="mb-3">
                            <input type="text" id="report_filter_1" class="form-control" placeholder="Search Report by Name, Invoice No, etc.">
                        </div>
                        <table class="table table-bordered viewinvoicetable" id="report_table_1">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: #f2f2f2;">
                                <tr>
                                    <th class="text-center">Invoice No</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Customer Name</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Issued Date</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Invoice Amount</th>
                                    <th>Status</th>
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





                    $(document).ready(function() {
                        $('#report_filter').on('keyup', function() {
                            var value = $(this).val().toLowerCase();
                            $('#report_table tbody tr').filter(function() {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                            });
                        });
                    });




                    $(document).ready(function() {
                        $('#report_filter_1').on('keyup', function() {
                            var value = $(this).val().toLowerCase();
                            $('#report_table_1 tbody tr').filter(function() {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                            });
                        });
                    });
                </script>


               
            </section>
        </div>
    </div>
   


</body>

</html>