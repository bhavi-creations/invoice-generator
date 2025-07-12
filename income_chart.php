<?php
require_once('bhavidb.php');


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



// Handle year filter
$selectedYear = isset($_POST['year']) ? (int)$_POST['year'] : (int)date('Y');

// Initialize arrays
$monthlyIncome = array_fill(1, 12, 0);
$monthlyExpense = array_fill(1, 12, 0);
$monthlyProfit = [];

// INCOME QUERY (group by month)
$sqlIncome = "SELECT MONTH(Invoice_date) AS month, SUM(Grandtotal) AS total
              FROM invoice
              WHERE YEAR(Invoice_date) = $selectedYear
              GROUP BY MONTH(Invoice_date)";
$resIncome = $conn->query($sqlIncome);
while ($row = $resIncome->fetch_assoc()) {
    $monthlyIncome[(int)$row['month']] = (float)$row['total'];
}

// EXPENDITURE QUERY (group by month)
$sqlExpense = "SELECT MONTH(date) AS month, SUM(total_amount) AS total
               FROM expenditure_tbl
               WHERE YEAR(date) = $selectedYear
               GROUP BY MONTH(date)";
$resExpense = $conn->query($sqlExpense);
while ($row = $resExpense->fetch_assoc()) {
    $monthlyExpense[(int)$row['month']] = (float)$row['total'];
}

// Calculate profit/loss
for ($m = 1; $m <= 12; $m++) {
    $monthlyProfit[$m] = $monthlyIncome[$m] - $monthlyExpense[$m];
}

// Format data for CanvasJS
function buildDataPoints($data)
{
    $points = [];
    for ($m = 1; $m <= 12; $m++) {
        $points[] = [
            "label" => date('F', mktime(0, 0, 0, $m, 1)),
            "y" => round($data[$m], 2)
        ];
    }
    return $points;
}

$dataPointsIncome = buildDataPoints($monthlyIncome);
$dataPointsExpense = buildDataPoints($monthlyExpense);
$dataPointsProfit = buildDataPoints($monthlyProfit);
?>


<!DOCTYPE html>
<html lang="en">


<?php include('header.php'); ?>

<style>
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

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #print-section,
        #print-section * {
            visibility: visible;
        }

        #print-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
        }

        .btn,
        .form-select,
        header,
        .sidebar,
        .navbar,
        form,
        footer {
            display: none !important;
        }

        .card-customer {
            display: inline-block !important;
            margin: 10px auto;
            text-align: center;
        }

        h2 {
            font-size: 20px;
            margin-top: 0;
            text-align: center;
        }
    }
</style>



<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">

            <?php include('sidebar.php'); ?>

            <section class="col-lg-10 ">

                <div id="print-section">


                    <div class="container mt-5" id="print-section">
                        <!-- YEAR HEADER -->
                        <div class="col-12">
                            <h2  class="text-center mb-4">Yearly Financial Summary – <?= $selectedYear ?></h2>
                        </div>

                    

                   
                       
                    </div>


                    <div class="row my-3">

                        <div class="col-lg-2 col-sm-6 card card-customer">
                            <div class="text-center ps-3 pt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="20" viewBox="0 0 16 16" fill="none">
                                    <path d="M5.66629 7.22225C7.38451 7.22225 8.7774 5.82936 8.7774 4.11114C8.7774 2.39292 7.38451 1.00003 5.66629 1.00003C3.94807 1.00003 2.55518 2.39292 2.55518 4.11114C2.55518 5.82936 3.94807 7.22225 5.66629 7.22225Z" stroke="#3575FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M1 14.9995V13.4439C1 12.6188 1.32778 11.8275 1.91122 11.244C2.49467 10.6606 3.28599 10.3328 4.11111 10.3328H7.22222C8.04734 10.3328 8.83866 10.6606 9.42211 11.244C10.0056 11.8275 10.3333 12.6188 10.3333 13.4439V14.9995" stroke="#3575FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="div-cus">
                                <p><b>Clients</b></p>
                                <input class="input-cus " style="z-index: 99;" type="text" value="<?= $rowcount ?>" readonly>
                            </div>
                        </div>
                        <div class="col-8">
                            <h2 class="text-center mb-4">Yearly Income vs Expenditure vs Profit</h2>

                            <!-- Year Filter + Export Buttons -->
                            <form method="POST" class="row g-3 justify-content-center mb-4">
                                <div class="col-md-3">
                                    <select name="year" class="form-select" required>
                                        <?php
                                        $currentYear = date('Y');
                                        for ($y = $currentYear; $y >= 2020; $y--): ?>
                                            <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>>
                                                <?= $y ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" onclick="exportToExcel()" class="btn btn-success w-100">📤 Excel</button>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" onclick="exportToPDF()" class="btn btn-danger w-100">📄 PDF</button>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" onclick="window.print()" class="btn btn-dark w-100">🖨️ Print</button>
                                </div>
                            </form>

                        </div>
                        <!-- Invoices Card -->
                        <div class="col-lg-2 col-sm-6 card card-customer">
                            <div class="text-center ps-3 pt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="20" fill="#007BFF" class="bi bi-receipt" viewBox="0 0 16 16">
                                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27" />
                                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
                                </svg>
                            </div>
                            <div class="div-cus">
                                <p><b>Invoices</b></p>
                                <input class="input-cus" style="z-index: 99;" type="text" value="<?= $rowcount2 ?>" readonly>
                            </div>
                        </div>

                    </div>


                    <!-- Charts -->
                    <div class="mb-5">
                        <h5>Monthly Income (<?= $selectedYear ?>)</h5>
                        <div id="incomeChart" style="height: 300px;"></div>
                    </div>

                    <div class="mb-5">
                        <h5>Monthly Expenditure (<?= $selectedYear ?>)</h5>
                        <div id="expenseChart" style="height: 300px;"></div>
                    </div>

                    <div class="mb-5">
                        <h5>Profit / Loss (<?= $selectedYear ?>)</h5>
                        <div id="profitChart" style="height: 300px;"></div>
                    </div>
                </div>
        </div>
        </section>

        <!-- Chart JS -->
        <script>
            window.onload = function() {
                // Income
                new CanvasJS.Chart("incomeChart", {
                    animationEnabled: true,
                    theme: "light2",
                    axisY: {
                        title: "Amount (₹)",
                        prefix: "₹"
                    },
                    data: [{
                        type: "column",
                        color: "#28a745",
                        dataPoints: <?= json_encode($dataPointsIncome, JSON_NUMERIC_CHECK); ?>
                    }]
                }).render();

                // Expense
                new CanvasJS.Chart("expenseChart", {
                    animationEnabled: true,
                    theme: "light2",
                    axisY: {
                        title: "Amount (₹)",
                        prefix: "₹"
                    },
                    data: [{
                        type: "column",
                        color: "#dc3545",
                        dataPoints: <?= json_encode($dataPointsExpense, JSON_NUMERIC_CHECK); ?>
                    }]
                }).render();

                // Profit/Loss
                new CanvasJS.Chart("profitChart", {
                    animationEnabled: true,
                    theme: "light2",
                    axisY: {
                        title: "Net Profit (₹)",
                        prefix: "₹"
                    },
                    data: [{
                        type: "column",
                        color: "#007bff",
                        dataPoints: <?= json_encode($dataPointsProfit, JSON_NUMERIC_CHECK); ?>
                    }]
                }).render();
            };
        </script>

        


        <!-- jsPDF + html2canvas + SheetJS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

        <script>
            function exportToPDF() {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');

                html2canvas(document.querySelector("#print-section")).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const imgProps = doc.getImageProperties(imgData);
                    const pdfWidth = doc.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    doc.save("Monthly_Report_<?= $selectedYear ?>.pdf");
                });
            }

            function exportToExcel() {
                const incomeData = <?= json_encode($dataPointsIncome) ?>;
                const expenseData = <?= json_encode($dataPointsExpense) ?>;
                const profitData = <?= json_encode($dataPointsProfit) ?>;

                const worksheet = XLSX.utils.book_new();
                const sheetData = [
                    ["Month", "Income", "Expenditure", "Profit/Loss"]
                ];

                for (let i = 0; i < incomeData.length; i++) {
                    sheetData.push([
                        incomeData[i].label,
                        incomeData[i].y,
                        expenseData[i]?.y || 0,
                        profitData[i]?.y || 0
                    ]);
                }

                const sheet = XLSX.utils.aoa_to_sheet(sheetData);
                XLSX.utils.book_append_sheet(worksheet, sheet, "Yearly Summary");

                XLSX.writeFile(worksheet, "Monthly_Report_<?= $selectedYear ?>.xlsx");
            }
        </script>



</body>

</html>