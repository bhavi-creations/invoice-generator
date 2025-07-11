<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

include('bhavidb.php');

// Count totals
$sql = "SELECT COUNT(*) AS rowCount FROM `customer`";
$sql2 = "SELECT COUNT(*) AS rowCount2 FROM `invoice`";

$result = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql2);

$rowcount = $rowcount2 = 0;
if ($result && $result2) {
    $row = $result->fetch_assoc();
    $row2 = $result2->fetch_assoc();
    $rowcount = $row['rowCount'];
    $rowcount2 = $row2['rowCount2'];
}

// Build income chart
$dataPoints = [];
$colors = ['#FF0000', '#FF9900', '#33CC33', '#3366FF', '#CC33FF', '#00CCCC', '#FF3399', '#FFCC00', '#00CC66', '#FF6600', '#3399FF', '#FF0066'];


$colorIndex = 0;

$sql = "SELECT DATE_FORMAT(Invoice_date, '%M') AS month_name,
               MONTH(Invoice_date) AS month_num,
               SUM(Grandtotal) AS total_income
        FROM invoice
        WHERE YEAR(Invoice_date) = YEAR(CURDATE())
        GROUP BY month_num
        ORDER BY month_num";

$result = $conn->query($sql);
while ($row = mysqli_fetch_assoc($result)) {
    $dataPoints[] = [
        "label" => $row['month_name'],
        "y" => (float)$row['total_income'],
        "color" => $colors[$colorIndex++ % count($colors)]
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Income Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="img/style.css">
    <link rel="stylesheet" href="img/stylemi.css">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }

        .container {
            margin: 40px auto;
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #324960;
        }

        .card-customer {
            height: 150px;
            margin: auto;
            width: 150px;
            border-radius: 44px;
        }

        .input-cus {
            text-align: center;
            width: 109px;
            border: none;
            font-size: 30px;
        }

        .div-cus {
            text-align: center;
            font-size: 24px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php include('sidebar.php'); ?>

        <section class="col-lg-10">

            <div class="container mango">
                <div class="row">

                    <!-- Clients Card -->
                    <div class="col-lg-2 col-sm-6 card card-customer">
                        <div class="text-center ps-3 pt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="20" viewBox="0 0 16 16" fill="none">
                                <path d="M5.66629 7.22225C7.38451 7.22225 8.7774 5.82936 8.7774 4.11114C8.7774 2.39292 7.38451 1.00003 5.66629 1.00003C3.94807 1.00003 2.55518 2.39292 2.55518 4.11114C2.55518 5.82936 3.94807 7.22225 5.66629 7.22225Z" stroke="#3575FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M1 14.9995V13.4439C1 12.6188 1.32778 11.8275 1.91122 11.244C2.49467 10.6606 3.28599 10.3328 4.11111 10.3328H7.22222C8.04734 10.3328 8.83866 10.6606 9.42211 11.244C10.0056 11.8275 10.3333 12.6188 10.3333 13.4439V14.9995" stroke="#3575FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="div-cus">
                            <p><b>Clients</b></p>
                            <input class="input-cus" type="text" value="<?= $rowcount ?>" readonly>
                        </div>
                    </div>

                    <!-- Invoices Card -->
                    <div class="col-lg-2 col-sm-6 card card-customer">
                        <div class="text-center ps-3 pt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="20" fill="#007BFF" class="bi bi-receipt" viewBox="0 0 16 16">
                                <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27"/>
                                <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </div>
                        <div class="div-cus">
                            <p><b>Invoices</b></p>
                            <input class="input-cus" type="text" value="<?= $rowcount2 ?>" readonly>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Graph Section -->
            <div class="container">
                <h2>Monthly Income Report - <?= date("Y") ?></h2>
                <div id="chartContainer" style="height: 350px; width: 100%;"></div>
            </div>

            <script>
                window.onload = function () {
                    const chartData = <?= json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;

                    if (chartData.length === 0) {
                        document.getElementById('chartContainer').innerHTML = "<p style='text-align:center;color:#888;'>No income data available for <?= date("Y"); ?>.</p>";
                        return;
                    }

                    var chart = new CanvasJS.Chart("chartContainer", {
                        animationEnabled: true,
                        theme: "light2",
                        title: {
                            text: "Monthly Income Overview"
                        },
                        axisY: {
                            title: "Income (₹)",
                            prefix: "₹"
                        },
                        axisX: {
                            title: "Month"
                        },
                        data: [{
                            type: "column",
                            dataPoints: chartData
                        }]
                    });
                    chart.render();
                }
            </script>
        </section>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
