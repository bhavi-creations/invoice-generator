<?php



require_once('bhavidb.php');

$invoice_id = (isset($_GET['Sid']) ? $_GET['Sid'] : '');


$stmt = $conn->prepare("SELECT * FROM `invoice` WHERE Sid = $invoice_id ");
$stmt->execute();
$result = $stmt->get_result();

$sql2 = "SELECT * FROM service WHERE service.Sid = $invoice_id;";
$result2 = mysqli_query($conn, $sql2);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $invoice_no = $row['Invoice_no'];
        $invoice_date = $row['Invoice_date'];
        $company_name = $row['Company_name'];
        $cname = $row['Cname'];
        $cphone = $row['Cphone'];
        $caddress = $row['Caddress'];
        $cemail = $row['Cmail'];
        $cgst = $row['Cgst'];
        $final_total = $row['Final'];
        $Gst_total = $row['Gst_total'];
        $Grand_total = $row['Grandtotal'];
        $Totalin_word = $row['Totalinwords'];
        $terms = $row['Terms'];
        $note = $row['Note'];
        $advance = $row['advance'];
        $balance = $row['balance'];
        $balancewords = $row['balancewords'];
        $gst = $row['Gst'];
    }
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


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet">


    <link rel="stylesheet" href="img/style.css">



    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 182px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12 px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
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
    </style>

</head>

<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-2">
                <nav id="sidebarMenu" class="  collapse d-lg-block sidebar collapse bg-white">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#"><img src="img/Bhavi-Logo-2.png" alt="" height="88px" width="191px"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class=" navbar-collapse  " id="navbarNav">
                            <ul class="navbar-nav " style="margin-left: 10%; text-align: center;">

                                <li class="nav-item">
                                    <a class="nav-link text-dark nav-links" href="viewcustomers.php">Customers</a>
                                </li>

                                <li class="dropdown nav-item pt-4">
                                    <a class="nav-link text-dark nav-links" href="#">Quotation <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg></a>
                                    <div class="dropdown-content">
                                        <a class="nav-link text-dark" href="quotation.php">
                                            <h6>Create Quotation</h6>
                                        </a>

                                        <a class="nav-link text-dark" href="viewquotes.php">
                                            <h6>View Quotations</h6>
                                        </a>
                                    </div>
                                </li>

                                <!-- Invoice dropdown -->
                                <li class="dropdown nav-item pt-4">
                                    <a class="nav-link  nav-links active-link" href="#">Invoice <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg></a>
                                    <div class="dropdown-content">

                                        <a class="nav-link text-dark" href="createinvoice.php">
                                            <h6>Create Invoice</h6>
                                        </a>
                                        <a class="nav-link text-dark" href="viewinvoices.php">
                                            <h6>View Invoices</h6>
                                        </a>

                                    </div>
                                </li>

                                <!-- <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="viewinvoices.php">View Invoices</a>
                        </li> -->
                                <li class="nav-item pt-4">
                                    <a class="nav-link text-dark nav-links" href="customized_edits.php">Customized Edits</a>
                                </li>

                                <li class="nav-item pt-4">
                                    <a class="nav-link text-dark nav-links" href="report.php">Reports</a>
                                </li>

                                <li class="nav-item pt-4">
                                    <a class="nav-link text-dark nav-links btn-danger" href="index.php">Sign Out</a>
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
                                    <a class="nav-link" href="customized_edits.php">Customized Edits</a>
                                </li>
                                <li class="dropdown nav-item pe-4">
                                    <a class="nav-link active text-primary" href="#">Invoice <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg></a>
                                    <div class="dropdown-content">
                                        <a class="nav-link text-dark" href="quotation.php">
                                            <h6>Create Quotation</h6>
                                        </a>
                                        <a class="nav-link text-dark" href="createinvoice.php">
                                            <h6>Create Invoice</h6>
                                        </a>
                                        <a class="nav-link text-dark" href="viewinvoices.php">
                                            <h6>View Invoices</h6>
                                        </a>
                                        <a class="nav-link text-dark" href="viewquotes.php">
                                            <h6>View Quotes</h6>
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="viewinvoices.php">VIEW INVOICES</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="viewcustomers.php">VIEW CUSTOMERS</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </nav>
            </section>

            <!--  INVOICE  FORM  -->

            <section class="col-lg-10">
                <div class="container pt-5">

                    <!-- FORM -->

                    <form class=" formborder rounded p-4 pb-4 mb-5" action="editform.php" method="post">
                        <img src="img/Bhavi-Logo-2.png" alt="" class="mx-auto d-block" height="20%" width="20%">

                        <!-- FORM INVOICENUMBER -->

                        <div class="row container pt-5 ps-5 mb-5">
                            <div class="col-lg-8 col-sm-12 col-md-12">
                                <h5><strong>Invoice</strong></h5>
                                <h5>Date : <input type="text" name="invoice_no" style="border: none;" class="row-1 col-3" value="<?php echo $invoice_date; ?>" readonly></h5>
                            </div>
                            <div class="col-lg-4 col-sm-12 col-md-12 invoicenumber">
                                <h5><strong>Invoice Number </strong></h5>
                                <h4><strong>BHAVI_KKD_2024_ <input type="text" name="invoice_no" style="border: none;" class="row-1 col-3" value="<?php echo $invoice_no; ?>" readonly></strong></h4>
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
                                <h4> To</h4>
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
                        <div class="container-fluid billing">
                            <table border="1">
                                <thead>
                                    <tr>

                                        <th class="text-center">S.no</th>
                                        <th style="width: 324px;" class="text-center"> Services</th>
                                        <th style="width: 420px;" class="text-center">Description</th>
                                        <th class="text-center">Qty </th>
                                        <th class="text-center">Price/Unit</th>
                                        <th class="text-center">Sub Total </th>
                                        <th class="text-center">Disc</th>
                                        <th class="text-center">Disc Total</th>
                                        <!-- <th></th> -->
                                    </tr>
                                </thead>
                                <tbody id="product_tbody">
                                    <tr>
                                        <?php
                                        $counter = 1;
                                        while ($data = mysqli_fetch_assoc($result2)) {

                                            echo  "<tr >";
                                            echo "<td class='serial-number'>" . sprintf('%02d', $counter) . "</td>";
                                            echo "<td class= 'text-center'>" . $data['Sname'] . " </td>";
                                            echo "<td class= 'text-center'>" . $data['Description'] . "</td>";
                                            echo "<td class= 'text-center'>" . $data['Qty'] . "</td>";
                                            echo "<td class= 'text-center'>" . $data['Price'] . "</td>";
                                            echo "<td class= 'text-center'>" . $data['Totalprice'] . "</td>";
                                            echo "<td class= 'text-center'>" . $data['Discount'] . "</td>";
                                            echo "<td class= 'text-center'>" . $data['Finaltotal'] . "</td>";
                                            echo "</tr>";
                                            $counter++;
                                        }
                                        ?>
                                    </tr>

                                    <!-- Add more rows as needed -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan='7' class='text-right' style="text-align: right;">Total Before Tax</td>
                                        <td colspan="1" class="text-center"><?php echo $final_total; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan='6' class='text-right' style="text-align: right;">GST%</td>
                                        <td class="text-center"><?php echo $gst; ?></td>
                                        <td colspan="1" class="text-center"><?php echo $Gst_total; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-center"><?php echo $Totalin_word; ?></td>
                                        <td class="text-center" class='text-right' style="text-align: right;">Total</td>
                                        <td colspan="2"><input type='text' name='Final_total' id='final_total' class='form-control final_total' value="<?php echo $Grand_total; ?>" readonly></td>
                                    <tr>
                                        <td colspan="7" class="text-right" class='text-right' style="text-align: right;">Pre Advance</td>
                                        <td colspan="" class="text-center"><input type='text' name='advance' id='advance' class='form-control advance' value="<?php echo $advance; ?>" readonly></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right" class='text-right' style="text-align: right;">New Advance</td>
                                        <td colspan="" class="text-center"><input type='text' name='newadvance' id='newadvance' class='form-control newadvance'></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"><input name='balancewords' type='text' class="form-control balancewords" readonly id="balancewords" value="<?php echo $balancewords; ?>"></td>
                                        <td class="text-right" class='text-right' style="text-align: right;">Balance</td>
                                        <td colspan="" class="text-center"><input type='text' name='balance' id='balance' class='form-control balance' value="<?php echo $balance; ?>" readonly></td>
                                        <input type='text' name='totaladvance' id='totaladvance' class='form-control totaladvance' hidden>
                                        <input type='text' name='Sid' id='Sid' class='form-control Sid' value="<?php echo $invoice_id; ?>" hidden>
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
                                    $("body").on("input", " #final_total,#advance, #newadvance", function() {
                                        var $row = $(this).closest("tr");
                                        updateBalanceWords();
                                        updateBalance();


                                    });



                                    $("body").on("keyup", "#balance", function() {
                                        updateBalanceWords();
                                    });
                                });

                                function updateBalance() {
                                    var finalTotal = Number($("#final_total").val());
                                    var advance = Number($("#advance").val());
                                    var newadvance = Number($("#newadvance").val());
                                    var balance = finalTotal - advance - newadvance;
                                    var totaladvance = advance + newadvance;

                                    $("#totaladvance").val(totaladvance);
                                    $("#balance").val(balance);
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
                                    <h5 class="mb-3"><strong>Scan to Pay:</strong></h5>
                                    <h4><img src="img/qrcode.jpg" alt="" height="20%" width="20%"></h4>
                                </div>
                                <div class="col-lg-6  col-sm-12 col-md-12 invoicenumber">
                                    <h5 class="mb-2"><strong>Payment details</strong></h5>
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
            </div>
    </div>
</body>


</html>