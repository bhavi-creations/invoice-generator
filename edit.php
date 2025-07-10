<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

require_once('bhavidb.php');

// --- 1. GET AND VALIDATE INVOICE ID ---
if (!isset($_GET['Sid']) || !is_numeric($_GET['Sid'])) {
    die("Invalid Invoice ID.");
}
$invoice_id = (int)$_GET['Sid'];

// --- 2. FETCH ALL INVOICE DATA SECURELY ---
// Fetch main invoice details
$stmt_invoice = $conn->prepare("SELECT * FROM invoice WHERE Sid = ?");
$stmt_invoice->bind_param("i", $invoice_id);
$stmt_invoice->execute();
$result_invoice = $stmt_invoice->get_result();
if ($result_invoice->num_rows === 0) {
    die("Invoice with ID $invoice_id not found.");
}
$invoice = $result_invoice->fetch_assoc();
$stmt_invoice->close();

// Fetch associated services
$services = [];
$stmt_services = $conn->prepare("SELECT * FROM service WHERE Sid = ?");
$stmt_services->bind_param("i", $invoice_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row;
}
$stmt_services->close();

// Fetch associated files
$files = [];
$stmt_files = $conn->prepare("SELECT * FROM invoice_files WHERE Invoice_id = ?");
$stmt_files->bind_param("i", $invoice_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
while ($row = $result_files->fetch_assoc()) {
    $files[] = $row;
}
$stmt_files->close();

// Fetch all customers for the dropdown
$all_customers = [];
$customer_result = $conn->query("SELECT Id, Company_name FROM customer");
while ($customer = $customer_result->fetch_assoc()) {
    $all_customers[] = $customer;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice - <?php echo htmlspecialchars($invoice['Invoice_no']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css">
    <style>
        body {
            background-color: #f9f9f9;
        }

        .invoice-form {
            background-color: white;
            border-radius: 25px;
            padding: 40px;
        }

        /* Include other necessary CSS from your main stylesheet if needed */
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="row">





            <section class="col-lg-12">
                <form class="invoice-form shadow-sm" action="edit_invoice.php" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="Sid" value="<?php echo $invoice_id; ?>">

                    <h2 class="text-center mb-4">Edit Invoice</h2>

                    <div class="row mb-5">
                        <div class="col-md-4">
                            <label for="companySelect" class="form-label"><strong>Customer/Company</strong></label>
                            <select name="company" id="companySelect">
                                <?php foreach ($all_customers as $customer) : ?>
                                    <option value="<?php echo $customer['Id']; ?>" <?php if ($customer['Id'] == $invoice['Customer_id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($customer['Company_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><strong>Date</strong></label>
                            <input type="date" name="invoice_date" class="form-control" value="<?php echo htmlspecialchars($invoice['Invoice_date']); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><strong>Invoice Number</strong></label>
                            <input type="text" name="invoice_no" class="form-control" value="BHAVI_KKD_2024_<?php echo htmlspecialchars($invoice['Invoice_no']); ?>" readonly>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <h4 class="mb-3">Services</h4>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Service</th>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th><button type="button" class="btn btn-primary btn-sm btn-add-row">+</button></th>
                                </tr>
                            </thead>
                            <tbody id="product_tbody">
                                <?php foreach ($services as $service) : ?>
                                    <tr>
                                        <td><input type="text" name="Sname[]" class="form-control" value="<?php echo htmlspecialchars($service['Sname']); ?>"></td>
                                        <td><textarea name="Description[]" class="form-control"><?php echo htmlspecialchars($service['Description']); ?></textarea></td>
                                        <td><input type="number" name="Qty[]" class="form-control qty" value="<?php echo htmlspecialchars($service['Qty']); ?>"></td>
                                        <td><input type="number" step="0.01" name="Price[]" class="form-control price" value="<?php echo htmlspecialchars($service['Price']); ?>"></td>
                                        <td><input type="text" name="total[]" class="form-control total" value="<?php echo htmlspecialchars($service['Finaltotal']); ?>" readonly></td>
                                        <td><button type="button" class="btn btn-danger btn-sm btn-remove-row">X</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Note:</h5>
                            <textarea name="note" class="form-control" rows="4"><?php echo htmlspecialchars($invoice['Note']); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <h5>Attachments</h5>
                            <?php if (!empty($files)): ?>
                                <p class="mb-1">Existing files (check box to delete):</p>
                                <?php foreach ($files as $file): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="delete_files[]" value="<?php echo $file['id']; ?>">
                                        <label class="form-check-label">
                                            <?php echo htmlspecialchars(substr($file['File_path'], strpos($file['File_path'], '-', strpos($file['File_path'], '-') + 1) + 1)); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <label class="form-label mt-2">Upload new files:</label>
                            <input type="file" name="attachments[]" class="form-control" multiple>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="payment-details">
                        <h5>Payment Details to Display on Invoice</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_details" value="office" <?php if ($invoice['payment_details_type'] == 'office') echo 'checked'; ?>>
                            <label class="form-check-label">Office Details</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_details" value="personal" <?php if ($invoice['payment_details_type'] == 'personal') echo 'checked'; ?>>
                            <label class="form-check-label">Personal Details</label>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                            <a href="viewinvoices.php" class="btn btn-secondary btn-lg me-3">Cancel / Go Back</a>

                        <button type="submit" name="update" class="btn btn-success btn-lg">Update Invoice</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select').selectize();
            // You can add your dynamic calculation and add/remove row JavaScript here
        });
    </script>
</body>

</html>