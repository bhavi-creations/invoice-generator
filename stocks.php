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
                <div class='btn-group'>
                    <!-- Edit Button -->
                    <a href='edit_stock.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm me-1'>Edit</a>

                    <!-- Delete Form -->
                    <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this record?\");' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                        <button type='submit' class='delete-button' style='border:none; background-color:#dc3545; padding:5px 10px; color:white; border-radius:4px;'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none'>
                                <path d='M3 6H5H21' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                <path d='M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                <path d='M10 11V17' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                <path d='M14 11V17' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                            </svg>
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
                <?php include('changepass-modal.php'); ?>
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