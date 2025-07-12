<?php


session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

require_once('bhavidb.php');

function getInvoiceId()
{

    include('bhavidb.php');
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
    </style>

</head>

<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>
            <section class="col-lg-10 col-md-12">
                <div class="container col-md-12 ">
                    <!-- FORM -->

                    <form class=" mango p-4 pb-4 mb-5" action="exp_formprocess.php" method="post">
                        <img src="img/Bhavi-Logo-2.png" alt="" class="mx-auto d-block img-fluid" style="max-height: 20%; max-width: 20%;">

                        <div class="row container pt-5 ps-5 mb-5">
                            <div class="col-lg-8 col-sm-12 col-md-12">
                                <h5><strong>Expenditure</strong></h5>
                                <h5><strong>Date :</strong> <input type="date" name="exp_date" id="" class="form-input" required value="<?php echo date('Y-m-d'); ?>">
                                </h5>
                            </div>

                        </div>

                        <div class="container">

                            <div class="col-md-12 text-md-center text-sm-center text-center mb-3 col-12 ">
                                <h3><b>Expenditure</b></h3>
                            </div>


                        </div>

                        <div class="   billing">
                            <div class="table-responsive">
                                <div style="overflow-x:auto;">
                                    <table border="0" class="table table-bordered">

                                        <thead class="thead" style="background-color: #e9ecef;">
                                            <tr>
                                                <th></th>
                                                <th class="text-center">S.no</th>
                                                <th class="text-center   ">Name</th>
                                                <th class="text-center   ">Description</th>
                                                <th class="text-center">Mode of payment</th>
                                                <th class="text-center">Amount paid</th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody id="product_tbody">
                                            <tr>
                                                <td class="text-center"><button style="border: none; background: none;" type="button" id="btn-add-row" class="btn-add-row"><b>+</b></button></td>
                                                <td class="serial-number text-center">01</td>
                                                <td>
                                                    <input type="text" name="exp_name[]" class="form-control" placeholder="Enter Name">
                                                </td>

                                                <td><textarea rows="1" class="form-control" name="exp_description[]" placeholder="DESCRIPITION." style="width: 100%;"></textarea></td>
                                                <td style="width:150px;">
                                                    <select style="width:150px;" name="mode_payment[]" class="form-control mode-payment">
                                                        <option value="">Select</option>
                                                        <option value="Google-pay">Google-Pay</option>
                                                        <option value="Phone-pay">Phone-Pay</option>
                                                        <option value="Paytm">Paytm</option>
                                                        <option value="Bank-personal">Bank (personal)</option>
                                                        <option value="Bank-professional">Bank (professional)</option>
                                                        <option value="cheque">Cheque</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </td>

                                                <td style="width:200px;"><input style="width : 200px;" type='text' required name='amount[]' class='form-control total'></td>
                                                <td class="text-center"><button type='button' value='X' style="border: none; background: none;" class='btn-sm' id='btn-row-remove'><b>X</b></button></td>
                                            </tr>


                                            <!-- Add more rows as needed -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan='5' class="text-right " style="text-align: right;">Total amount</td>
                                                <td colspan="1"><input type='text' name='total_amount_exp' id='grand_total' class='form-control grand_total' required></td>

                                            </tr>
                                            <tr>
                                                <td colspan="9"><input name='exp_words' type='text' class="form-control words" readonly id="balancewords"></td>
                                            </tr>




                                        </tfoot>




                                    </table>
                                </div>
                            </div>

                            <div class="container mt-5">
                                <div class="row">


                                    <div class="col-lg-6 col-md-6 mb-3">
                                        <textarea name="note" class="form-control" style="border-radius: 10px;" rows="5" placeholder="Note"></textarea>
                                    </div>

                                    <div class="col-12 col-lg-2 col-md-2 mt-lg-5">
                                        <input type="submit" name="submit" value="Save" class="btn btn-primary w-100">
                                    </div>
                                </div>
                            </div>


                            <div class="container pt-5 mb-5">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_details" id="office_details" value="office" checked>
                                            <label class="form-check-label" for="office_details"><strong>With GST Payment</strong></label>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_details" id="personal_details" value="personal">
                                            <label class="form-check-label" for="personal_details"><strong>Use Personal Payment Details</strong></label>
                                        </div>
                                    </div>

                                    <div id="office_payment_section" class="col-12 payment-section">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <h5><strong>Scan to Pay:</strong></h5>
                                                <img src="img/qrcode.jpg" alt="Office QR Code" height="120px" width="120px">
                                            </div>
                                            <div class="col-lg-6">
                                                <h5><strong>Payment details</strong></h5>
                                                <p>Bank Name: HDFC Bank, Kakinada</p>
                                                <p>Account Name: Bhavi Creations Private Limited</p>
                                                <p>Account No.: 59213749999999</p>
                                                <p>IFSC: HDFC0000426</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="personal_payment_section" class="col-12 payment-section d-none">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <h5><strong>Scan to Pay:</strong></h5>
                                                <img src="img/personal_qrcode.jpg" alt="Personal QR Code" height="120px" width="120px">
                                            </div>
                                            <div class="col-lg-6">
                                                <h5><strong>Payment details</strong></h5>
                                                <p>Bank Name: State Bank Of India</p>
                                                <p>Account Name: Chalikonda Naga Phaneendra Naidu</p>
                                                <p>Account No.: 20256178992</p>
                                                <p>IFSC: SBIN00001917</p>
                                            </div>
                                        </div>
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
                                        var row = "<tr><td></td> <td class='serial-number'></td><td><select name='exp_name[]' class='form-control'><?php $sql = 'SELECT `name` FROM `exp_name`';
                                                                                                                                                    $res = $conn->query($sql);
                                                                                                                                                    while ($row = mysqli_fetch_assoc($res)) {
                                                                                                                                                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                                                                                                                                    } ?></select></td><td><textarea class='form-control' name='exp_description[]' placeholder='DESCRIPITION.' style='width: 100%;'></textarea></td>  <td><select name='mode_payment[]' id='' class='form-control mode-payment'>                                            <option value='select'>Select</option><option value='Google-pay'>Google-Pay</option><option value='Phone-pay'>Phone-Pay</option><option value='Paytm'>Paytm</option><option value='Bank-personal'>Bank (personal)</option><option value='Bank-professional'>Bank (professional)</option><option value='cheque'>Cheque</option><option value='cash'>Cash</option><option value='Other'>Other</option></select></td><td><input type='text' required name='amount[]' class='form-control qty'></td><td><button type='button' value='X' style='border: none; background: none;' class='btn-sm' id='btn-row-remove'><b>X</b></button></td></tr>";


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

                                    // Calculate total on input change

                                    $(document).on('input', 'input[name="amount[]"]', function() {
                                        calculateTotal();
                                        updateBalanceWords(); // Add this line to update words when the amount changes
                                    });

                                    // Function to calculate total
                                    function calculateTotal() {
                                        var totalAmount = 0;
                                        $('input[name="amount[]"]').each(function() {
                                            var amount = parseFloat($(this).val()) || 0;
                                            totalAmount += amount;
                                        });

                                        $('#grand_total').val(totalAmount.toFixed(2));
                                    }

                                    function updateBalanceWords() {
                                        var grandTotal = Number($("#grand_total").val());
                                        var grandTotalWords = amountToWords(grandTotal);
                                        $("#balancewords").val(grandTotalWords);
                                    }

                                    // Trigger total calculation on page load
                                    calculateTotal();
                                    updateBalanceWords();
                                });


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
                        </div>



                    </form>

                </div>

            </section>
        </div>
    </div>
    <!-- ENDING   INVOICE  FORM  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js" integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(function() {
            $("select").selectize();
        });
        


        document.addEventListener("DOMContentLoaded", function() {
            const officeRadio = document.getElementById("office_details");
            const personalRadio = document.getElementById("personal_details");
            const officeSection = document.getElementById("office_payment_section");
            const personalSection = document.getElementById("personal_payment_section");

            function togglePaymentSection() {
                const isPersonal = personalRadio.checked;
                officeSection.classList.toggle("d-none", isPersonal);
                personalSection.classList.toggle("d-none", !isPersonal);

                // Enable or disable mode of payment dropdowns
                document.querySelectorAll(".mode-payment").forEach(sel => {
                    sel.disabled = !isPersonal;
                    if (!isPersonal) sel.value = ""; // Clear if not personal
                });
            }

            officeRadio.addEventListener("change", togglePaymentSection);
            personalRadio.addEventListener("change", togglePaymentSection);
            togglePaymentSection(); // initialize
        });
    </script>
</body>

</html>