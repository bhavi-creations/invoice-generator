<?php
require_once('bhavidb.php');



session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}



// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Perform the delete operation, modify the query based on your table structure
    $deleteSql = "DELETE FROM expenditure_tbl WHERE id = $deleteId";
    $deleteSql2 = "DELETE FROM expenditure_desc_tbl WHERE 	main_expenditure_id	 = $deleteId";

    // Use separate query executions for each DELETE statement
    if ($conn->query($deleteSql) === TRUE && $conn->query($deleteSql2) === TRUE) {
        // Records deleted successfully
        header("Location: view_expenditure.php"); // Redirect to the same page after deletion
        exit(); // Add exit() to stop script execution
    } else {
        // Error deleting records
        echo "Error: " . $conn->error;
    }
}

// Fetch data from the database
$sql = "SELECT * FROM expenditure_tbl";
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

    <link rel="stylesheet" href="img/style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="img/stylemi.css">


    <!-- <style>
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
    </style> -->
    <style>
        .table.viewinvoicetable thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #f2f2f2;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Add shadow to the bottom */
        }

        table {
            background-color: white;
            border-radius: 20px;
        }

        .nav-item {
            padding-top: 20px;
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
    </style>

</head>

<body>

    <!--  LARGE SCREEN NAVBAR  -->
    <div class="container-fluid">
        <div class="row">
                       <?php include('sidebar.php'); ?>





            <!--  Table-->
            <section class="col-lg-10">
                <div class="container " style="margin-top: 70px;">
                    <div class="table-responsive mango " style="max-height: 500px; max-width: 1194px; overflow-y: auto;">
                        <table class="table table-bordered viewinvoicetable">
                            <thead style="position: sticky; top: 0; z-index: 1; background-color: white;">
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Expenditure Date</th>
                                    <th>Total Amount paid</th>
                                    <th style="  padding-right: 30px; padding-left: 30px;  ">Amount in words</th>
                                    <th>Note</th>
                                    <th ">Actions</th>
                                    <!-- <th style=" width: 30%;">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody id="product_tbody viewinvoicetable">
                                <?php
                                // Loop through the fetched data and display it in the table
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr style='border: hidden;'>";
                                    echo "<td style='border: hidden;'>" . $row['id'] . "</td>";
                                    echo "<td style='border: hidden;'>" . $row['date'] . "</td>";
                                    echo "<td style='border: hidden;'>" . $row['total_amount'] .  "</td>";
                                    echo "<td style='border: hidden;'>" . $row['amount_in_words'] . "</td>";
                                    echo "<td style='border: hidden;'>" . $row['exp_note'] . "</td>";
                                    echo "<td style='border: hidden;'> 
                            <div class='btn-group'>
                            <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>
                                    <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                    <button type='submit' class='delete-button' style='border:none;'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
                                    <path d='M3 6H5H21' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                    <path d='M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                    <path d='M10 11V17' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                    <path d='M14 11V17' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                  </svg></button>
                                </form> 
                                <span style='margin-left: 10px;'></span>
                                <button style='border:none;' type='button' class='history-button' data-bs-toggle='modal' data-bs-target='#advance_frm' data-id='{$row['id']}'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-clock-history' viewBox='0 0 16 16'>
                                <path d='M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z' stroke-width='2'/>
                                <path d='M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z' stroke-width='2'/>
                                <path d='M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5' stroke-width='2'/>
                            </svg>
                            
                              
                                </button>
                               
                           
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
                                    <h5 class="modal-title">Expenditure History</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php include('addcus-model.php'); ?>


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



        $(document).ready(function() {
            $('.history-button').click(function() {
                var invoiceNo = $(this).data('id');

                // Make an AJAX request to fetch the advance history for the selected invoice
                $.ajax({
                    type: 'GET',
                    url: 'exp_desc_history.php', // Create a separate PHP file to handle this request
                    data: {
                        invoiceNo: invoiceNo
                    },
                    success: function(response) {
                        // Update the modal content with the fetched data
                        $('#advance_frm .modal-body').html(response);
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
    <?php include('changepass-modal.php') ?>



</body>

</html>

<?php

$conn->close();
?>