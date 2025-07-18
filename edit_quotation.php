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
if ($stmt_quote === false) {
    die("Error preparing statement for quotation: " . $conn->error);
}
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
if ($stmt_services === false) {
    die("Error preparing statement for services: " . $conn->error);
}
$stmt_services->bind_param("i", $quote_id);
$stmt_services->execute();
$result_services = $stmt_services->get_result();
while ($row = $result_services->fetch_assoc()) {
    $services[] = $row;
}
$stmt_services->close();

// --- Fetch associated files for display (optional, if you want to show existing files) ---
$files = [];
$stmt_files = $conn->prepare("SELECT * FROM quote_files WHERE quote_id = ?");
if ($stmt_files === false) {
    die("Error preparing statement for files: " . $conn->error);
}
$stmt_files->bind_param("i", $quote_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
while ($row = $result_files->fetch_assoc()) {
    $files[] = $row;
}
$stmt_files->close();


// --- PHP CODE TO FETCH ALL STAMPS AND DEFAULT SIGNATURES FOR DROPDOWNS ---
$companyStamps = [];
$directorStamps = [];
$defaultSignaturePath = 'img/Bhavi-Logo-2.png'; // Fallback if no signature found in DB

$sql_stamps = "SELECT file_name, display_name, type FROM `stamps` WHERE is_active = 1";
$result_stamps = $conn->query($sql_stamps);

if ($result_stamps->num_rows > 0) {
    while ($row = $result_stamps->fetch_assoc()) {
        if ($row['type'] == 'company_stamp') {
            $companyStamps[] = $row;
        } elseif ($row['type'] == 'director_stamp') {
            $directorStamps[] = $row;
        }
    }
}

// Override default signature path if a signature is already selected in the quote
if (!empty($quote['selected_signature_filename'])) {
    $defaultSignaturePath = 'uploads/' . htmlspecialchars($quote['selected_signature_filename']);
} else {
    // If no signature is selected for this quote, try to pick one from active stamps as a default option
    $sql_default_signature_option = "SELECT file_name FROM `stamps` WHERE type = 'signature' AND is_active = 1 LIMIT 1";
    $result_default_signature_option = $conn->query($sql_default_signature_option);
    if ($result_default_signature_option && $result_default_signature_option->num_rows > 0) {
        $row_signature_option = $result_default_signature_option->fetch_assoc();
        $defaultSignaturePath = 'uploads/' . htmlspecialchars($row_signature_option['file_name']);
    }
}

// Fetch all customers for the dropdown
$all_customers = [];
$sql_customers = "SELECT * FROM `customer`";
$res_customers = $conn->query($sql_customers);
while ($row_cust = mysqli_fetch_assoc($res_customers)) {
    $all_customers[] = $row_cust;
}

// Fetch GST percentages for dropdown
$gst_options = [];
$sql_gst = "SELECT `gst` FROM `gst_no`";
$res_gst = $conn->query($sql_gst);
while ($row_gst = mysqli_fetch_assoc($res_gst)) {
    $gst_options[] = $row_gst['gst'];
}



// --- Populate current_customer_details directly from the $quote array ---
// These values are used to pre-fill the <p> tags
$current_customer_details = [
    'Company_name' => $quote['Company_name'] ?? '',
    'Name' => $quote['Cname'] ?? '',
    'Email' => $quote['Cmail'] ?? '',
    'Phone' => $quote['Cphone'] ?? '',
    'Address' => $quote['Caddress'] ?? '',
    'Gst_no' => $quote['Cgst'] ?? ''
];

// --- Fetch all customers for the dropdown ---
$all_customers = [];
$stmt_all_customers = $conn->prepare("SELECT Id, Company_name, Name, Phone, Address, Email, Gst_no FROM `customer`");
if ($stmt_all_customers) {
    $stmt_all_customers->execute();
    $result_all_customers = $stmt_all_customers->get_result();
    while ($row = $result_all_customers->fetch_assoc()) {
        $all_customers[] = $row;
    }
    $stmt_all_customers->close();
} else {
    error_log("Error preparing all customers statement: " . $conn->error);
}

// --- Determine selected customer ID for the dropdown (based on Company_name) ---
$selected_company_id_for_dropdown = null;
if (isset($quote['Company_name'])) {
    $quote_company_name_normalized = strtolower(trim($quote['Company_name']));
    foreach ($all_customers as $customer_row) {
        $customer_company_name_normalized = strtolower(trim($customer_row['Company_name'] ?? ''));
        if ($customer_company_name_normalized == $quote_company_name_normalized) {
            $selected_company_id_for_dropdown = $customer_row['Id'];
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Quotation - BHAVIINVOICE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css" integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="img/style.css">
    <link rel="stylesheet" href="img/stylemi.css">

    <style>
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

      
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>

            <section class="col-lg-10">
                <div class="container">
                    <form class="quote-form shadow-sm pb-1" action="edit_quotation_form.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="quote_id" value="<?php echo htmlspecialchars($quote_id); ?>">
                        <img src="img/Bhavi-Logo-2.png" alt="" class="mx-auto d-block pt-5" height="20%" width="20%">

                        <div class="row container pt-5 mb-5">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <h5><strong>Date :</strong> <input type="date" name="quote_date" id="" value="<?php echo htmlspecialchars($quote['quotation_date']); ?>" class="form-input"></h5>
                                </div>

                           




                                <div class="col-lg-4 col-sm-12 mb-3">

                                    <select name="company" id="companySelect" class="form-select mb-2">
                                        <option value=''>Select Customer/Company</option>
                                        <?php
                                        foreach ($all_customers as $customer_row) {
                                            $selected = ($customer_row['Id'] == $selected_company_id_for_dropdown) ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($customer_row['Id']) . "' " . $selected . ">" . htmlspecialchars($customer_row['Company_name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type='hidden' id='company_data' value='<?php echo json_encode($all_customers); ?>' />
                                    <h5 class="mt-3">Customer Details:</h5>
                                    <p class="mb-1"><strong>Company Name:</strong> <span id="company_name"><?php echo htmlspecialchars($current_customer_details['Company_name'] ?? ''); ?></span></p>
                                    <p class="mb-1"><strong>Contact Person:</strong> <span id="name"><?php echo htmlspecialchars($current_customer_details['Name'] ?? ''); ?></span></p>
                                    <p class="mb-1"><strong>Email:</strong> <span id="email"><?php echo htmlspecialchars($current_customer_details['Email'] ?? ''); ?></span></p>
                                    <p class="mb-1"><strong>Phone:</strong> <span id="phone"><?php echo htmlspecialchars($current_customer_details['Phone'] ?? ''); ?></span></p>
                                    <p class="mb-1"><strong>GST No:</strong> <span id="gst"><?php echo htmlspecialchars($current_customer_details['Gst_no'] ?? ''); ?></span></p>
                                    <p class="mb-1"><strong>Address:</strong> <span id="address"><?php echo htmlspecialchars($current_customer_details['Address'] ?? ''); ?></span></p>
                                </div>


                                <div class="col-lg-4 col-sm-12 col-md-12 invoicenumber">
                                    <h5><strong>Quotation Number </strong></h5>
                                    <h5><strong>BHAVI_KKD_2024_ <input type="text" name="quotation_no" style="border: none;" class="row-1 col-4" value="<?php echo htmlspecialchars($quote['quotation_no']); ?>" readonly></strong></h5>
                                </div>
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
                                    <textarea style="border: none;" name="" id="" cols="30" rows="3"></textarea>
                                </div>
                                <div class="col-lg-4 col-sm-12 mb-3">
                                    <h4 class="pb-2"><strong>Contact</strong></h4>
                                    <address class="">
                                        <h6>Phone no.: 9642343434</h6>
                                        <h6>Email: <span style="font-size: 16px;"> admin@bhavicreations.com </span>
                                        </h6>
                                        <h6>GSTIN: 37AAKCB6960H1ZB.</h6>
                                    </address>
                                    <textarea style="border: none;" name="" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-center mb-5"><B>Price Quotation</B></h3>

                        <div class="billing">
                            <div class="table-responsive">
                                <div style="overflow-x:auto;">
                                    <table border="0">
                                        <thead class="thead">
                                            <tr>
                                                <th></th>
                                                <th class="text-center">S.no</th>
                                                <th class="text-center">Services</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Price/Unit</th>
                                                <th class="text-center">Sub Total</th>
                                                <th class="text-center">Disc %</th>
                                                <th class="text-center">Disc Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="product_tbody">
                                            <?php if (empty($services)) : ?>
                                                <tr>
                                                    <td><button style="border: none; background: none;" type="button" id="btn-add-row" class="btn-add-row"><b>+</b></button></td>
                                                    <td class="serial-number">01</td>
                                                    <td>
                                                        <select style="width:150px;" name="Sname[]" class="form-control">
                                                            <?php
                                                            $sql_services_options = "SELECT `service_name` FROM `service_names`";
                                                            $res_services_options = $conn->query($sql_services_options);
                                                            while ($row_service_option = mysqli_fetch_assoc($res_services_options)) {
                                                                echo "<option value='" . htmlspecialchars($row_service_option['service_name']) . "'>" . htmlspecialchars($row_service_option['service_name']) . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td><textarea style="width:150px;" rows="1" class="form-control" name="Description[]" placeholder="DESCRIPTION."></textarea></td>
                                                    <td><input type='text' required name='Qty[]' class='form-control qty' value="0"></td>
                                                    <td><input type='text' required name='Price[]' class='form-control price' value="0.00"></td>
                                                    <td><input type='text' name='subtotal[]' class='form-control subtotal' value="0.00"></td>
                                                    <td><input type='text' name='discount[]' class='form-control discount' value="0"></td>
                                                    <td><input type='text' required name='total[]' class='form-control total' value="0.00"></td>
                                                    <td><button type='button' value='X' style="border: none; background: none;" class='btn-sm' id='btn-row-remove'><b>X</b></button></td>
                                                </tr>
                                            <?php else : ?>
                                                <?php $s_counter = 1; ?>
                                                <?php foreach ($services as $service) : ?>
                                                    <tr>
                                                        <td><?php if ($s_counter == 1) : ?><button style="border: none; background: none;" type="button" id="btn-add-row" class="btn-add-row"><b>+</b></button><?php endif; ?></td>
                                                        <td class="serial-number"><?php echo sprintf('%02d', $s_counter++); ?></td>
                                                        <td>
                                                            <select style="width:150px;" name="Sname[]" class="form-control">
                                                                <?php
                                                                $sql_services_options = "SELECT `service_name` FROM `service_names`";
                                                                $res_services_options = $conn->query($sql_services_options);
                                                                while ($row_service_option = mysqli_fetch_assoc($res_services_options)) {
                                                                    $selected = ($row_service_option['service_name'] == $service['Sname']) ? 'selected' : '';
                                                                    echo "<option value='" . htmlspecialchars($row_service_option['service_name']) . "' " . $selected . ">" . htmlspecialchars($row_service_option['service_name']) . "</option>";
                                                                }
                                                                // Reset result pointer if you intend to loop again or if you have only one connection
                                                                if (isset($res_services_options)) {
                                                                    $res_services_options->data_seek(0);
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td><textarea style="width:150px;" rows="1" class="form-control" name="Description[]" placeholder="DESCRIPTION."><?php echo htmlspecialchars($service['Description']); ?></textarea></td>
                                                        <td><input type='text' required name='Qty[]' class='form-control qty' value="<?php echo htmlspecialchars($service['Qty']); ?>"></td>
                                                        <td><input type='text' required name='Price[]' class='form-control price' value="<?php echo htmlspecialchars(number_format((float)$service['Price'], 2, '.', '')); ?>"></td>
                                                        <td><input type='text' name='subtotal[]' class='form-control subtotal' value="<?php echo htmlspecialchars(number_format((float)$service['Subtotal'], 2, '.', '')); ?>"></td>
                                                        <td><input type='text' name='discount[]' class='form-control discount' value="<?php echo htmlspecialchars($service['Discount_percentage']); ?>"></td>
                                                        <td><input type='text' required name='total[]' class='form-control total' value="<?php echo htmlspecialchars(number_format((float)$service['Finaltotal'], 2, '.', '')); ?>"></td>
                                                        <td><button type='button' value='X' style="border: none; background: none;" class='btn-sm' id='btn-row-remove'><b>X</b></button></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan='8' class='text-right' style="text-align: right;">Total Before Tax</td>
                                                <td colspan="2"><input type='text' name='grand_total' id='grand_total' class='form-control grand_total' value="<?php echo htmlspecialchars(number_format((float)$quote['Final'], 2, '.', '')); ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td colspan='7' class='text-right' style="text-align: right;">GST%</td>
                                                <td>
                                                    <select name="gst" id="gst" class="gst">
                                                        <?php
                                                        foreach ($gst_options as $gst_val) {
                                                            $selected = ($gst_val == $quote['Gst']) ? 'selected' : '';
                                                            echo "<option value='" . htmlspecialchars($gst_val) . "' " . $selected . ">" . htmlspecialchars($gst_val) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td colspan="2"><input type='text' name='gst_total' id='gst_total' class='form-control gst_total' value="<?php echo htmlspecialchars(number_format((float)$quote['Gst_total'], 2, '.', '')); ?>"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"><input name='words' type='text' class="form-control words" readonly id="words" value="<?php // Value will be set by JS on load 
                                                                                                                                                        ?>"></td>
                                                <td class="text-center" class='text-right' style="text-align: right;">Total</td>
                                                <td colspan="2"><input type='text' name='Final_total' id='final_total' class='form-control final_total' value="<?php echo htmlspecialchars(number_format((float)$quote['Grandtotal'], 2, '.', '')); ?>" readonly></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right" style="text-align: right;">Advance</td>
                                                <td colspan="2"><input type='text' name='advance' id='advance' class='form-control advance' value="<?php echo htmlspecialchars(number_format((float)$quote['Advance'], 2, '.', '')); ?>"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"><input name='balancewords' type='text' class="form-control balancewords" readonly id="balancewords" value="<?php // Value will be set by JS on load 
                                                                                                                                                                            ?>"></td>
                                                <td class="text-right" style="text-align: right;">Balance</td>
                                                <td colspan="2"><input type='text' name='balance' id='balance' class='form-control balance' value="<?php echo htmlspecialchars(number_format((float)$quote['Balance'], 2, '.', '')); ?>" readonly></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="container mt-5">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 mb-3">
                                        <label for="note" class="form-label"><strong>Note:</strong></label>
                                        <textarea name="note" class="form-control" style="border-radius: 10px;" rows="1" placeholder="Note:"><?php echo htmlspecialchars($quote['Note']); ?></textarea>
                                    </div>

                                    <div class="col-lg-4 col-md-12 mb-3 d-flex align-items-end">
                                        <div class="w-100 d-flex justify-content-center">
                                            <button type="submit" name="save" class="btn btn-success me-2">Update Quotation</button>
                                            <button type="button" onclick="window.print()" class="btn btn-secondary">Print</button>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-12 mb-3">
                                        <label for="attachments" class="form-label"><strong>Attach Files:</strong></label>
                                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                                        <?php if (!empty($files)) : ?>
                                            <small class="text-muted mt-2">Existing Files:
                                                <?php foreach ($files as $file) : ?>
                                                    <span class="badge bg-info text-dark me-1"><?php echo htmlspecialchars(basename($file['file_path'])); ?></span>
                                                <?php endforeach; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="container pt-5 mb-5">
                                <div class="row">
                                    <span class="verticalline mb-5"></span>

                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_details" id="office_details" value="office" <?php echo ($quote['payment_details_type'] == 'office' ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="office_details">
                                                <strong>With Gst Payment</strong>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_details" id="personal_details" value="personal" <?php echo ($quote['payment_details_type'] == 'personal' ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="personal_details">
                                                <strong>Use Personal Payment Details</strong>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="office_payment_section" class="col-12 payment-section <?php echo ($quote['payment_details_type'] == 'personal' ? 'd-none' : ''); ?>">
                                        <div class="row  ">
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

                                    <div id="personal_payment_section" class="col-12 payment-section <?php echo ($quote['payment_details_type'] == 'office' ? 'd-none' : ''); ?>">
                                        <div class="row  ">
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
                                                <h6 class="mb-2">Google pay , Phone pay, Paytm : 8686394079</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="row justify-content-end me-5 mb-3">
                            <div class="col-auto" style="width: 200px;">
                                <label for="stamp_select" class="form-label"><strong>Select Stamp:</strong></label>
                                <select name="stamp_select" id="stamp_select" class="form-control">
                                    <option value="">No Stamp</option>
                                    <optgroup label="Company Stamps">
                                        <?php foreach ($companyStamps as $stamp) :
                                            $selected = ($stamp['file_name'] == $quote['selected_stamp_filename']) ? 'selected' : ''; ?>
                                            <option value="uploads/<?php echo htmlspecialchars($stamp['file_name']); ?>" <?php echo $selected; ?>>
                                                <?php echo htmlspecialchars($stamp['display_name']); ?> (Company)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="Director Stamps">
                                        <?php foreach ($directorStamps as $stamp) :
                                            $selected = ($stamp['file_name'] == $quote['selected_stamp_filename']) ? 'selected' : ''; ?>
                                            <option value="uploads/<?php echo htmlspecialchars($stamp['file_name']); ?>" <?php echo $selected; ?>>
                                                <?php echo htmlspecialchars($stamp['display_name']); ?> (Director)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                                <input type="hidden" name="stamp_image_path" id="stamp_image_path_input" value="<?php echo !empty($quote['selected_stamp_filename']) ? 'uploads/' . htmlspecialchars($quote['selected_stamp_filename']) : ''; ?>">
                            </div>
                        </div> -->

                        <div class="row justify-content-end me-5">
                            <div class="col-auto text-center d-flex flex-column align-items-center me-5">
                                <img id="dynamicStamp" src="<?php echo !empty($quote['selected_stamp_filename']) ? 'uploads/' . htmlspecialchars($quote['selected_stamp_filename']) : 'img/Bhavi-Logo-2.png'; ?>" alt="Stamp" class="img-fluid mb-2" style="max-height:200px; max-width: 200px;">

                                <img id="dynamicSignature" src="<?php echo htmlspecialchars($defaultSignaturePath); ?>" alt="Signature" class="img-fluid" style="max-height: 100px; max-width: 100px;">
                                <input type="hidden" name="selected_signature_filename" id="signature_image_path_input" value="<?php echo htmlspecialchars($defaultSignaturePath); ?>">

                                <p class="mt-2">Signature</p>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

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
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js" integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            // Initialize selectize for all select elements
            $("select").selectize();

            // Function to update customer details based on selected company
            const customerData = <?php echo json_encode($all_customers); ?>;
            const companySelect = document.getElementById('companySelect');
            const companyNameSpan = document.getElementById('company_name');
            const nameSpan = document.getElementById('name');
            const emailSpan = document.getElementById('email');
            const phoneSpan = document.getElementById('phone');
            const gstSpan = document.getElementById('gst');
            const addressSpan = document.getElementById('address');


            function updateCustomerDetails() {
                const selectedCompanyId = companySelect.value;
                const selectedCustomer = customerData.find(customer => customer.Id == selectedCompanyId);

                if (selectedCustomer) {
                    companyNameSpan.textContent = selectedCustomer.Company_name || '';
                    nameSpan.textContent = selectedCustomer.Name || '';
                    emailSpan.textContent = selectedCustomer.Email || '';
                    phoneSpan.textContent = selectedCustomer.Phone || '';
                    gstSpan.textContent = selectedCustomer.Gst_no || '';
                    addressSpan.textContent = selectedCustomer.Address || '';
                } else {
                    // Clear fields if no customer is selected or found
                    companyNameSpan.textContent = '';
                    nameSpan.textContent = '';
                    emailSpan.textContent = '';
                    phoneSpan.textContent = '';
                    gstSpan.textContent = '';
                    addressSpan.textContent = '';
                }
            }

            // Update on initial load based on pre-selected value
            updateCustomerDetails();

            // Update when selection changes
            companySelect.addEventListener('change', updateCustomerDetails);


            const officeRadio = document.getElementById('office_details');
            const personalRadio = document.getElementById('personal_details');
            const officeSection = document.getElementById('office_payment_section');
            const personalSection = document.getElementById('personal_payment_section');

            function togglePaymentDetailsDisplay() {
                if (officeRadio.checked) {
                    officeSection.classList.remove('d-none');
                    personalSection.classList.add('d-none');
                } else if (personalRadio.checked) {
                    personalSection.classList.remove('d-none');
                    officeSection.classList.add('d-none');
                }
            }

            // Initial check on page load
            togglePaymentDetailsDisplay();

            // Listen to change events
            officeRadio.addEventListener('change', togglePaymentDetailsDisplay);
            personalRadio.addEventListener('change', togglePaymentDetailsDisplay);


            // Function to update stamp image and hidden input
            function updateStampImage() {
                const selectElement = document.getElementById('stamp_select');
                const imgElement = document.getElementById('dynamicStamp');
                const hiddenInput = document.getElementById('stamp_image_path_input');

                if (selectElement.value) {
                    imgElement.src = selectElement.value; // Set image source to selected file path
                    hiddenInput.value = selectElement.value; // Store the path in hidden input
                    imgElement.style.display = 'block'; // Show the image
                } else {
                    imgElement.src = 'img/Bhavi-Logo-2.png'; // Set to default if "No Stamp" or empty
                    hiddenInput.value = ''; // Clear hidden input
                }
            }

            // Event listener for stamp dropdown
            $('#stamp_select').change(function() {
                updateStampImage();
            });

            // Initial call to set default stamp image/path if any options are pre-selected
            updateStampImage();


            // Function to update signature image and hidden input
            function updateSignatureImage() {
                const imgElement = document.getElementById('dynamicSignature');
                const hiddenInput = document.getElementById('signature_image_path_input');
                // The signature image path is primarily set by PHP initially.
                // We just need to ensure the hidden input always reflects the current image source.
                hiddenInput.value = imgElement.src;
            }

            // Call on document ready to ensure initial signature path is captured
            updateSignatureImage();


            $("#btn-add-row").click(function() {
                // Ensure service options are loaded dynamically or passed from PHP
                var serviceOptions = "";
                <?php
                $sql_services_options = "SELECT `service_name` FROM `service_names`";
                $res_services_options = $conn->query($sql_services_options);
                while ($row_service_option = mysqli_fetch_assoc($res_services_options)) {
                    echo "serviceOptions += \"<option value='" . htmlspecialchars($row_service_option['service_name']) . "'>" . htmlspecialchars($row_service_option['service_name']) . "</option>\";\n";
                }
                ?>
                var row = "<tr><td></td> <td class='serial-number'></td><td><select style='width:150px;' name='Sname[]' class='form-control'>" + serviceOptions + "</select></td><td><textarea class='form-control' name='Description[]' placeholder='DESCRIPTION.' style='width: 100%;'></textarea></td><td><input type='text' required name='Qty[]' class='form-control qty' value='0'></td><td><input type='text' required name='Price[]' class='form-control price' value='0.00'></td><td><input type='text' required name='subtotal[]' class='form-control subtotal' value='0.00'></td><td><input type='text' name='discount[]' class='form-control discount' value='0'></td><td><input type='text' required name='total[]' class='form-control total' value='0.00'></td><td><button type='button' value='X' style='border: none; background: none;' class='btn-sm' id='btn-row-remove'><b>X</b></button></td></tr>";

                $("#product_tbody").append(row);

                // Re-initialize selectize for new row
                $("#product_tbody tr:last-child select").selectize();

                // Update serial numbers
                updateSerialNumbers();
            });

            // Function to update serial numbers
            function updateSerialNumbers() {
                $(".serial-number").each(function(index) {
                    $(this).text((index + 1).toString().padStart(2, '0'));
                });
            }
            updateSerialNumbers(); // Call initially for pre-filled rows

            $("body").on("click", "#btn-row-remove", function() {
                if (confirm("Are You Sure?")) {
                    $(this).closest("tr").remove();
                    updateSerialNumbers();
                    calculateTotals(); // Re-calculate totals after row removal
                }
            });

            $("body").on("input", ".price, .qty, .discount, #advance", function() {
                var $row = $(this).closest("tr");

                var price = Number($row.find(".price").val());
                var qty = Number($row.find(".qty").val());
                var subtotal = price * qty;
                $row.find(".subtotal").val(subtotal.toFixed(2));

                var discount = Number($row.find(".discount").val());
                var total = subtotal - (subtotal * (discount / 100));
                $row.find(".total").val(total.toFixed(2));

                calculateTotals();
            });

            $("body").on("change", ".gst", function() {
                calculateTotals();
            });


            function updateBalance() {
                var finalTotal = Number($("#final_total").val());
                var advance = Number($("#advance").val());
                var balance = finalTotal - advance;

                $("#balance").val(balance.toFixed(2));
                updateBalanceWords();
            }

            function grand_total() {
                var tot = 0;
                $(".total").each(function() {
                    tot += Number($(this).val());
                });
                $("#grand_total").val(tot.toFixed(2));
            }

            function gst_total() {
                var grand_total = Number($("#grand_total").val());
                var gst = Number($(".gst").val());
                var gst_amount = (grand_total * gst) / 100;
                $("#gst_total").val(gst_amount.toFixed(2));
            }

            function final_total() {
                var grand_total = Number($("#grand_total").val());
                var gst_amount = Number($("#gst_total").val());
                var final_total = grand_total + gst_amount;
                $("#final_total").val(final_total.toFixed(2));

                var words = amountToWords(final_total);
                $("#words").val(words);
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
                updateBalance(); // Ensure balance is updated after final_total
            }

            // Initial calculation on page load for pre-filled data
            calculateTotals();


            function amountToWords(num) {
                var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
                var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

                var parts = num.toString().split('.');
                var wholePart = parts[0];
                var decimalPart = parts[1] || '00';
                decimalPart = decimalPart.padEnd(2, '0'); // Ensure two decimal places

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
                var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
                var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

                var n = ('00' + decimalPart).substr(-2).match(/^(\d{1})(\d{1})$/);
                if (!n) return '';

                var str = '';
                if (Number(n[0]) > 0) { // If paisa part is not zero
                    if (Number(n[0]) < 20) {
                        str = a[Number(n[0])];
                    } else {
                        str = b[n[1]] + ' ' + a[n[2]];
                    }
                    str += 'paisa ';
                }
                return str;
            }


            document.getElementById('gstInput').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

           
        });
    </script>
</body>

</html>