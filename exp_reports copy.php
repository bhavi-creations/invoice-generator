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


include('addcus-model.php');
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

    <style>
        .table.viewinvoicetable thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Add shadow to the bottom */
        }

        table {
            background-color: white;
            border-radius: 20px;
        }

        thead {
            background-color: white;
        }

        .nav-item {
            padding-top: 20px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 20px;
            /* text-align: center; */
        }

        .dropdown-content a {
            color: black;
            padding: 12 px 16px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }
    </style>


</head>

<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">

            <?php include('sidebar.php'); ?>


            <section class="col-lg-10 ">

                <div class="container">



                    <?php




                    require_once('bhavidb.php');

                    // Fetch paid invoices
                    $sql = "SELECT MONTH(date) AS month, SUM(total_amount) AS totalAmount
                    FROM expenditure_tbl
                    -- WHERE `status` = 'paid'
                    GROUP BY MONTH(date);";

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
                            const colors = [
                                "#ff5733", "#33c1ff", "#33ff57", "#ff33a1",
                                "#ffc133", "#9d33ff", "#33ffd1", "#ff3380",
                                "#a3ff33", "#3375ff", "#ff6f33", "#33fff9"
                            ];

                            let chartData = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;

                            // Add bright color to each bar dynamically
                            chartData = chartData.map((dp, index) => ({
                                ...dp,
                                color: colors[index % colors.length]
                            }));

                            var chart = new CanvasJS.Chart("chartContainer", {
                                animationEnabled: true,
                                exportEnabled: true,
                                theme: "light1",
                                title: {
                                    text: "Monthly Expenditure Status"
                                },
                                axisX: {
                                    title: "Months"
                                },
                                axisY: {
                                    title: "Expenditure",
                                    includeZero: true
                                },
                                data: [{
                                    type: "column",
                                    indexLabel: "{y}",
                                    indexLabelFontColor: "#5A5757",
                                    indexLabelPlacement: "outside",
                                    dataPoints: chartData
                                }]
                            });
                            chart.render();
                        }
                    </script>



                    <!-- Monthly Invoice Amount Chart -->
                    <div class="container  mt-5 mb-5  ">
                        <div class="  mango">
                            <h5 style="text-align: center;" class="mb-4"><strong>Monthly Expenditure Amount Chart</strong></h5>
                            <div id="chartContainer" style="height: 300px; "></div>
                        </div>
                    </div>




                    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

                </div>




                <div class="container " style="margin-top: 70px;">
                    <div class="table-responsive mango" style="max-height: 500px; max-width: 1194px; overflow-y: auto;">
                        <table class="table table-bordered viewinvoicetable">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: white;">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Expenditure Date</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Total Amount paid</th>
                                    <th style="  padding-right: 50px; padding-left: 50px;  ">Amount in words</th>
                                    <th style="  padding-right: 50px; padding-left: 50px;  ">Note</th>
                                    <!-- <th style="width: 10%;">Actions</th> -->
                                    <!-- <th style="width: 30%;">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody id="product_tbody viewinvoicetable">
                                <?php
                                $sql = "SELECT * FROM expenditure_tbl";
                                $result = $conn->query($sql);
                                // Loop through the fetched data and display it in the table
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr style='border: hidden;'>";
                                    echo "<td style='border: hidden;'>" . $row['id'] . "</td>";
                                    echo "<td style='border: hidden;'>" . $row['date'] . "</td>";
                                    echo "<td style='border: hidden;'>" . $row['total_amount'] .  "</td>";
                                    echo "<td style='border: hidden;'>" . $row['amount_in_words'] . "</td>";
                                    echo "<td style='border: hidden;'>" . $row['exp_note'] . "</td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                
            </section>
        </div>
    </div>
    <?php include('changepass-modal.php'); ?>
</body>

</html>