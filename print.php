<?php
session_start();
require_once('bhavidb.php'); // Your database connection

// This line creates the full, absolute path for your images
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// 1. GET THE INVOICE ID SECURELY
if (!isset($_GET['Sid']) || !is_numeric($_GET['Sid'])) {
    die("Invalid Invoice ID.");
}
$invoice_id = (int)$_GET['Sid'];

// 2. FETCH THE MAIN INVOICE DATA
// Ensure stamp_image and signature_image are selected
$stmt_invoice = $conn->prepare("SELECT * FROM invoice WHERE Sid = ?");
$stmt_invoice->bind_param("i", $invoice_id);
$stmt_invoice->execute();
$result_invoice = $stmt_invoice->get_result();
if ($result_invoice->num_rows === 0) {
    die("Invoice not found.");
}
$invoice = $result_invoice->fetch_assoc();
$stmt_invoice->close();

// 3. FETCH THE SERVICE LINE ITEMS
$services = [];
$stmt_services = $conn->prepare("SELECT * FROM service WHERE Sid = ?");
$stmt_services->bind_param("i", $invoice_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row;
}
$stmt_services->close();

// 4. FETCH THE ATTACHED FILES
$files = [];
$stmt_files = $conn->prepare("SELECT * FROM invoice_files WHERE Invoice_id = ?");
$stmt_files->bind_param("i", $invoice_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
while ($row = $result_files->fetch_assoc()) {
    $files[] = $row;
}
$stmt_files->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo htmlspecialchars($invoice['Invoice_no']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="print.css" rel="stylesheet">


</head>

<body>
    <div class="container">
        <div class="row">
            <section>
                <div class="col-lg-12">

                    <div class="text-center my-4 no-print">
                        <a href="viewinvoices.php" class="btn btn-secondary">Go Back</a>
                        <button id="myPrintButton" class="btn btn-primary">Print</button>
<a href="save_invoice_as_pdf.php?Sid=<?php echo $invoice['Sid']; ?>" class="btn btn-success">Save as PDF</a>                    </div>

                    <?php
                    $is_pdf = isset($is_pdf) ? $is_pdf : false;



                    include 'print_content.php'; ?>

                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const printButton = document.getElementById("myPrintButton");
            if (printButton) {
                printButton.addEventListener("click", function() {
                    window.print();
                });
            }
        });
    </script>
</body>


</html>