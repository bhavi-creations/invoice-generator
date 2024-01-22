<?php
require_once('bhavidb.php');

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Perform the delete operation, modify the query based on your table structure
    $deleteSql = "DELETE FROM quotation WHERE quotation_no = $deleteId";
    if ($conn->query($deleteSql) === TRUE) {
        // Record deleted successfully
        header("Location: viewquotes.php"); // Redirect to the same page after deletion
        exit(); // Add exit() to stop script execution
    } else {
        // Error deleting record
        echo "Error: " . $conn->error;
    }
}

// Fetch data from the database
$sql = "SELECT * FROM quotation";
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
                                <li class="nav-item nav-links">
                                    <a class="nav-link text-dark" href="customized_edits.php">Customized Edits</a>
                                </li>



                                <li class="dropdown nav-item pt-4">
                                    <a class="nav-link nav-links active-link" href="#">Quotation <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
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
                                    <a class="nav-link  nav-links " href="#">Invoice <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg></a>
                                    <div class="dropdown-content">

                                        <a class="nav-link text-dark" href="index.php">
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
                                    <a class="nav-link text-dark nav-links" href="viewcustomers.php">Customers</a>
                                </li>
                                <li class="nav-item pt-4">
                                    <a class="nav-link text-dark nav-links" href="report.php">Reports</a>
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


            <section class="col-lg-10">
                <div class="container " style="margin-top: 70px;">
                    <div class="table-responsive ms-5" style="max-height: 500px; max-width: 1194px; overflow-y: auto;">
                        <table class="table table-bordered viewinvoicetable">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: #f2f2f2;">
                                <tr>
                                    <th class="text-center" style="width: 10%;">Quotation No</th>
                                    <th style="width: 30%;">Customer Name</th>
                                    <th style="width: 20%;">Issued Date</th>
                                    <th style="width: 10%;">Quotation Amount</th>
                                    <!-- <th style="width: 10%;" class="status">Status</th> -->
                                    <th style="width: 20%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="product_tbody" class="viewinvoicetable">
                                <?php
                                // Loop through the fetched data and display it in the table
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['quotation_no'] . "</td>";
                                    echo "<td>" . $row['Cname'] . "</td>";
                                    echo "<td>" . $row['quotation_date'] . "</td>";
                                    echo "<td>" . $row['Grandtotal'] . "</td>";
                                    echo "<td> 
                                <div class='btn-group'>
                                    <form method='POST' action='convert.php'>
                                        <input type='hidden' name='convert_id' value='" . $row['Sid'] . "'>
                                        <button type='submit' class='history-button'>
                                            Convert
                                        </button>
                                    </form>
                                    <span style='margin-left: 10px;'></span>
                                    <button type='submit' class='view-button'>
                                        <a class='view-button' href='quprint.php?Sid={$row['Sid']}'>View</a>
                                    </button>
                                    <span style='margin-left: 10px;'></span>
                                    <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>
                                        <input type='hidden' name='delete_id' value='" . $row['quotation_no'] . "'>
                                        <button type='submit' class='delete-button'>Delete</button>
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
            </section>
        </div>
    </div>

    <!-- Include your footer content here -->

    <!-- <script>
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

    </script> -->


</body>

</html>

<?php

$conn->close();
?>