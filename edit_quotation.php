<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}
require_once('bhavidb.php');

// Get the ID from the URL. e.g., edit_quotation.php?Sid=1
$quote_id = (isset($_GET['Sid']) ? (int)$_GET['Sid'] : 0);

if ($quote_id === 0) {
    die("Quotation ID is missing or invalid.");
}

// --- Fetch main quotation data ---
$stmt_quote = $conn->prepare("SELECT * FROM `quotation` WHERE Sid = ?");
$stmt_quote->bind_param("i", $quote_id);
$stmt_quote->execute();
$result_quote = $stmt_quote->get_result();
if ($result_quote->num_rows === 0) {
    die("Quotation not found.");
}
$quote = $result_quote->fetch_assoc();
$stmt_quote->close();

// --- Fetch the services for this quotation ---
$services = [];
$stmt_services = $conn->prepare("SELECT * FROM quservice WHERE Sid = ?");
$stmt_services->bind_param("i", $quote_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row;
}
$stmt_services->close();

// --- Fetch all customers for the dropdown ---
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
    <title>Edit Quotation - <?php echo htmlspecialchars($quote['quotation_no']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css">
    <link rel="stylesheet" href="img/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">



            <section class="col-lg-12">
                <div class="container my-5">
                    <form class="p-4 border rounded shadow-sm" action="edit_quotation_form.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="Sid" value="<?php echo $quote_id; ?>">

                        <h2 class="text-center mb-4">Edit Quotation</h2>

                        <div class="row mb-5">
                            <div class="col-md-4">
                                <label class="form-label"><strong>Customer/Company</strong></label>
                                <select name="company">
                                    <?php foreach ($all_customers as $customer) : ?>
                                        <option value="<?php echo $customer['Id']; ?>" <?php if ($customer['Company_name'] == $quote['Company_name']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($customer['Company_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><strong>Date</strong></label>
                                <input type="date" name="quotation_date" class="form-control" value="<?php echo htmlspecialchars($quote['quotation_date']); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><strong>Quotation Number</strong></label>
                                <input type="text" name="quotation_no" class="form-control" value="<?php echo htmlspecialchars($quote['quotation_no']); ?>" readonly>
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
                                        <th>Price/Unit</th>
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
                                <textarea name="note" class="form-control" rows="4"><?php echo htmlspecialchars($quote['Note']); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <h5>Attachments</h5>
                                <label class="form-label mt-2">Upload new files:</label>
                                <input type="file" name="attachments[]" class="form-control" multiple>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="payment-details">
                            <h5>Payment Details to Display on Quotation</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_details" value="office" <?php if (($quote['payment_details_type'] ?? 'office') == 'office') echo 'checked'; ?>>
                                <label class="form-check-label">Office Details</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_details" value="personal" <?php if (($quote['payment_details_type'] ?? '') == 'personal') echo 'checked'; ?>>
                                <label class="form-check-label">Personal Details</label>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <a href="viewquotes.php" class="btn btn-secondary btn-lg me-3">Cancel / Go Back</a>
                            <button type="submit" name="submit" class="btn btn-success btn-lg">Update Quotation</button>
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
            // Paste your full JavaScript for calculations, adding rows, and removing rows here.
        });
    </script>
</body>

</html>