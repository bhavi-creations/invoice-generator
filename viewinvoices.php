<?php
require_once('bhavidb.php');

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Perform the delete operation, modify the query based on your table structure
    $deleteSql = "DELETE FROM invoice WHERE Invoice_no = $deleteId";
    if ($conn->query($deleteSql) === TRUE) {
        // Record deleted successfully
        header("Location: viewinvoices.php"); // Redirect to the same page after deletion
        exit(); // Add exit() to stop script execution
    } else {
        // Error deleting record
        echo "Error: " . $conn->error;
    }
}

// Fetch data from the database
$sql = "SELECT * FROM invoice";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHAVIINVOICE</title>

    <!-- BOOTSTRAP PLUGIN -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!-- jQuery -->


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>

    <!-- ADDING STYLE SHEET  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="img/style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
            z-index: 3;
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
    </style>


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
                        <li class="nav-item pe-4">
                            <a class="nav-link text-dark" href="viewcustomers.php">Customers</a>
                        </li>

                        <!-- Invoice dropdown -->
                        <li class="dropdown nav-item pe-4">
                            <a class="nav-link active text-primary" href="#">Invoice <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                    <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                </svg></a>
                            <div class="dropdown-content">
                                <a class="nav-link text-dark" href="quotation.php">
                                    <h6>Create Quotation</h6>
                                </a>
                                <a class="nav-link text-dark" href="index.php">
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

                        <!-- <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="viewinvoices.php">View Invoices</a>
                        </li> -->
                        <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="customized_edits.php">Customized Edits</a>
                        </li>
                        <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="report.php">Reports</a>
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
                            <a class="nav-link" href="customized_edits.php">CUSTOMIZED EDITS</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header>



</body>

</html>



<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Include your head content here -->

</head>

<body>

   
    <div class="container " style="margin-top: 70px;">
        <div class="table-responsive ms-5" style="max-height: 500px; max-width: 1194px; overflow-y: auto;">
            <table class="table table-bordered viewinvoicetable">
                <thead style="position: sticky; top: 0; z-index: 1; background-color: #f2f2f2;">
                    <tr>
                        <th class="text-center" style="width: 10%;">Invoice No</th>
                        <th style="width: 20%;">Customer Name</th>
                        <th style="width: 20%;">Issued Date</th>
                        <th style="width: 10%;">Invoice Amount</th>
                        <th style="width: 10%;" class="status">Status</th>
                        <th style="width: 20%;">Advance Actions</th>
                        <th style="width: 30%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="product_tbody viewinvoicetable">
                    <?php
                    // Loop through the fetched data and display it in the table
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Invoice_no'] . "</td>";
                        echo "<td>" . $row['Cname'] . "</td>";
                        echo "<td>" . $row['Invoice_date'] . "</td>";
                        echo "<td>" . $row['Grandtotal'] . "</td>";
                        echo "<td class='status' data-invoice-no='" . $row['Invoice_no'] . "'>" . $row['status'] .  "</td>";
                        echo "<td> 
                            <div class='btn-group'>
                            <button type='submit' class='view-button'>
                                    <a class='view-button' href='edit.php?Sid={$row['Sid']}'>Edit</a>
                                </button>
                                <span style='margin-left: 10px;'></span>
                                <button type='button' class='history-button' data-bs-toggle='modal' data-bs-target='#advance_frm' data-id='{$row['Invoice_no']}'>
                                History
                                </button>
                               
                           
                            </div>
                        </td>";
                        echo "<td> 
                            <div class='btn-group'>
                            
                                <button type='submit' class='view-button'>
                                    <a class='view-button' href='print.php?Sid={$row['Sid']}'>View</a>
                                </button>
                                <span style='margin-left: 10px;'></span>
                                <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>
                                    <input type='hidden' name='delete_id' value='" . $row['Invoice_no'] . "'>
                                    <button type='submit' class='delete-button'>Delete</button>
                                </form> 
                              <span style='margin-left: 10px;'></span>
                              <select name='status' class='status-dropdown' data-invoice-no=' " . $row['Invoice_no'] . " '>
                              <option value=''>Status</option>
                              <option value='paid'>Paid</option>
                              <option value='pending'>Pending</option>
                           </select>
                           
                            </div>
                        </td>";

                        echo "</tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>


    <div class="container  ">
        <div class="modal" tabindex="-1" id="advance_frm">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Advance History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered viewinvoicetable">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: #f2f2f2;">
                                <tr>
                                    <th class="text-center" style="width: 20%;">Invoice No</th>
                                    <th style="width: 40%;">Date</th>
                                    <th style="width: 40%;">Amount</th>

                                </tr>
                            </thead>
                            <tbody id="product_tbody viewinvoicetable">
                                <?php

                                if (isset($_GET['Invoice_no'])) {
                                    $invoiceNo = mysqli_real_escape_string($conn, $_GET['Invoice_no']);

                                    $sql = "SELECT * FROM advancehistory WHERE `Invoice_no` = '$invoiceNo'";
                                    $result = $conn->query($sql);

                                    // Loop through the fetched data and display it in the table
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['Invoice_no'] . "</td>";
                                        echo "<td>" . $row['Date'] . "</td>";
                                        echo "<td>" . $row['advance'] . "</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // require_once('bhavidb.php');

    // if (isset($_GET['invoice_no'])) {
    //     $invoiceNo = mysqli_real_escape_string($conn, $_GET['Invoice_no']);

    //     $sql = "SELECT * FROM advancehistory WHERE `Invoice_no` = '$invoiceNo'";
    //     $result = $conn->query($sql);

    //     if ($result) {
    //         $data = array();
    //         while ($row = $result->fetch_assoc()) {
    //             $data[] = $row;
    //         }
    //         echo json_encode($data);
    //     } else {
    //         echo "Error fetching data: " . $conn->error;
    //     }
    // } else {
    //     echo "Invalid request";
    // }
    ?>


    <!-- Include your footer content here -->

    <script>
        $(document).ready(function() {
            $('.status-dropdown').change(function() {
                var selectedStatus = $(this).val();
                var invoiceNo = $(this).data('invoice-no');

                // Make an AJAX request to update the status
                $.ajax({
                    type: 'POST',
                    url: 'update_status.php',
                    data: {
                        invoiceNo: invoiceNo,
                        selectedStatus: selectedStatus
                    },
                    success: function(response) {
                        console.log(response);
                        $('td[data-invoice-no="' + invoiceNo + '"].status').text(selectedStatus);
                        location.reload(true);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });



        // document.addEventListener('DOMContentLoaded', function() {
        //     var historyModal = new bootstrap.Modal(document.getElementById('advance_frm'));
        // })
    </script>




</body>

</html>

<?php

$conn->close();
?>