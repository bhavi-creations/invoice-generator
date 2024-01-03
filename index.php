<?php

define('INVOICE_INITIAL_VALUE', '1');


require_once('bhavidb.php');

function getInvoiceId()
{
    $server = 'localhost';
    // Condition to check if the script is running locally or on a server
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
        // Local environment details
        $username = 'root';
        $pass = '';
        $database = 'bhavi_invoice_db';
    } else {
        // Server environment details
        $username = 'cnpthbbs_invoice_user';
        $pass = '%tNc6peV4-}w';
        $database = 'cnpthbbs_invoice';
    }

    $conn = mysqli_connect($server, $username, $pass, $database);

    if ($conn->connect_error) {
        die('Error : (' . $conn->connect_errno . ') ' . $conn->connect_error);
    }

    $query = "SELECT Invoice_no FROM invoice ORDER BY Invoice_no DESC LIMIT 1";

    if ($result = $conn->query($query)) {
        $row_cnt = $result->num_rows;

        $row = mysqli_fetch_assoc($result);

        if ($row_cnt == 0) {
            $nextInvoiceNumber = INVOICE_INITIAL_VALUE;
        } else {
            $nextInvoiceNumber = $row['Invoice_no'] + 1;
        }


        $formattedInvoiceNumber = sprintf('%04d', $nextInvoiceNumber);


        $result->free();


        $conn->close();

        return $formattedInvoiceNumber;
    }
}

$invoiceNumber = getInvoiceId();

/* Customer Details */



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

    <link rel="stylesheet" href="img/style.css">

</head>

<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <header>

        <nav class="navbar navbar-expand-lg navbar-light bg-light d-none d-lg-block">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="img/Bhavi-Logo-2.png" alt="" height="50%" width="50%"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse ms-auto " id="navbarNav">
                    <ul class="navbar-nav " style="margin-left: 10%;">
                        <li class="nav-item pe-5">
                            <a class="nav-link active text-primary" href="index.php">CREATE INVOICE</a>
                        </li>
                        <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="viewinvoices.php">VIEW INVOICES</a>
                        </li>
                        <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="viewcustomers.php">VIEW CUSTOMERS</a>
                        </li>
                        <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="customized_edits.php">CUSTOMIZED EDITS</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <!-- SMALL SCREEN AND MEDIUM SCREEN  NAVBAR -->

        <nav class="navbar navbar-expand-lg navbar-light bg-light d-block d-lg-none ">
            <div class="container-fluid">
                <div class="navbar-header">
                    <!-- <a class="navbar-brand" href="#"><img src="img/Bhavi-Logo-2.png" alt="" height="50%" width="50%"></a> -->
                    <a class="navbar-brand" href="#">Navbar</a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">CREATE INVOICE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="viewinvoices.php">VIEW INVOICES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="viewcustomers.php">VIEW CUSTOMERS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="customized_edits.php">Customized Edits</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header>

    <!--  INVOICE  FORM  -->

    <section>
        <div class="container pt-5">

            <!-- FORM -->

            <form class=" formborder rounded p-4 pb-4 mb-5" action="formprocess.php" method="post">
                <img src="img/Bhavi-Logo-2.png" alt="" class="mx-auto d-block" height="30%" width="30%">

                <!-- FORM INVOICENUMBER -->

                <div class="row container pt-5 ps-5 mb-5">
                    <div class="col-lg-8 col-sm-12 col-md-12">
                        <h4><strong>INVOICE</strong></h4>
                        <h5>Date : <input type="date" name="invoice_date" id="" class="" style="border-radius:3px;"></h5>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-md-12 invoicenumber">
                        <h4><strong>INVOICE NUMBER </strong></h4>
                        <h4><strong>BHAVI_KKD_2024_ <input type="text" name="invoice_no" style="border: none;" class="row-1 col-3" value="<?php echo $invoiceNumber; ?>" readonly></strong></h4>
                    </div>
                </div>

                <!-- ENDING  FORM INVOICENUMBER -->

                <!--  COMPANY DETAILS  -->

                <div class="row container ps-5 mb-5">
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
                        <h4>
                            <select name="company" id="companySelect">
                                <?php
                                $sql = "SELECT * FROM `customer`";
                                $res = $conn->query($sql);
                                $fetched_data = [];
                                echo "<option value=''>Select Customer/Company</option>";
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $fetched_data[] = $row;
                                    echo "<option value='" . $row['Id'] . "'>" . $row['Company_name'] . "</option>";
                                }
                                // this hidden input is used to store the data & get the data in javascript
                                echo "<input type='hidden' id='company_data' value='" . json_encode($fetched_data) . "' />";
                                ?>
                            </select>
                        </h4>
                        <p class="mb-1" id="company_name"></p>
                        <p class="mb-1" id="name"></p>
                        <p class="mb-1" id="email"></p>
                        <p class="mb-1" id="phone"></p>
                        <p class="mb-1" id="gst"></p>
                    </div>
                </div>

                <!-- ENDING COMPANY DETAILS -->

                <!-- BILLING SECTION  -->
                <h3 class="text-center mb-5"><B>BILLING</B></h3>
                <div class="container-fluid billing">
                    <table border="1">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-center">S.NO</th>
                                <th style="width: 253px;" class="text-center"> SERVICES</th>
                                <th style="width: 364px;" class="text-center">DESCRIPITION</th>
                                <th class="text-center">QTY </th>
                                <th class="text-center">PRICE/UNIT</th>
                                <th class="text-center">SUB TOTAL </th>
                                <th class="text-center">DISC</th>
                                <th class="text-center">DISC TOTAL</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="product_tbody">
                            <tr>
                                <td><button style="border: none; background: none;" type="button" id="btn-add-row" class="btn-add-row"><b>+</b></button></td>
                                <td class="serial-number">01</td>
                                <td> <select name="Sname[]" class="form-control">
                                        <?php
                                        $sql = "SELECT `service_name` FROM `service_names`";
                                        $res = $conn->query($sql);

                                        while ($row = mysqli_fetch_assoc($res)) {
                                            echo "<option value='" . $row['service_name'] . "'>" . $row['service_name'] . "</option>";
                                        }
                                        ?>
                                    </select></td>
                                <td><textarea class="form-control" name="Description[]" placeholder="DESCRIPITION." style="width: 100%;"></textarea></td>
                                <td><input type='text' required name='Qty[]' class='form-control qty'></td>
                                <td><input type='text' required name='Price[]' class='form-control price'></td>
                                <td><input type='text' required name='subtotal[]' class='form-control subtotal'></td>
                                <td><input type='text' required name='discount[]' class='form-control discount'></td>
                                <td><input type='text' required name='total[]' class='form-control total'></td>
                                <td><button type='button' value='X' style="border: none; background: none;" class='btn-sm' id='btn-row-remove'><b>X</b></button></td>
                            </tr>

                            <!-- Add more rows as needed -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='8' class='text-right' style="text-align: right;">Total Before Tax</td>
                                <td colspan="2"><input type='text' name='grand_total' id='grand_total' class='form-control grand_total' required></td>
                            </tr>
                            <tr>
                                <td colspan='7' class='text-right' style="text-align: right;">GST%</td>
                                <td>
                                    <select name="gst" id="gst" class="form-control gst">
                                        <?php
                                        require_once('bhavidb.php');
                                        $sql2 = "SELECT `gst` FROM `gst_no`";
                                        $result = $conn->query($sql2);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='" . $row['gst'] . "'>" . $row['gst'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td colspan="2"><input type='text' name='gst_total' id='gst_total' class='form-control gst_total'></td>
                            </tr>
                            <tr>
                                <td colspan="7"><input name='words' type='text' class="form-control words" readonly id="words"></td>
                                <td class="text-center" class='text-right' style="text-align: right;">Total</td>
                                <td colspan="2"><input type='text' name='Final_total' id='final_total' class='form-control final_total' readonly></td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-right" class='text-right' style="text-align: right;">Advance</td>
                                <td colspan="2"><input type='text' name='advance' id='advance' class='form-control advance'></td>
                            </tr>
                            <tr>
                                <td colspan="7"><input name='balancewords' type='text' class="form-control balancewords" readonly id="balancewords"></td>
                                <td class="text-right" class='text-right' style="text-align: right;">Balance</td>
                                <td colspan="2"><input type='text' name='balance' id='balance' class='form-control balance' readonly></td>
                            </tr>

                        </tfoot>
                    </table>

                    <div class="container mt-5">
                        <div class="row   ">

                            <div class="  mt-3 col-5     ">
                                <textarea name="terms" id="" cols="50" rows="5" placeholder="terms&conditions"></textarea>
                            </div>
                            <div class=" col-2   pt-5 ps-5" style="margin-right:-5px;">

                                <input type="submit" name="submit" value="Save & Print" class="btn btn-primary ">

                            </div>


                            <div class="  mt-3 col-5  " style="padding-left: 111px;">
                                <textarea name=" note" id="" cols="50" rows="5" placeholder="Note:"></textarea>
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
                                var row = "<tr><td></td> <td class='serial-number'></td><td><select name='Sname[]' class='form-control'><?php $sql = 'SELECT `service_name` FROM `service_names`';
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

                    <div class="row container pt-5 ms-5 mb-5">
                        <span class="verticalline mb-5"></span>
                        <div class="col-lg-6  col-sm-12 col-md-12 ps-5">
                            <h4 class="mb-3"><strong>Scan to Pay:</strong></h4>
                            <h4><img src="img/Vector.svg" alt="" height="20%" width="20%"></h4>
                        </div>
                        <div class="col-lg-6  col-sm-12 col-md-12 invoicenumber">
                            <h4 class="mb-3"><strong>Payment details</strong></h4>
                            <h6 class="mb-2">Bank Name : HDFC Bank, Kakinada</h6>
                            <h6 class="mb-2">Account Name : Bhavi Creations Private Limited</h6>
                            <h6 class="mb-2">Account No. : 59213749999999</h6>
                            <h6 class="mb-2">IFSC : HDFC000042</h6>
                        </div>
                        <span class="verticalline mt-5"></span>
                    </div>
                    <!--   ENDING  SCANNER SECTION  -->

                    <!--    GOOGLEPAY SECTION   -->

                    <div class="googlepay">
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <h6 class="text-center">Google pay , Phone pay. Paytm 8686394079</h6>
                        </div>
                    </div>
                    <!--  ENDING  GOOGLEPAY SECTION  -->





            </form>

            <!-- ENDING  FORM -->
        </div>

    </section>


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
    </script>

</body>


</html>