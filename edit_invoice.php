<?php
session_start();
require_once('bhavidb.php');

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

// Check if the form was submitted with the "update" button
if (isset($_POST['update'])) {

    // --- 1. GET THE INVOICE ID ---
    $invoice_id = isset($_POST['Sid']) ? (int)$_POST['Sid'] : 0;
    if ($invoice_id === 0) {
        die("Error: Invoice ID is missing.");
    }

    // --- 2. GET THE FULL CUSTOMER DETAILS FROM THE SELECTED ID ---
    $company_id = (int)$_POST['company'];
    $stmt_customer = $conn->prepare("SELECT * FROM `customer` WHERE `Id` = ?");
    $stmt_customer->bind_param("i", $company_id);
    $stmt_customer->execute();
    $result_customer = $stmt_customer->get_result();
    if ($customer_row = $result_customer->fetch_assoc()) {
        $company_name = $customer_row['Company_name'];
        $cname = $customer_row['Name'];
        $cphone = $customer_row['Phone'];
        $caddress = $customer_row['Address'];
        $cemail = $customer_row['Email'];
        $cgst = $customer_row['Gst_no'];
    } else {
        die("Selected customer not found.");
    }
    $stmt_customer->close();

    // --- 3. CAPTURE THE REST OF THE FORM DATA ---
    $invoice_date = $_POST['invoice_date'];
    $payment_details_type = $_POST['payment_details'];
    $note = $_POST['note'];
    $final_total = (float)$_POST['grand_total']; // This is the subtotal
    $gst_percentage = (float)$_POST['gst'];
    $gst_total = (float)$_POST['gst_total'];
    $grand_total = (float)$_POST['Final_total']; // This is the final total with GST
    $advance = (float)$_POST['advance'];
    $balance = (float)$_POST['balance'];
    $total_in_words = $_POST['words'] ?? '';
    $balance_words = $_POST['balancewords'] ?? '';
    $status = $_POST['status'] ?? 'pending';
    $stampImage = $_POST['stamp_image_path'] ?? ''; // Capture status

    // --- 4. UPDATE THE MAIN INVOICE RECORD ---
    $sql_update_invoice = "UPDATE invoice SET 
        Invoice_date = ?, Company_name = ?, Cname = ?, Cphone = ?, Caddress = ?, Cmail = ?, Cgst = ?, stamp_image = ?,
        Final = ?, Gst = ?, Gst_total = ?, Grandtotal = ?, Totalinwords = ?, Note = ?, advance = ?, balance = ?, balancewords = ?, status = ?, payment_details_type = ?
        WHERE Sid = ?";

    $stmt_update = $conn->prepare($sql_update_invoice);
    // Bind all 19 parameters: s, s, s, s, s, s, s, d, d, d, d, s, s, d, d, s, s, s, i
    $stmt_update->bind_param(
        "ssssssssddddssddsssi",
        $invoice_date,
        $company_name,
        $cname,
        $cphone,
        $caddress,
        $cemail,
        $cgst,
        $stampImage,
        $final_total,
        $gst_percentage,
        $gst_total,
        $grand_total,
        $total_in_words,
        $note,
        $advance,
        $balance,
        $balance_words,
        $status,
        $payment_details_type,
        $invoice_id
    );
    $stmt_update->execute();
    $stmt_update->close();

    // --- 5. UPDATE LINE ITEMS (DELETE AND RE-INSERT) ---
    $stmt_delete_items = $conn->prepare("DELETE FROM service WHERE Sid = ?");
    $stmt_delete_items->bind_param("i", $invoice_id);
    $stmt_delete_items->execute();
    $stmt_delete_items->close();

    $sql_items = "INSERT INTO service (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_items = $conn->prepare($sql_items);

    if (isset($_POST["Sname"]) && is_array($_POST["Sname"])) {
        for ($i = 0; $i < count($_POST["Sname"]); $i++) {
            $stmt_items->bind_param("issiddii", $invoice_id, $_POST["Sname"][$i], $_POST["Description"][$i], $_POST["Qty"][$i], $_POST["Price"][$i], $_POST["subtotal"][$i], $_POST["discount"][$i], $_POST["total"][$i]);
            $stmt_items->execute();
        }
    }
    $stmt_items->close();

    // --- 6. FILE MANAGEMENT (DELETIONS & UPLOADS) ---
    // Handle Deletions

    if (!empty($_POST['delete_files'])) {
        foreach ($_POST['delete_files'] as $file_id_to_delete) {
            $stmt = $conn->prepare("SELECT File_path FROM invoice_files WHERE id = ?");
            $stmt->bind_param("i", $file_id_to_delete);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $file_path = $row['File_path'];
                if (file_exists($file_path)) {
                    unlink($file_path); // Delete file from filesystem
                }
                // Now delete from DB
                $deleteStmt = $conn->prepare("DELETE FROM invoice_files WHERE id = ?");
                $deleteStmt->bind_param("i", $file_id_to_delete);
                $deleteStmt->execute();
                $deleteStmt->close();
            }
            $stmt->close();
        }
    }


    // Handle New Uploads
    if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
        $total_files = count($_FILES['attachments']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            $file_name = $_FILES['attachments']['name'][$i];
            $file_tmp = $_FILES['attachments']['tmp_name'][$i];
            $unique_name = uniqid() . '-' . basename($file_name); // Generate filename only
            $destination_path = 'uploads/attachments/' . $unique_name; // Full path for saving file
            if (move_uploaded_file($file_tmp, $destination_path)) {
                // Save only filename (not full path) to DB
                $stmt = $conn->prepare("INSERT INTO invoice_files (Invoice_id, File_path) VALUES (?, ?)");
                $stmt->bind_param("is", $invoice_id, $unique_name); // Save only filename
                $stmt->execute();
                $stmt->close();
            }
        }
    }


    // --- 7. REDIRECT ON SUCCESS ---
    echo "<script>
            alert('Invoice Updated Successfully!');
            window.location.href='viewinvoices.php';
          </script>";
    exit();
} else {
    // Redirect if accessed directly
    header("Location: viewinvoices.php");
    exit();
}
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

    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css" integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet"> -->


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
            /* background-color: aliceblue; */
            border: 1px solid black;
        }

        th {
            border: none;
            padding: 4px;
            /* Adjust padding as needed */
            text-align: center;
        }

        .table-responsive {
            border-radius: 10px;
            border: 1px solid black;
        }

        .nav-item {
            padding-top: 20px;
        }

        /* 
        .navbar-nav {
            color: black;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 17px;
        }
         */
    </style>

</head>

<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">

            <?php include('sidebar.php'); ?>



            <!--  INVOICE  FORM  -->

            <section class="col-lg-10">
                <div class="container ">

                    <!-- FORM -->

                    <form class="    pb-4 mb-5" action="edit_invoice_form.php" method="post">
                        <img src="img/Bhavi-Logo-2.png" alt="" class="mx-auto d-block" height="20%" width="20%">

                        <!-- FORM INVOICENUMBER -->

                        <div class="row container pt-5   mb-5">
                            <div class="col-lg-8 col-sm-12 col-md-12">
                                <h5><strong>Invoice</strong></h5>
                                <h5>Date : <input type="date" name="invoice_date" style="border: none;" class="row-1 col-3" value="<?php echo $invoice_date; ?>" readonly></h5>
                            </div>
                            <div class="col-lg-4 col-sm-12 col-md-12 invoicenumber">
                                <h5><strong>Invoice Number </strong></h5>
                                <h4><strong>BHAVI_KKD_2024_ <input type="text" name="invoice_no" style="border: none;" class="row-1 col-3" value="<?php echo $invoice_no; ?>" readonly></strong></h4>
                            </div>
                        </div>

                        <!-- ENDING  FORM INVOICENUMBER -->

                        <!--  COMPANY DETAILS  -->

                        <div class="row container  mb-5">
                            <div class="col-lg-8 col-sm-12 col-md-12">
                                <h4 class="pb-2"><strong>Bhavi Creations Pvt Ltd </strong></h4>
                                <h6>Plot no28, H No70, 17-28, RTO Office Rd, opposite to New </h6>
                                <h6>RTO Office, behind J.N.T.U Engineering College Play Ground,</h6>
                                <h6> RangaRaoNagar,Kakinada,</h6>
                                <h6>AndhraPradesh533003</h6>
                                <h6>Phone no.: 9642343434</h6>
                                <h6>Email: admin@bhavicreations.com</h6>
                                <h6>GSTIN: 37AAKCB6960H1ZB.</h6>
                            </div>
                            <div class="col-lg-4 col-sm-12 col-md-12">
                                <h4> <select class="" name="company" id="companySelect" required>
                                        <?php
                                        $sql = "SELECT * FROM `customer`";
                                        $res = $conn->query($sql);
                                        $fetched_data = [];
                                        // echo "<option value='" . $row['Company_name'] . "'>" . $row['Company_name'] . "</option>";
                                        echo "<option value=''>Select Customer/Company</option>";
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            $fetched_data[] = $row;
                                            echo "<option value='" . $row['Id'] . "'>" . $row['Company_name'] . "</option>";
                                        }
                                        // this hidden input is used to store the data & get the data in javascript
                                        echo "<input type='hidden' id='company_data' value='" . json_encode($fetched_data) . "' />";
                                        ?>
                                    </select></h4>
                                <p class="mb-1" id="company_name"><?php echo $company_name; ?></p>
                                <p class="mb-1" id="name"><?php echo $cname; ?></p>
                                <p class="mb-1" id="email"><?php echo $cemail; ?></p>
                                <p class="mb-1" id="phone"><?php echo $cphone; ?></p>
                                <p class="mb-1" id="gst"><?php echo $cgst; ?></p>

                            </div>
                        </div>

                        <!-- ENDING COMPANY DETAILS -->

                        <!-- BILLING SECTION  -->
                        <h3 class="text-center mb-5"><B>BILLING</B></h3>
                        <div class="  billing">
                            <div class="table-responsive">
                                <div style="overflow-x:auto;">
                                    <table border="1">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="text-center">S.no</th>
                                                <th>Id </th>
                                                <th style="width: 266px;" class="text-center"> Services</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center" style="width: 93px;">Qty </th>
                                                <th class="text-center">Price/Unit</th>
                                                <th class="text-center">Sub Total </th>
                                                <th class="text-center">Disc</th>
                                                <th style="padding-left:25px;padding-right:25px;" class="text-center">Disc Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="product_tbody">
                                            <tr>
                                                <?php
                                                $counter = 1;
                                                while ($data = mysqli_fetch_assoc($result2)) {
                                                    echo "<td>";

                                                    if ($counter === 1) {
                                                        echo "<button style='border: none; background: none;' type='button' id='btn-add-row' class='btn-add-row'><b>+</b></button>";
                                                    }

                                                    echo "</td>";
                                                    echo "<td class='serial-number'>$counter</td>";
                                                    echo "<td><input type='text'  name='id[]' class='form-control'  value='" . $data['Id'] . "' readonly></td>";
                                                    echo "<td class='text-center'><select  name='Sname[]' class=''>";
                                                    $sql = "SELECT `service_name` FROM `service_names`";
                                                    $res = $conn->query($sql);

                                                    while ($row = mysqli_fetch_assoc($res)) {
                                                        echo "<option value='" . $data['Sname'] . "'>" . $data['Sname'] . "</option>";
                                                        echo "<option value='" . $row['service_name'] . "'>" . $row['service_name'] . "</option>";
                                                    }
                                                    echo "</select></td>";
                                                    echo '<td><textarea style="width: 250px;" class="form-control" name="Description[]" placeholder="DESCRIPTION." style="width: 100%;">' . $data['Description'] . '</textarea></td>';
                                                    echo "<td><input type='text' required name='Qty[]' class='form-control qty' value='" . $data['Qty'] . "'></td>";
                                                    echo "<td><input type='text' required name='Price[]' class='form-control price' value='" . $data['Price'] . "'></td>";
                                                    echo "<td><input type='text' required name='subtotal[]' class='form-control subtotal' value='" . $data['Totalprice'] . "'></td>";
                                                    echo "<td><input type='text' required name='discount[]' class='form-control discount' value='" . $data['Discount'] . "'></td>";
                                                    echo "<td><input type='text' required name='total[]' class='form-control total' value='" . $data['Finaltotal'] . "'></td>";
                                                    echo "<td>";
                                                    if ($counter != 1) {
                                                        echo "<button type='button' value='X' style='border: none; background: none;' class='btn-sm' id='btn-row-remove'><b>X</b></button>";
                                                    }
                                                    echo "</td>";
                                                    echo "</tr>";
                                                    $counter++;
                                                }
                                                ?>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan='8' class="text-right " style="text-align: right;">Total Before Tax</td>
                                                <td colspan="2"><input type='text' name='grand_total' id='grand_total' class='form-control grand_total' required></td>
                                            </tr>
                                            <tr>
                                                <td colspan='7' class='text-right' style="text-align: right;">GST%</td>
                                                <td>
                                                    <select name="gst" id="gst" class="gst">
                                                        <?php
                                                        require_once('bhavidb.php');
                                                        $sql2 = "SELECT `gst` FROM `gst_no`";
                                                        $result = $conn->query($sql2);
                                                        echo "<option value='$gst'>" . $gst . " </option>";

                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo "<option value='" . $row['gst'] . "'>" . $row['gst'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td colspan="2"><input type='text' name='gst_total' id='gst_total' class='form-control gst_total'></td>
                                                <input type="text" name='Sid' value="<?php echo $invoice_id ?>" hidden>
                                            </tr>
                                            <tr>
                                                <td colspan="7"><input name='words' type='text' class="form-control words" readonly id="words"></td>
                                                <td class="text-center" style="text-align: right;">Total</td>
                                                <td colspan="2"><input type='text' name='Final_total' id='final_total' class='form-control final_total' readonly></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right" class='text-right' style="text-align: right;">Advance</td>
                                                <td colspan="2"><input type='text' name='advance' id='advance' class='form-control advance' value="<?php echo $advance ?>"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"><input name='balancewords' type='text' class="form-control balancewords" value="<?php echo $balancewords ?>" readonly id="balancewords"></td>
                                                <td class="text-right" class='text-right ' style="text-align: right;">Balance</td>
                                                <td colspan="2"><input type='text' name='balance' id='balance' class='form-control balance' value="<?php echo $balance ?>" readonly></td>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                            </div>


                            <div class="container mt-5">
                                <div class="row">

                                    <div class="col-lg-5 col-md-5 col-sm-12 mt-3">
                                        <textarea name="terms" class="form-control" style="border-radius: 10px;" rows="5" placeholder="Terms & Conditions"><?php echo $terms ?></textarea>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-12 mt-3">
                                        <textarea name="note" class="form-control" style="border-radius: 10px;" rows="5" placeholder="Note:"><?php echo $note ?></textarea>
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-12 text-center mt-3">
                                        <input style="   margin-top:50px;" type="submit" name="submit" value="Save & Print" class="btn btn-primary">
                                    </div>

                                </div>
                            </div>
                            <!--  ENDING BILLING SECTION  -->

                            <!--   Functions of invoice -->
                            <script>
                                $(document).ready(function() {
                                    $("#date").datepicker({
                                        dateFormat: "dd-mm-yy"
                                    });

                                    $("#btn-add-row").click(function() {
                                        var row = "<tr><td></td> <td class='serial-number'></td><td></td><td><select name='Sname[]' class='form-control'><?php $sql = 'SELECT `service_name` FROM `service_names`';
                                                                                                                                                            $res = $conn->query($sql);
                                                                                                                                                            while ($row = mysqli_fetch_assoc($res)) {
                                                                                                                                                                echo "<option value='" . $row['service_name'] . "'>" . $row['service_name'] . "</option>";
                                                                                                                                                            } ?></select></td><td><textarea class='form-control' name='Description[]' placeholder='DESCRIPTION.' style='width: 100%;'></textarea></td><td><input type='text' required name='Qty[]' class='form-control qty'></td><td><input type='text' required name='Price[]' class='form-control price'></td><td><input type='text' required name='subtotal[]' class='form-control subtotal'></td><td><input type='text' required name='discount[]' class='form-control discount'></td><td><input type='text' required name='total[]' class='form-control total'></td><td><button type='button' value='X' style='border: none; background: none;' class='btn-sm' id='btn-row-remove'><b>X</b></button></td></tr>";

                                        $("#product_tbody").append(row);

                                        // Update serial numbers
                                        updateSerialNumbers();
                                    });

                                    // Function to update serial numbers
                                    function updateSerialNumbers() {
                                        $(".serial-number").each(function(index) {
                                            $(this).text((index + 1).toString().padStart(2, '0'));
                                        });
                                    }

                                    $("body").on("click", "#btn-row-remove", function() {
                                        if (confirm("Are You Sure?")) {
                                            $(this).closest("tr").remove();
                                            updateSerialNumbers();
                                            grand_total();
                                        }
                                    });





                                    /*----Ending balnce--*/


                                    // $("#advance").val(0);

                                    $("body").on("input", ".price, .qty, .subtotal, .discount, .final_total, #advance, .gst", function() {
                                        var $row = $(this).closest("tr");

                                        var price = Number($row.find(".price").val());
                                        var qty = Number($row.find(".qty").val());
                                        $row.find(".subtotal").val(price * qty);

                                        var subtotal = Number($row.find(".subtotal").val());
                                        var discount = Number($row.find(".discount").val());
                                        $row.find(".total").val(subtotal - (subtotal * (discount / 100)));


                                        // Update final_total and advance fields
                                        var finalTotal = Number($("#final_total").val());


                                        grand_total();
                                        gst_total();
                                        final_total();
                                        updateBalanceWords();
                                        updateBalance();


                                    });
                                    grand_total();
                                    gst_total();
                                    final_total();
                                    updateBalanceWords();

                                    $("body").on("change", ".gst", function() {
                                        calculateTotals();
                                    });

                                    $("body").on("keyup", "#balance", function() {
                                        updateBalanceWords();
                                    });
                                });

                                function updateBalance() {
                                    var finalTotal = Number($("#final_total").val());
                                    var advance = Number($("#advance").val());
                                    var balance = finalTotal - advance;

                                    $("#balance").val(balance);
                                    updateBalanceWords();
                                }

                                function grand_total() {
                                    var tot = 0;
                                    $(".total").each(function() {
                                        tot += Number($(this).val());
                                    });

                                    var formatted_total = tot.toFixed(2);
                                    $("#grand_total").val(formatted_total.toString());
                                }

                                function gst_total() {
                                    var grand_total = Number($("#grand_total").val());
                                    var gst = Number($(".gst").val());
                                    var gst_amount = (grand_total * gst) / 100;

                                    var formatted_gst_amount = gst_amount.toFixed(2);

                                    $("#gst_total").val(formatted_gst_amount);
                                }

                                function final_total() {
                                    var grand_total = Number($("#grand_total").val());
                                    var gst_amount = Number($("#gst_total").val());
                                    var final_total = grand_total + gst_amount;

                                    var formatted_final_total = final_total.toFixed(2);

                                    $("#final_total").val(formatted_final_total);

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
                                    updateBalanceWords();
                                }





                                function amountToWords(num) {
                                    var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
                                    var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

                                    // Separate the whole and decimal parts
                                    var parts = num.toString().split('.');
                                    var wholePart = parts[0];
                                    var decimalPart = parts[1] || 0;

                                    if (wholePart.length > 9) return 'overflow';

                                    var n = ('000000000' + wholePart).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);

                                    if (!n) return '';

                                    var str = '';
                                    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
                                    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
                                    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
                                    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
                                    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'rupees ' : '';

                                    // Handle paisa
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

                                    if (n[1] != 0 || n[2] != 0) {
                                        // If both digits are non-zero, use the combined word
                                        str += (n[1] > 0 ? b[n[1]] : '') + (n[1] > 0 && n[2] > 0 ? ' ' : '') + (n[2] > 0 ? a[n[2]] : '');
                                    } else if (n[1] != 0) {
                                        // If only the first digit is non-zero, use its word
                                        str += (b[n[1]]);
                                    } else if (n[2] != 0) {
                                        // If only the second digit is non-zero, use its word
                                        str += (a[n[2]]);
                                    }

                                    str += (str !== '') ? ' paisa ' : '';

                                    return str;
                                }
                            </script>

                            <!--     SCANNER SECTION  -->

                            <div class=" container pt-5   mb-5  ">

                                <div class="row  ">
                                    <span class="verticalline mb-5"></span>
                                    <div class="col-lg-6  col-sm-12 col-md-6   ">
                                        <h5 class="mb-3"><strong>Scan to Pay:</strong></h5>
                                        <h4><img src="img/qrcode.jpg" alt="" height="120px" width="120px"></h4>
                                        <br>
                                    </div>
                                    <div class="col-lg-6  col-sm-12 col-md-6 invoicenumber only_sm  ">
                                        <h5 class="mb-2"><strong>Payment details</strong></h5>
                                        <h6 class="mb-2">Bank Name : HDFC Bank, Kakinada</h6>
                                        <h6 class="mb-2">Account Name : Bhavi Creations Private Limited</h6>
                                        <h6 class="mb-2">Account No. : 59213749999999</h6>
                                        <h6 class="mb-2">IFSC : HDFC000042</h6>
                                        <br>
                                    </div>
                                    <span class="verticalline  "></span>
                                </div>
                            </div>





                            <!--   ENDING  SCANNER SECTION  -->

                            <!--    GOOGLEPAY SECTION   -->


                            <div class="googlepay container">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <h6 class="text-center">Google Pay, Phone Pay, Paytm: 8686394079</h6>
                                    </div>
                                </div>
                            </div>

                            <!--  ENDING  GOOGLEPAY SECTION  -->







                    </form>
                    <!-- ENDING  FORM -->
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
                            <!-- <div>
                                <p class="float-end d-flex flex-row justify-content-center"><a href="#" class="btn btn-success" id="add_customer">Add Customer</a></p>
                            </div> -->
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>
    <!-- ENDING   INVOICE  FORM  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js" integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(function() {
            $("select").selectize();

            $('#companySelect').change(() => {
                var selectedCompany = $('#companySelect').val();
                var companyData = JSON.parse($('#company_data').val());
                console.log(companyData);
                companyData.forEach(element => {
                    if (element.Id == selectedCompany) {
                        console.log(element);
                        $('#company_name').html(element.Company_name);
                        $('#name').html(element.Name);
                        $('#email').html(element.Email);
                        $('#phone').html(element.Phone);
                        $('#gst').html(element.Gst_no);
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var addCustomerModal = new bootstrap.Modal(document.getElementById('modal_frm'));
            var addCustomerButton = document.getElementById('add_customer');
            addCustomerButton.addEventListener('click', function() {
                addCustomerModal.show();
            });

            document.getElementById('gstInput').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });
    </script>

</body>


</html>