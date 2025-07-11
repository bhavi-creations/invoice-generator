<?php
require_once('bhavidb.php');

$dataPoints = [];

$sql = "SELECT DATE_FORMAT(Invoice_date, '%M') AS month_name,
               MONTH(Invoice_date) AS month_num,
               SUM(Grandtotal) AS total_income
        FROM invoice
        WHERE YEAR(Invoice_date) = YEAR(CURDATE()) -- Only current year's income
        GROUP BY month_num
        ORDER BY month_num";

$result = $conn->query($sql);

while ($row = mysqli_fetch_assoc($result)) {
    $dataPoints[] = [
        "label" => $row['month_name'],
        "y" => (float)$row['total_income']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customers with Invoices</title>
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
                <div class="container">
                    <h2>Monthly Income Report - <?php echo date("Y"); ?></h2>
                    <div id="chartContainer" style="height: 350px; width: 100%;"></div>
                </div>

                <script>
                    window.onload = function() {
                        const chartData = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;

                        if (chartData.length === 0) {
                            document.getElementById('chartContainer').innerHTML = "<p style='text-align:center;color:#888;'>No income data available for <?php echo date("Y"); ?>.</p>";
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
                                color: "#4CAF50",
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