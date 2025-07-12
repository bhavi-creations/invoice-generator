<?php
require_once('bhavidb.php');

session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}


// define('INVOICE_INITIAL_VALUE', '1');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Perform the delete operation, modify the query based on your table structure
    $deleteSql = "DELETE FROM stocks WHERE id = $deleteId";

    if ($conn->query($deleteSql) === TRUE) {
        // Records deleted successfully
        header("Location: stocks.php"); // Redirect to the same page after deletion
        exit(); // Add exit() to stop script execution
    } else {
        // Error deleting records
        echo "Error: " . $conn->error;
    }
}

// Fetch data from the database





?>

<!DOCTYPE html>
<html lang="en">
            <?php include('header.php'); ?>
 

<body>
    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>



            <!--  INVOICE  FORM  -->

            <section class="col-lg-10 col-md-12">
                <div class="container col-md-12 ">

                    <!-- FORM -->

                    <form class=" mango p-4 pb-4 mb-5" action="stock_form_process.php" method="post">
                        <img src="img/Bhavi-Logo-2.png" alt="" class="mx-auto d-block img-fluid" style="max-height: 20%; max-width: 20%;">


                        <!-- FORM INVOICENUMBER -->



                        <!-- ENDING  FORM INVOICENUMBER -->

                        <!--  COMPANY DETAILS  -->




                        <div class="container mt-5">

                            <div class="col-md-12 text-md-center text-sm-center text-center mb-3 col-12 ">
                                <h3><b>Stocks</b></h3>
                            </div>


                        </div>

                        <div class="  billing">
                            <div class=" ">
                                <div style="overflow-x:auto;">
                                    <table class="table table-bordered">

                                        <thead class="thead" style="background-color: #e9ecef;">
                                            <tr>
                                                <th></th>
                                                <th class="text-center">S.no</th>
                                                <th class="text-center  ">Name(Stock)</th>
                                                <th class="text-center ">Description</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Details</th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody id="product_tbody">
                                            <tr>
                                                <td><button style="border: none; background: none;" type="button" id="btn-add-row" class="btn-add-row"><b>+</b></button></td>
                                                <td class="serial-number">01</td>
                                                <td style="width:250px;"><textarea class="form-control" rows="1" name="stock_name[]" placeholder="Name of stock." style="width: 100%;"></textarea></td>
                                                <td style="width:200px;"><textarea style="width:200px;" class="form-control" rows="1" name="stock_desc[]" placeholder="DESCRIPITION." style="width: 100%;"></textarea></td>
                                                <td><input type='text' required name='stock_qty[]' class='form-control total'></td>
                                                <td style="width:250px;"><textarea style="width:250px;" class="form-control" rows="1" name="stock_details[]" placeholder="details." style="width: 100%;"></textarea></td>
                                                <td><button type='button' value='X' style="border: none; background: none;" class='btn-sm' id='btn-row-remove'><b>X</b></button></td>
                                            </tr>


                                            <!-- Add more rows as needed -->
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="container mt-5 d-flex flex-row justify-content-center">
                                <div class="col-12 col-lg-2 mt-lg-3">
                                    <input type="submit" name="submit" value="Save" class="btn btn-primary w-100">
                                </div>
                            </div>
                            <!--  ENDING BILLING SECTION  -->

                            <!--   Functions of invoice -->


                            <script>
                                $(document).ready(function() {

                                    $("#btn-add-row").click(function() {
                                        var row = "<tr><td></td><td class='serial-number'>01</td><td><textarea class='form-control'  name='stock_name[]' placeholder='Name of stock.' style='width: 100%;'></textarea></td><td><textarea class='form-control' name='stock_desc[]' placeholder='DESCRIPITION.' style='width: 100%;'></textarea></td><td><input type='text' required name='stock_qty[]' class='form-control total'></td><td><textarea class='form-control' name='stock_details[]' placeholder='details.' style='width: 100%;'></textarea></td><td><button type='button' value='X' style='border: none; background: none;' class='btn-sm' id='btn-row-remove'><b>X</b></button></td></tr>";


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

                                    calculateTotal();
                                    updateBalanceWords();
                                });
                            </script>



                    </form>

                    <div style="margin-top: 70px;">
                        <div class="table-responsive " style="max-height: 500px; max-width: 1194px; overflow-y: auto;">
                            <table class="table table-bordered viewinvoicetable">
                                <thead style="position: sticky; top: 0; z-index: 1; background-color: white;">
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th style="padding-right: 50px; padding-left: 50px;">Name</th>
                                        <th style="padding-right: 30px; padding-left: 30px;">Description</th>
                                        <th>Quantity</th>
                                        <th style="padding-right: 50px; padding-left: 50px;">Details</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="product_tbody viewinvoicetable">
                                    <?php
                                    $sql = "SELECT * FROM stocks";
                                    $result = $conn->query($sql);

                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr style='border: hidden;'>";
                                        echo "<td style='border: hidden;'>" . $row['id'] . "</td>";
                                        echo "<td style='border: hidden;'>" . htmlspecialchars($row['stock_name']) . "</td>";
                                        echo "<td style='border: hidden;'>" . htmlspecialchars($row['stock_desc']) . "</td>";
                                        echo "<td style='border: hidden;'>" . htmlspecialchars($row['stock_qty']) . "</td>";
                                        echo "<td style='border: hidden;'>" . htmlspecialchars($row['stock_details']) . "</td>";
                                        echo "<td style='border: hidden;'> 
    <div class='btn-group' role='group'>

        <!-- Edit Button -->
        <a href='edit_stock.php?id=" . $row['id'] . "' class='btn btn-sm me-2' style='background-color: #ffc107; color: white;' title='Edit'>
            <i class='bi bi-pencil-square'></i>
        </a>

        <!-- Delete Form -->
        <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this record?\");' style='display:inline;'>
            <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
            <button type='submit' class='btn btn-sm' style='background-color: #dc3545; color: white;' title='Delete'>
                <i class='bi bi-trash-fill'></i>
            </button>
        </form>

    </div>
</td>";

                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
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