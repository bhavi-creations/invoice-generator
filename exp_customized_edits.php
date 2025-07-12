<?php


session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUTOMIZED EDITS|BHAVI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!-- jQuery -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>

    <!-- ADDING STYLE SHEET  -->
    <link rel="stylesheet" href="img/style.css">
    <link rel="stylesheet" href="img/stylemi.css">







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

        .service-btn {
            background-color: #f0f8ff;
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



            <!-- Modal for Add Service-->
            <section class="col-lg-10">
                <div class="container">
                    <div class="row ">
                        <div class="col-6  mt-5">
                            <?php include('addexpcus-model.php'); ?>
                            <!-- <div class=" ">
                                <div class="container ">
                                    <div class="table-container" style="height: 450px; overflow-y: auto;">
                                        <table class="table  viewinvoicetable" style="width: 100%;">
                                            <thead style="  background-color: white;" class="table-head">
                                                <th style="width: 60px;" class="pb-3">SI No</th>
                                                <th class="service_name">Name <a href="#" class="btn service-btn" id="add_exp_customer">ADD CUS_NAME</a></th>
                                                <th class="pb-3">Adress</th>
                                                <th class="pb-3">Phone</th>

                                                Add your other columns here 
                                               Example: <th>Column 2</th> ... <th>Column 10</th> 
                                            </thead>
                                            <tbody>
                                                <?php
                                                // require_once('bhavidb.php');

                                                // $sql = "SELECT * FROM exp_name";
                                                // $res = $conn->query($sql);
                                                // while ($row = mysqli_fetch_assoc($res)) {
                                                //     echo "<tr style='border:none;'>";
                                                //     echo "<td style='border:none;'>" . $row['id'] . "</td>";
                                                //     echo "<td style='border:none;'>" . $row['name'] . "</td>";
                                                //     echo "<td style='border:none;'>" . $row['address'] . "</td>";
                                                //     echo "<td style='border:none;'>" . $row['phone'] . "</td>";
                                                //     // Add data for other columns here
                                                //     echo "</tr>";
                                                // }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <!-- Table For GST-->
                        <div class="  col-12 col-md-5 mt-5 " ">
                            <?php include('addexp-type-modal.php');  ?>
                            
                            <div class=" container add_gst " >
                                <div class=" table-responsive">
                            <table class="table  viewinvoicetable" style="  overflow-y: auto;">
                                <thead style="  background-color: white;" class="table-head">
                                    <th style=" width: 60px;" class="pb-3">SI No</th>
                                    <th>Type of expenditure<a href="#" class="btn service-btn" id="exp_type_modal">ADD</a></th>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once('bhavidb.php');

                                    $sql = "SELECT * FROM `exp_type`";
                                    $res = $conn->query($sql);

                                    if ($res === false) {
                                    } else {
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            echo "<tr style='border:none;'>";
                                            echo "<td  style='border:none;'>" . $row['id'] . "</td>";
                                            echo "<td  style='border:none;'>" . $row['name'] . "</td>";
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
    <div class="container">
        <div class="container mt-2">
            <div class="table-responsive mango" style="max-height: 500px; overflow-y: auto; border-radius: 40px; background-color: white;">
                <table class="table viewinvoicetable pb-5" style="border-collapse: collapse; border: none; border-radius: 40px;">
                    <thead style="position: sticky; top: 0; z-index: 1; background-color: #f2f2f2;">
                        <tr style="background-color: #ffffff;" class="pb-2">
                            <th>Id</th>
                            <th style="  padding-right: 50px; padding-left: 50px;  ">Company Name <a href="#" class="btn service-btn" id="add_exp_customer">ADD CUS_NAME</a></th>
                            <th style="  padding-right:35px; padding-left: 35px;  ">Customer Phone</th>
                            <th>Customer Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product_tbody" class="viewinvoicetable" style="border: hidden;">
                        <?php
                        require_once('bhavidb.php');
                        $sql2 = "SELECT * FROM exp_name";
                        $res2 = $conn->query($sql2);

                        while ($row2 = mysqli_fetch_assoc($res2)) {
                            echo "<tr style='border: hidden;'>";
                            echo "<td style='border: hidden;'>" . $row2['id'] . "</td>";
                            echo "<td style='border: hidden;'>" . $row2['name'] . "</td>";
                            echo  "<td style='border: hidden; '>" . $row2['phone'] . "</td>";
                            echo "<td style='border: hidden;'>" . $row2['address'] . "</td>";
                            echo "<td>
                        <div class='btn-group'>
                            <br><br>
                            <span style='margin-left: 10px;'></span> 
                            <a href=\"delete_exp.php?id={$row2['id']}\" onClick=\"return confirm('Are you sure you want to delete?')\">
                                <button class=\"delete-button-cus\">
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
                                        <path d='M3 6H5H21' stroke='#C01818' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                        <path d='M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6' stroke='#C01818' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                        <path d='M10 11V17' stroke='#C01818' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                        <path d='M14 11V17' stroke='#C01818' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                    </svg>
                                </button>
                            </a>
                        </div>
                    </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </section>

    

    </div>
    </div>
    


</body>

</html>