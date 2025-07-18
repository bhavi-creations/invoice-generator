<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

require_once('bhavidb.php');

// --- 1. GET AND VALIDATE INVOICE ID ---
if (!isset($_GET['Sid']) || !is_numeric($_GET['Sid'])) {
    die("Invalid Invoice ID. Please go back to the invoice list.");
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

// --- 3. FETCH THE SPECIFIC CUSTOMER DETAILS FOR THIS INVOICE ---
// This assumes 'Customer_id' column exists in your 'invoice' table.
$current_customer_details = [
    'Company_name' => '', 'Name' => '', 'Email' => '', 'Phone' => '', 'Gst_no' => ''
];
if (!empty($invoice['Customer_id'])) {
    $stmt_current_customer = $conn->prepare("SELECT Company_name, Name, Email, Phone, Gst_no FROM customer WHERE Id = ?");
    $stmt_current_customer->bind_param("i", $invoice['Customer_id']);
    $stmt_current_customer->execute();
    $result_current_customer = $stmt_current_customer->get_result();
    if ($result_current_customer->num_rows > 0) {
        $current_customer_details = $result_current_customer->fetch_assoc();
    }
    $stmt_current_customer->close();
}


// Fetch all customers for the dropdown
$all_customers = [];
$customer_result = $conn->query("SELECT Id, Company_name, Name, Email, Phone, Gst_no FROM customer"); // Fetch all customer details for JS
$fetched_customer_data = []; // For JavaScript
while ($customer = $customer_result->fetch_assoc()) {
    $all_customers[] = $customer;
    $fetched_customer_data[] = $customer; // Store for JSON encode
}

// Fetch associated services for the current invoice
$services = [];
$stmt_services = $conn->prepare("SELECT * FROM service WHERE Sid = ?");
$stmt_services->bind_param("i", $invoice_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row;
}
$stmt_services->close();

// Fetch associated files for the current invoice
$files = [];
$stmt_files = $conn->prepare("SELECT * FROM invoice_files WHERE Invoice_id = ?");
$stmt_files->bind_param("i", $invoice_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
while ($row = $result_files->fetch_assoc()) {
    $files[] = $row;
}
$stmt_files->close();


// --- PHP CODE TO FETCH STAMPS AND DEFAULT SIGNATURE (from createinvoice.php logic) ---
$companyStamps = [];
$directorStamps = [];
$currentSignaturePath = 'img/Bhavi-Logo-2.png'; // Default placeholder, will try to get saved one

// If the invoice has a saved stamp image, use it, otherwise a default placeholder
$currentStampPath = !empty($invoice['stamp_image']) ? 'uploads/' . htmlspecialchars($invoice['stamp_image']) : 'img/Bhavi-Logo-2.png';


// Fetch all active stamps and signatures from the 'stamps' table
$sql_stamps = "SELECT file_name, display_name, type FROM `stamps` WHERE is_active = 1";
$result_stamps = $conn->query($sql_stamps);

if ($result_stamps && $result_stamps->num_rows > 0) {
    while ($row = $result_stamps->fetch_assoc()) {
        if ($row['type'] == 'company_stamp') {
            $companyStamps[] = $row;
        } elseif ($row['type'] == 'director_stamp') {
            $directorStamps[] = $row;
        }
    }
}

// Override currentSignaturePath if the invoice has a specific signature saved
if (!empty($invoice['signature_image'])) {
    $currentSignaturePath = 'uploads/' . htmlspecialchars($invoice['signature_image']);
} else {
    // If no signature saved for this invoice, try to get a default active signature
    $sql_default_signature = "SELECT file_name FROM `stamps` WHERE type = 'signature' AND is_active = 1 LIMIT 1";
    $result_default_signature = $conn->query($sql_default_signature);
    if ($result_default_signature->num_rows > 0) {
        $row_signature = $result_default_signature->fetch_assoc();
        $currentSignaturePath = 'uploads/' . htmlspecialchars($row_signature['file_name']);
    }
}

// Fetch all available service names for the dropdown (as per createinvoice.php)
$availableServiceNames = [];
$sql_service_names = "SELECT `service_name` FROM `service_names` ORDER BY service_name ASC";
$res_service_names = $conn->query($sql_service_names);
if ($res_service_names) {
    while ($row = mysqli_fetch_assoc($res_service_names)) {
        $availableServiceNames[] = $row['service_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice - <?php echo htmlspecialchars($invoice['Invoice_no']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css" integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="img/style.css">
    <link rel="stylesheet" href="img/stylemi.css">

</head>
<style>
    /* Styles copied from createinvoice.php */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 20px;
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

    .navbar-nav li:hover .dropdown-content {
        display: block;
    }

    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        padding: 58px 0 0;
        /* Height of navbar */
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
        width: 240px;
        z-index: 600;
    }

    .nav-links {
        background-color: aliceblue;
        border-radius: 20px;
    }

    .active-link {
        background-color: blue;
        color: white;
    }

    body {
        background-color: #f9f9f9;
    }

    form {
        background-color: white;
        border-radius: 50px;
    }

    .form-input {
        border-radius: 20px;
        border: none;
        background-color: aliceblue;
        padding: 5px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    .thead {
        border: 1px solid black;
    }

    th {
        border: none;
        padding: 4px;
        text-align: center;
    }

    .table-responsive {
        border-radius: 10px;
        border: 1px solid black;
    }

    .nav-item {
        padding-top: 20px;
    }

    /* Additional styles for image previews */
    .stamp-signature-preview {
        max-height: 100px;
        max-width: 100%;
        display: block;
        margin-top: 10px;
        border: 1px solid #ddd;
        padding: 5px;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="row">

            <?php include('sidebar.php'); ?>

            <section class="col-lg-10 col-md-12">
                <div class="container col-md-12 ">

                    <form class="mango pb-1 mb-5" action="edit_invoice.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="Sid" value="<?php echo htmlspecialchars($invoice_id); ?>">

                        <img src="img/Bhavi-Logo-2.png" alt="" class="mx-auto d-block img-fluid pt-5" style="max-height: 20%; max-width: 20%;">

                        <div class="row container pt-5 ps-5 mb-5">
                            <div class="col-lg-4 col-sm-12 col-md-12">
                                <h5><strong>Date :</strong> <input type="date" name="invoice_date" id="" value="<?php echo htmlspecialchars($invoice['Invoice_date']); ?>" class="form-input"></h5>
                            </div>

                            <div class="col-lg-4 col-sm-12 mb-3">
                                <h4 class="mb-3">
                                    <select class="" required name="company" id="companySelect">
                                        <?php
                                        echo "<option value=''>Select Customer/Company</option>";
                                        foreach ($all_customers as $customer) {
                                            $selected = ($customer['Id'] == $invoice['Customer_id']) ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($customer['Id']) . "' " . $selected . ">" . htmlspecialchars($customer['Company_name']) . "</option>";
                                        }
                                        // this hidden input is used to store the data & get the data in javascript
                                        echo "<input type='hidden' id='company_data' value='" . htmlspecialchars(json_encode($fetched_customer_data)) . "' />";
                                        ?>
                                    </select>
                                </h4>
                                <p class="mb-1" id="company_name"><?php echo htmlspecialchars($current_customer_details['Company_name'] ?? ''); ?></p>
                                <p class="mb-1" id="name"><?php echo htmlspecialchars($current_customer_details['Name'] ?? ''); ?></p>
                                <p class="mb-1" id="email"><?php echo htmlspecialchars($current_customer_details['Email'] ?? ''); ?></p>
                                <p class="mb-1" id="phone"><?php echo htmlspecialchars($current_customer_details['Phone'] ?? ''); ?></p>
                                <p class="mb-1" id="gst"><?php echo htmlspecialchars($current_customer_details['Gst_no'] ?? ''); ?></p>
                            </div>

                            <div class="col-lg-4 col-sm-12 col-md-12 invoicenumber">
                                <h5><strong>Invoice Number </strong></h5>
                                <h5><strong>BHAVI_KKD_2024_ <input type="text" name="invoice_no" style="border: none;" class="row-1 col-4" value="<?php echo htmlspecialchars($invoice['Invoice_no']); ?>" readonly></strong></h5>
                            </div>
                        </div>

                        <div class="container ps-5 mb-5">
                            <div class="row">
                                <div class="col-lg-8 col-sm-12 mb-3">
                                    <h4 class="pb-2"><strong>Bhavi Creations Pvt Ltd</strong></h4>
                                    <address class="">
                                        <h6>Plot no28, H No70, 17-28, RTO Office Rd, </h6>
                                        <h6>RangaRaoNagar, Kakinada,</h6>
                                        <h6>AndhraPradesh 533003.</h6>
                                    </address>
                                    <textarea style="border: none;" name="" id="" cols="30" rows="3"></textarea> </div>
                                <div class="col-lg-4 col-sm-12 mb-3">
                                    <h4 class="pb-2"><strong>Contact</strong></h4>
                                    <address class="">
                                        <h6>Phone no.: 9642343434</h6>
                                        <h6>Email: <span style="font-size: 16px;"> admin@bhavicreations.com </span>
                                        </h6>
                                        <h6>GSTIN: 37AAKCB6960H1ZB.</h6>
                                    </address>
                                    <textarea style="border: none;" name="" id="" cols="30" rows="3"></textarea> </div>
                            </div>
                        </div>


                        <div class="container mb-3">
                            <div class="row">
                                <div class="col-md-6 text-md-end text-sm-center text-center mb-3 col-12 ">
                                    <h3><b>BILLING</b></h3>
                                </div>
                                <div class="col-lg-1 col-md-3 col-sm-12">
                                    <select class="" name="status" id="status">
                                        <option value="paid" <?php if ($invoice['Status'] == 'paid') echo 'selected'; ?>>Paid</option>
                                        <option value="pending" <?php if ($invoice['Status'] == 'pending') echo 'selected'; ?>>Not paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="billing">
                            <div class="table-responsive">
                                <div style="overflow-x:auto;">
                                    <table border="0" class="table table-bordered">

                                        <thead class="thead" style="background-color: #e9ecef;">
                                            <tr>
                                                <th></th>
                                                <th class="text-center">S.no</th>
                                                <th class="text-center   d-md-table-cell d-lg-table-cell">Services</th>
                                                <th class="text-center  d-md-table-cell d-lg-table-cell">Description</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Price/Unit</th>
                                                <th class="text-center">Sub Total</th>
                                                <th class="text-center">Disc %</th>
                                                <th class="text-center">Disc Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="product_tbody">
                                            <?php if (empty($services)) : // If no existing services, show one empty row ?>
                                                <tr>
                                                    <td><button type="button" class="btn-add-row btn btn-primary"><b>+</b></button></td>
                                                    <td class="serial-number">01</td>
                                                    <td>
                                                        <select style="width:200px;" name="Sname[]" class="service-select">
                                                            <option value="">Select Service</option>
                                                            <?php foreach ($availableServiceNames as $s_name) : ?>
                                                                <option value="<?php echo htmlspecialchars($s_name); ?>">
                                                                    <?php echo htmlspecialchars($s_name); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><textarea style="width: 250px;" rows="1" class="form-control" name="Description[]" placeholder="DESCRIPTION." style="width: 100%;"></textarea></td>
                                                    <td><input type='text' required name='Qty[]' class='form-control qty' value="1"></td>
                                                    <td><input type='text' required name='Price[]' class='form-control price' value="0.00"></td>
                                                    <td><input type='text' readonly name='subtotal[]' class='form-control subtotal' value="0.00"></td>
                                                    <td><input type='text' name='discount[]' class='form-control discount' value="0"></td>
                                                    <td><input type='text' readonly name='total[]' class='form-control total' value="0.00"></td>
                                                    <td><button type='button' value='X' class='btn-sm btn-danger btn-row-remove'><b>X</b></button></td>
                                                </tr>
                                            <?php else : // Populate with existing services ?>
                                                <?php $s_no = 1; ?>
                                                <?php foreach ($services as $service) : ?>
                                                    <tr>
                                                        <td><?php if ($s_no == 1) : ?><button type="button" class="btn-add-row btn btn-primary"><b>+</b></button><?php endif; ?></td>
                                                        <td class="serial-number"><?php echo sprintf('%02d', $s_no++); ?></td>
                                                        <td>
                                                            <select style="width:200px;" name="Sname[]" class="service-select">
                                                                <option value="">Select Service</option>
                                                                <?php foreach ($availableServiceNames as $s_name) : ?>
                                                                    <option value="<?php echo htmlspecialchars($s_name); ?>" <?php if ($s_name == $service['Sname']) echo 'selected'; ?>>
                                                                        <?php echo htmlspecialchars($s_name); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td><textarea style="width: 250px;" rows="1" class="form-control" name="Description[]" placeholder="DESCRIPTION." style="width: 100%;"><?php echo htmlspecialchars($service['Description']); ?></textarea></td>
                                                        <td><input type='text' required name='Qty[]' class='form-control qty' value="<?php echo htmlspecialchars($service['Qty']); ?>"></td>
                                                        <td><input type='text' required name='Price[]' class='form-control price' value="<?php echo htmlspecialchars($service['Price']); ?>"></td>
                                                        <td><input type='text' readonly name='subtotal[]' class='form-control subtotal' value="<?php echo htmlspecialchars($service['Subtotal']); ?>"></td>
                                                        <td><input type='text' name='discount[]' class='form-control discount' value="<?php echo htmlspecialchars($service['Discount_percentage']); ?>"></td>
                                                        <td><input type='text' readonly name='total[]' class='form-control total' value="<?php echo htmlspecialchars($service['Finaltotal']); ?>"></td>
                                                        <td><button type='button' value='X' class='btn-sm btn-danger btn-row-remove'><b>X</b></button></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan='8' class="text-right " style="text-align: right;">Total Before Tax</td>
                                                <td colspan="2"><input type='text' name='grand_total' id='grand_total' class='form-control grand_total' value="<?php echo htmlspecialchars($invoice['Total_before_tax']); ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td colspan='7' class='text-right' style="text-align: right;">GST%</td>
                                                <td>
                                                    <select name="gst" id="gst" class="gst">
                                                        <?php
                                                        // Fetch GST rates (assuming from gst_no table)
                                                        $sql_gst = "SELECT `gst` FROM `gst_no`";
                                                        $result_gst = $conn->query($sql_gst);
                                                        while ($row = mysqli_fetch_assoc($result_gst)) {
                                                            $selected = ($row['gst'] == $invoice['GST_percentage']) ? 'selected' : '';
                                                            echo "<option value='" . htmlspecialchars($row['gst']) . "' " . $selected . ">" . htmlspecialchars($row['gst']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td colspan="2"><input type='text' name='gst_total' id='gst_total' class='form-control gst_total' value="<?php echo htmlspecialchars($invoice['GST_amount']); ?>"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"><input name='words' type='text' class="form-control words" readonly id="words"></td>
                                                <td class="text-center" style="text-align: right;">Total</td>
                                                <td colspan="2"><input type='text' name='Final_total' id='final_total' class='form-control final_total' readonly value="<?php echo htmlspecialchars($invoice['Final_total']); ?>"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right" class='text-right' style="text-align: right;">Advance</td>
                                                <td colspan="2"><input type='text' name='advance' id='advance' class='form-control advance' value="<?php echo htmlspecialchars($invoice['Advance_amount']); ?>"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"><input name='balancewords' type='text' class="form-control balancewords" readonly id="balancewords"></td>
                                                <td class="text-right" class='text-right ' style="text-align: right;">Balance</td>
                                                <td colspan="2"><input type='text' name='balance' id='balance' class='form-control balance' readonly value="<?php echo htmlspecialchars($invoice['Balance_amount']); ?>"></td>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                            </div>


                            <div class="container mt-5">
                                <div class="row">

                                    <div class="col-lg-4 col-md-12 mb-3">
                                        <label for="note" class="form-label"><strong>Note:</strong></label>
                                        <textarea name="note" id="note" class="form-control" style="border-radius: 10px;" rows="1" placeholder="Add a note..."><?php echo htmlspecialchars($invoice['Note']); ?></textarea>
                                    </div>

                                    <div class="col-lg-4 col-md-12 mb-3 d-flex align-items-end">
                                        <div class="w-100 d-flex justify-content-center">
                                            <button type="submit" name="update" class="btn btn-primary me-2">Update</button>
                                            <button type="button" onclick="window.print()" class="btn btn-secondary">Print</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 mb-3">
                                        <label for="attachments" class="form-label"><strong>Attach Files:</strong></label>
                                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                                        <?php if (!empty($files)) : ?>
                                            <p class="mb-1 mt-2">Existing files (check to delete):</p>
                                            <?php foreach ($files as $file) : ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="delete_files[]" value="<?php echo htmlspecialchars($file['id']); ?>">
                                                    <label class="form-check-label">
                                                        <?php echo htmlspecialchars(substr($file['File_path'], strpos($file['File_path'], '-', strpos($file['File_path'], '-') + 1) + 1)); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>


                            <div class="container pt-5 mb-5">
                                <div class="row">
                                    <span class="verticalline mb-5"></span>

                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_details" id="office_details" value="office" <?php if ($invoice['payment_details_type'] == 'office') echo 'checked'; ?>>
                                            <label class="form-check-label" for="office_details">
                                                <strong>With Gst Payment</strong>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_details" id="personal_details" value="personal" <?php if ($invoice['payment_details_type'] == 'personal') echo 'checked'; ?>>
                                            <label class="form-check-label" for="personal_details">
                                                <strong>Use Personal Payment Details</strong>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="office_payment_section" class="col-12 payment-section <?php if ($invoice['payment_details_type'] == 'personal') echo 'd-none'; ?>">
                                        <div class="row mt-2">
                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                <h5 class="mb-3"><strong>Scan to Pay:</strong></h5>
                                                <h4><img src="img/qrcode.jpg" alt="Office QR Code" height="120px" width="120px"></h4>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 col-md-6 invoicenumber only_sm">
                                                <h5 class="mb-2"><strong>Payment details</strong></h5>
                                                <h6 class="mb-2">Bank Name : HDFC Bank, Kakinada</h6>
                                                <h6 class="mb-2">Account Name : Bhavi Creations Private Limited</h6>
                                                <h6 class="mb-2">Account No. : 59213749999999</h6>
                                                <h6 class="mb-2">IFSC : HDFC0000426</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="personal_payment_section" class="col-12 payment-section <?php if ($invoice['payment_details_type'] == 'office') echo 'd-none'; ?>">
                                        <div class="row mt-2">
                                            <div class="col-lg-6 col-sm-12 col-md-6">
                                                <h5 class="mb-3"><strong>Scan to Pay:</strong></h5>
                                                <h4><img src="img/personal_qrcode.jpg" alt="Personal QR Code" height="120px" width="120px"></h4>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 col-md-6 invoicenumber only_sm">
                                                <h5 class="mb-2"><strong>Payment details</strong></h5>
                                                <h6 class="mb-2">Bank Name : State Bank Of India</h6>
                                                <h6 class="mb-2">Account Name : Chalikonda Naga Phaneendra Naidu</h6>
                                                <h6 class="mb-2">Account No. : 20256178992</h6>
                                                <h6 class="mb-2">IFSC : SBIN00001917</h6>
                                                <h6 class="mb-2">Google Pay, Phone Pay, Paytm: 8686394079</h6>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>



                        <div class="row justify-content-end me-5 mb-3">
                            <div class="col-auto" style="width: 200px;">
                                <label for="stamp_select" class="form-label"><strong>Select Stamp:</strong></label>
                                <select name="stamp_select" id="stamp_select" class="form-control">
                                    <option value="">No Stamp</option>
                                    <optgroup label="Company Stamps">
                                        <?php foreach ($companyStamps as $stamp) : ?>
                                            <option value="uploads/<?php echo htmlspecialchars($stamp['file_name']); ?>" <?php if ('uploads/' . $invoice['stamp_image'] == 'uploads/' . $stamp['file_name']) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($stamp['display_name']); ?> (Company)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="Director Stamps">
                                        <?php foreach ($directorStamps as $stamp) : ?>
                                            <option value="uploads/<?php echo htmlspecialchars($stamp['file_name']); ?>" <?php if ('uploads/' . $invoice['stamp_image'] == 'uploads/' . $stamp['file_name']) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($stamp['display_name']); ?> (Director)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                                <input type="hidden" name="stamp_image_path" id="stamp_image_path_input" value="<?php echo htmlspecialchars($invoice['stamp_image']); ?>">
                            </div>
                        </div>


                        <div class="row justify-content-end me-5">
                            <div class="col-auto text-center d-flex flex-column align-items-center me-5">
                                <img id="dynamicStamp" src="<?php echo htmlspecialchars($currentStampPath); ?>" alt="Stamp" class="img-fluid mb-2" style="max-height:200px; max-width: 200px;">
                                <img id="dynamicSignature" src="<?php echo htmlspecialchars($currentSignaturePath); ?>" alt="Signature" class="img-fluid" style="max-height: 100px; max-width: 100px;">
                                <input type="hidden" name="signature_image_path" id="signature_image_path_input" value="<?php echo htmlspecialchars($invoice['signature_image']); ?>">
                                <p class="mt-2">Signature</p>
                            </div>
                        </div>


                    </form>
                </div>


                <div class="container text-center mt-4 ">
                    <div class="row">
                        <div class="col-7">
                            <div class="modal" tabindex="-1" id="modal_frm">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Customer Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="modalform.php" method="post">
                                                <div class="form-group">
                                                    <label for="">Company Name</label>
                                                    <input type="text" name="company_name" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Name</label>
                                                    <input type="text" name="cname" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Address</label>
                                                    <input type="text" name="caddress" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Phone</label>
                                                    <input type="tel" name="cphone" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="email" name="cemail" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">GST_No</label>
                                                    <input type="text" name="cgst" id="gstInput" class="form-control">
                                                </div>
                                                <input type="submit" name="submit" id="submit" class="btn btn-success mt-5">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js" integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(function() {
            // Initialize selectize for all select elements, including dynamically added ones
            function initSelectize(selector) {
                $(selector).selectize({
                    sortField: 'text' // Sort options alphabetically
                });
            }

            // Function to populate customer details based on selected ID
            function populateCustomerDetails() {
                var selectedCompanyId = $('#companySelect').val();
                var companyData = JSON.parse($('#company_data').val());

                // Clear previous customer details
                $('#company_name').html('');
                $('#name').html('');
                $('#email').html('');
                $('#phone').html('');
                $('#gst').html('');

                companyData.forEach(element => {
                    // Use loose equality (==) for comparison as element.Id might be string/number and selectedCompanyId is string
                    if (element.Id == selectedCompanyId) {
                        $('#company_name').html(element.Company_name);
                        $('#name').html(element.Name);
                        $('#email').html(element.Email);
                        $('#phone').html(element.Phone);
                        $('#gst').html(element.Gst_no);
                        return; // Exit loop once found
                    }
                });
            }

            // Initialize selectize for customer select
            var $companySelect = $('#companySelect').selectize({
                sortField: 'text',
                onChange: populateCustomerDetails // Call populate on change
            });

            // After selectize is initialized, manually trigger population for initial display
            // This is more robust than .trigger('change') directly on the original select
            if ($companySelect[0].selectize) {
                // Trigger change to populate details based on the pre-selected option on load
                $companySelect[0].selectize.trigger('change');
            }


            // Function to update the stamp image preview and hidden input
            function updateStampImage() {
                const selectElement = document.getElementById('stamp_select');
                const imgElement = document.getElementById('dynamicStamp');
                const hiddenInput = document.getElementById('stamp_image_path_input');

                // Get the value from the selectize instance
                const selectedValue = $(selectElement).val();

                if (selectedValue) {
                    imgElement.src = selectedValue; // Set image source to selected file path
                    hiddenInput.value = selectedValue.replace('uploads/', ''); // Store only filename in hidden input
                    imgElement.style.display = 'block'; // Show the image
                } else {
                    imgElement.src = 'img/Bhavi-Logo-2.png'; // Set to default or empty if "No Stamp"
                    hiddenInput.value = ''; // Clear hidden input
                }
            }

            // Event listener for stamp dropdown
            $('#stamp_select').change(function() {
                updateStampImage();
            });

            // Initial call to set default stamp image/path if any options are pre-selected
            updateStampImage();


            // Function to update serial numbers
            function updateSerialNumbers() {
                $(".serial-number").each(function(index) {
                    $(this).text((index + 1).toString().padStart(2, '0'));
                });
            }

            // Add new row button click event
            $(document).on("click", ".btn-add-row", function() {
                var newRowHtml = `
                    <tr>
                        <td><button type="button" class="btn-add-row btn btn-primary"><b>+</b></button></td>
                        <td class="serial-number"></td>
                        <td>
                            <select style="width:200px;" name="Sname[]" class="service-select">
                                <option value="">Select Service</option>
                                <?php foreach ($availableServiceNames as $s_name) : ?>
                                    <option value="<?php echo htmlspecialchars($s_name); ?>">
                                        <?php echo htmlspecialchars($s_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><textarea style="width: 250px;" rows="1" class="form-control" name="Description[]" placeholder="DESCRIPTION." style="width: 100%;"></textarea></td>
                        <td><input type='text' required name='Qty[]' class='form-control qty' value="1"></td>
                        <td><input type='text' required name='Price[]' class='form-control price' value="0.00"></td>
                        <td><input type='text' readonly name='subtotal[]' class='form-control subtotal' value="0.00"></td>
                        <td><input type='text' name='discount[]' class='form-control discount' value="0"></td>
                        <td><input type='text' readonly name='total[]' class='form-control total' value="0.00"></td>
                        <td><button type='button' value='X' class='btn-sm btn-danger btn-row-remove'><b>X</b></button></td>
                    </tr>
                `;
                var $newRow = $(newRowHtml);
                $("#product_tbody").append($newRow);

                // Re-initialize selectize for the newly added service select
                initSelectize($newRow.find('.service-select'));

                updateSerialNumbers();
                calculateTotals(); // Recalculate totals after adding a row
            });

            // Remove row button click event
            $("body").on("click", ".btn-row-remove", function() {
                if (confirm("Are You Sure?")) {
                    $(this).closest("tr").remove();
                    updateSerialNumbers();
                    calculateTotals(); // Recalculate totals after removing a row
                }
            });


            /*----Ending balance calculation--*/
            $("body").on("input", ".price, .qty, .discount, #advance, .gst", function() {
                var $row = $(this).closest("tr");

                var price = Number($row.find(".price").val());
                var qty = Number($row.find(".qty").val());
                var subtotal = price * qty;
                $row.find(".subtotal").val(subtotal.toFixed(2)); // Ensure 2 decimal places

                var discount = Number($row.find(".discount").val());
                // Calculate 'total' for the row, which is (Subtotal - Discount Amount)
                var total = subtotal - (subtotal * (discount / 100));
                $row.find(".total").val(total.toFixed(2)); // Assign to .total (Disc Total column)

                calculateTotals();
            });

            // Recalculate all totals on page load to ensure accuracy
            calculateTotals();

            // Handle payment details radio buttons
            document.addEventListener('DOMContentLoaded', function() {
                const officeRadio = document.getElementById('office_details');
                const personalRadio = document.getElementById('personal_details');
                const officeSection = document.getElementById('office_payment_section');
                const personalSection = document.getElementById('personal_payment_section');

                function togglePaymentDetails() {
                    if (officeRadio.checked) {
                        officeSection.classList.remove('d-none');
                        personalSection.classList.add('d-none');
                    } else {
                        personalSection.classList.remove('d-none');
                        officeSection.classList.add('d-none');
                    }
                }

                // Initial check on page load
                togglePaymentDetails();

                // Listen to change events
                officeRadio.addEventListener('change', togglePaymentDetails);
                personalRadio.addEventListener('change', togglePaymentDetails);
            });


            // Customer modal functionality (copied from createinvoice.php)
            document.addEventListener('DOMContentLoaded', function() {
                var addCustomerModal = new bootstrap.Modal(document.getElementById('modal_frm'));
                // The add_customer button doesn't exist in this edit.php, so this part won't fire.
                // It's here because it was in the createinvoice.php template.
                var addCustomerButton = document.getElementById('add_customer');
                if (addCustomerButton) {
                    addCustomerButton.addEventListener('click', function() {
                        addCustomerModal.show();
                    });
                }
                document.getElementById('gstInput').addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            });

        });

        // Global functions for calculations
        function grand_total() {
            var tot = 0;
            $(".total").each(function() {
                tot += Number($(this).val());
            });
            $("#grand_total").val(tot.toFixed(2));
        }

        function gst_total() {
            var grand_total_val = Number($("#grand_total").val());
            var gst_rate = Number($(".gst").val());
            var gst_amount = (grand_total_val * gst_rate) / 100;
            $("#gst_total").val(gst_amount.toFixed(2));
        }

        function final_total() {
            var grand_total_val = Number($("#grand_total").val());
            var gst_amount_val = Number($("#gst_total").val());
            var final_total_val = grand_total_val + gst_amount_val;
            $("#final_total").val(final_total_val.toFixed(2));

            var words = amountToWords(final_total_val);
            $("#words").val(words);
        }

        function updateBalance() {
            var finalTotal = Number($("#final_total").val());
            var advance = Number($("#advance").val());
            var balance = finalTotal - advance;
            $("#balance").val(balance.toFixed(2));
            updateBalanceWords();
        }

        function updateBalanceWords() {
            var balance = Number($("#balance").val());
            var balanceWords = amountToWords(balance);
            $("#balancewords").val(balanceWords);
        }

        function calculateTotals() {
            grand_total();
            gst_total();
            final_total();
            updateBalance(); // Call updateBalance to ensure balance is also calculated
        }

        function amountToWords(num) {
            var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
            var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

            var parts = num.toString().split('.');
            var wholePart = parts[0];
            var decimalPart = parts[1] || 0; // If no decimal part, default to 0

            if (wholePart.length > 9) return 'overflow';

            var n = ('000000000' + wholePart).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);

            if (!n) return '';

            var str = '';
            str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
            str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
            str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
            str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'rupees ' : '';

            var paisaWords = amountToWordsDecimal(decimalPart);
            if (paisaWords) {
                str += 'and ' + paisaWords;
            }

            str += 'only ';

            return str;
        }

        function amountToWordsDecimal(decimalPart) {
            var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine '];
            var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

            var n = ('00' + decimalPart).substr(-2).match(/^(\d{1})(\d{1})$/);

            if (!n) return '';

            var str = '';

            // Handle numbers 1-19
            if (Number(n[0]) > 0 && Number(n[0]) < 20) {
                str += a[Number(n[0])];
            } else {
                str += (n[1] != 0 ? b[n[1]] : '');
                str += (n[2] != 0 ? ' ' + a[n[2]] : '');
            }

            str = str.trim(); // Remove leading/trailing space

            str += (str !== '') ? ' paisa ' : '';

            return str;
        }
    </script>

</body>

</html>