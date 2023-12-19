<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/number-to-words/1.5.1/number-to-words.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/number-to-words@1.5.0/index.js"></script>



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
                <div class="collapse navbar-collapse mr-auto" id="navbarNav">
                    <ul class="navbar-nav navbarleft">
                        <li class="nav-item">
                            <a class="nav-link active text-primary pe-5 me-5" aria-current="page" href="invoice.php">CREATE INVOICE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark pe-5" href="viewinvoices.html">VIEW INVOICES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark ps-5" href="viewcustomers.html">VIEW CUSTOMERS</a>
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
                            <a class="nav-link active" aria-current="page" href="invoice.php">CREATE INVOICE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="viewinvoices.html">VIEW INVOICES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="viewinvoices.html">VIEW CUSTOMERS</a>
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
                        <h5>Date <input type="date" name="invoice_date" id="" class=""></h5>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-md-12 invoicenumber">
                        <h4><strong>INVOICE NUMBER </strong></h4>
                        <h4><strong>BHAVI_KKD_2023_ <input type="text" name="invoice_no" style="border: none;" class="row-1 col-3"></strong></h4>
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
                        <h4 class="pb-2"><strong>Company Name</strong> </h4>
                        <h6> <input name="cname" type="text" placeholder="Name" style="border: none;"></h6>
                        <div class="address">
                            <h6><textarea class="form-control" name="caddress" placeholder="Address" style="border: none; margin-right: 5px;"></textarea> </h6>
                        </div>
                        <h6> <input name="cphone" type="text" placeholder="Phone Number" style="border: none;"></h6>
                        <h6> <input name="cemail" type="text" placeholder="Email" style="border: none;"></h6>
                        <h6> <input name="cgst" type="text" placeholder="GST" style="border: none;"></h6>
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
                                <th>Si_no</th>
                                <th style="width: 253px;"> SERVICES</th>
                                <th style="width: 364px;">DESCRIPITION</th>
                                <th>QUANTITY</th>
                                <th>PRICE</th>
                                <th>TOTAL PRICE </th>
                                <th>DISCOUNT</th>
                                <th>FINAL</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="product_tbody">
                            <tr>
                                <td><input type='button' value='+' class='btn btn-success btn-sm' id='btn-add-row'></td>
                                <td class="serial-number">01</td>
                                <td><select name="Sname[]" class="form-control">
                                        <option value="Logo Design">Logo Design</option>
                                        <option value="Google My Business">Google My Business</option>
                                        <option value="Website">Website</option>
                                        <option value="Social Media Management">Social Media Management</option>
                                        <option value="Image Designing">Image Designing</option>
                                        <option value="Video Creation">Video Creation</option>
                                        <option value="Video Editing">Video Editing</option>
                                        <option value="SEO">SEO</option>
                                        <option value="Printing">Printing</option>
                                        <option value="Vising Cards">Vising Cards</option>
                                        <option value="Letter Heads">Letter Heads</option>
                                        <option value="pamphlet">pamphlet</option>
                                        <option value="Flex">Flex</option>
                                        <option value="Brouchers">Brouchers</option>
                                        <option value="Viny Stickers">Viny Stickers</option>
                                        <option value="Calenders">Calenders</option>
                                        <option value="Diaries">Diaries</option>

                                    </select></td>
                                <td><textarea class="form-control" name="Description[]" placeholder="DESCRIPITION." style="width: 100%;"></textarea></td>
                                <td><input type='text' required name='Qty[]' class='form-control qty'></td>
                                <td><input type='text' required name='Price[]' class='form-control price'></td>
                                <td><input type='text' required name='subtotal[]' class='form-control subtotal'></td>
                                <td><input type='text' required name='discount[]' class='form-control discount'></td>
                                <td><input type='text' required name='total[]' class='form-control total'></td>
                                <td><button type='button' value='X' class='btn-sm' id='btn-row-remove'><b>X</b></button></td>
                            </tr>

                            <!-- Add more rows as needed -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='8' class='text-right' style="text-align: right;">Total</td>
                                <td colspan="2"><input type='text' name='grand_total' id='grand_total' class='form-control grand_total' required></td>
                            </tr>
                            <tr>
                                <td colspan='7' class='text-right' style="text-align: right;">GST</td>
                                <td>
                                    <select name="gst" id="gst" class="form-control gst">
                                        <option value="0">0%</option>
                                        <option value="5">5%</option>
                                        <option value="12">12%</option>
                                        <option value="18">18%</option>
                                    </select>
                                </td>
                                <td colspan="2"><input type='text' name='gst_total' id='gst_total' class='form-control gst_total' required></td>
                            </tr>
                            <<tr>
                                <td colspan="7"><input type='text' class="form-control words" readonly id="words"></td>
                                <td class='text-right' style="text-align: right;">Final Total</td>
                                <td colspan="2"><input type='text' name='Final_total' id='final_total' class='form-control final_total' required readonly></td>
                                </tr>
                        </tfoot>
                    </table>
                    <div class="row container-fluid">
                        <div class="float-left mt-3 col-4">
                            <textarea name="terms&conditions" id="" cols="50" rows="5" placeholder="terms&conditions"></textarea>
                        </div>
                        <div class="row col-2">
                            <div class="text-center pt-5 ">
                                <input type="submit" name="submit" value="Save" class="btn btn-primary ">
                            </div>
                        </div>
                        <div class="row col-2">
                            <div class="text-center pt-5 ">
                                <input type="submit" name="Print" value="Print" class="btn btn-primary float-right">
                            </div>
                        </div>
                        <div class="float-right mt-3 col-4 ms-10">
                            <textarea name="terms&conditions" id="" cols="50" rows="5" placeholder="Note:"></textarea>
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
                                var row = "<tr><td><input type='button' value='+' class='btn btn-success btn-sm' id='btn-add-row'></td> <td class='serial-number'></td><td><select name='Sname[]' class='form-control'> <option value='Logo Design'>Logo Design</option><option value='Google My Business'>Google My Business</option><option value='Website'>Website</option><option value='Social Media Management'>Social Media Management</option><option value='Image Designing'>Image Designing</option><option value='Video Creation'>Video Creation</option><option value='Video Editing'>Video Editing</option><option value='SEO'>SEO</option><option value='Printing'>Printing</option><option value='Vising Cards'>Vising Cards</option><option value='Letter Heads'>Letter Heads</option><option value='pamphlet'>pamphlet</option><option value='Flex'>Flex</option><option value='Brouchers'>Brouchers</option><option value='Viny Stickers'>Viny Stickers</option><option value='Calenders'>Calenders</option><option value='Diaries'>Diaries</option></select></td><td><textarea class='form-control' name='Description[]' placeholder='DESCRIPTION.' style='width: 100%;'></textarea></td><td><input type='text' required name='Qty[]' class='form-control qty'></td><td><input type='text' required name='Price[]' class='form-control price'></td><td><input type='text' required name='subtotal[]' class='form-control subtotal'></td><td><input type='text' required name='discount[]' class='form-control discount'></td><td><input type='text' required name='total[]' class='form-control total'></td><td><button type='button' value='X' class=' btn-sm' id='btn-row-remove'><b>X</b></button></td></tr>";

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


                            $("body").on("keyup", ".price", function() {
                                var price = Number($(this).val());
                                var qty = Number($(this).closest("tr").find(".qty").val());
                                $(this).closest("tr").find(".subtotal").val(price * qty);
                                grand_total();
                            });

                            $("body").on("keyup", ".qty", function() {
                                var qty = Number($(this).val());
                                var price = Number($(this).closest("tr").find(".price").val());
                                $(this).closest("tr").find(".subtotal").val(price * qty);
                                grand_total();
                            });

                            $("body").on("keyup", ".subtotal", function() {
                                var subtotal = Number($(this).val());
                                var discount = Number($(this).closest("tr").find(".discount").val());
                                $(this).closest("tr").find(".total").val(subtotal - (subtotal * (discount / 100)));
                                grand_total();
                            });

                            $("body").on("keyup", ".discount", function() {
                                var discount = Number($(this).val());
                                var subtotal = Number($(this).closest("tr").find(".subtotal").val());
                                $(this).closest("tr").find(".total").val(subtotal - (subtotal * (discount / 100)));
                                grand_total();
                            });

                            function grand_total() {
                                var tot = 0;
                                $(".total").each(function() {
                                    tot += Number($(this).val());
                                });
                                $("#grand_total").val(tot);
                            }
                        });


                        // Additional script for GST calculations
                        $("body").on("keyup", ".grand_total", function() {
                            gst_total();
                        });

                        $("body").on("change", ".gst", function() {
                            gst_total();
                        });

                        function gst_total() {
                            var grand_total = Number($("#grand_total").val());
                            var gst = Number($(".gst").val());
                            var gst_amount = (grand_total * gst) / 100;
                            $("#gst_total").val(gst_amount);
                        }

                        $("body").on("keyup", ".grand_total", function() {
                            calculateTotals();
                        });

                        $("body").on("change", ".gst", function() {
                            calculateTotals();
                        });

                        function calculateTotals() {
                            gst_total();
                            final_total();
                        }

                        function final_total() {
                            var grand_total = Number($("#grand_total").val());
                            var gst_amount = Number($("#gst_total").val());
                            var final_total = grand_total + gst_amount;
                            $("#final_total").val(final_total);
                        }


                        $("body").on("keyup change", "#final_total", function() {
                            var total = Number($(this).val());
                            var words = convertNumberToWords(total);
                            $(".words").val(words);
                        });

                        // Function to convert number to words
                        function convertNumberToWords(number) {
                            var wordsInstance = new numberToWords();
                            return wordsInstance.toWords(number);
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
                            <h6 class="text-center">Google pay , Phone pay. Paytm 9642343434</h6>
                        </div>
                    </div>
                    <!--  ENDING  GOOGLEPAY SECTION  -->





            </form>

            <!-- ENDING  FORM -->
        </div>

    </section>


    <!-- ENDING   INVOICE  FORM  -->




</body>

</html>