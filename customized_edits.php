<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHAVIINVOICE</title>

    <!-- BOOTSTRAP PLUGIN -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- jQuery -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>

    <!-- ADDING STYLE SHEET  -->
    <link rel="stylesheet" href="img/style.css">



    <style>
        .table-container {
           position: relative;
        }

        .table-head {
            position: sticky;
            top: 0;
            background-color: #fff;  
            z-index: 1;  
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
                            <li class="nav-item pe-5">
                                <a class="nav-link text-dark" href="index.php">CREATE INVOICE</a>
                            </li>
                            <li class="nav-item pe-5">
                                <a class="nav-link text-dark" href="viewinvoices.php">VIEW INVOICES</a>
                            </li>
                            <li class="nav-item pe-5">
                                <a class="nav-link text-dark" href="viewcustomers.php">VIEW CUSTOMERS</a>
                            </li>
                            <li class="nav-item pe-5">
                                <a class="nav-link active text-primary" href="customized_edits.php">CUSTOMIZED EDITS</a>
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

    
     <!-- Modal for Add Service-->

        <div class="container">
            <div class="row   "> 
                <div class="col-6  mt-3"> 
                
                    <div class="text-center "  > 
                        <div class="container  ">
                            <div class="modal" tabindex="-1" id="modal_service">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                                <h5 class="modal-title">Service Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="servicesmodal.php" method="post">
                                                        <div class="form-group">
                                                            <label for="">Service Name</label>
                                                            <input type="text" name="service_name" class="form-control">
                                                        </div>
                                                        <input type="submit" name="submit" id="submit" class="btn btn-success  ">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class=""><a href="#" class="btn btn-success" id="add_service">ADD SERVICES</a></p>
                        </div>
                    </div>
                
                        
    

                    <div class=" ">
                            <div class="container ">
                                <div class="table-container" style="height: 450px; overflow-y: auto;">
                                    <table class="table table-striped viewinvoicetable" style="width: 100%;">
                                        <thead style="position: sticky; top: 0;  background-color: #f2f2f2;"     class="table-head">
                                            <th style="width: 60px;">SI No</th>
                                            <th  class="service_name">Service Name</th>
                                            <!-- Add your other columns here -->
                                            <!-- Example: <th>Column 2</th> ... <th>Column 10</th> -->
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once('bhavidb.php');

                                            $sql = "SELECT * FROM service_names";
                                            $res = $conn->query($sql);
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                echo "<tr>";
                                                echo "<td>" . $row['si_No'] . "</td>";
                                                echo "<td>" . $row['service_Name'] . "</td>";
                                                // Add data for other columns here
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                </div>


            <!-- Table For GST-->
            
                <div class="col-6 mt-3">
                    
                        <div class="container  text-center ">
                            <div class="modal" tabindex="-1" id="modal_gst">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">GST Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="gstmodal.php" method="post">
                                                <div class="form-group">
                                                    <label for="">GST %</label>
                                                    <input type="text" name="gst" class="form-control">
                                                </div>
                                                <input type="submit" name="submit" id="submit" class="btn btn-success mt-5">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class=""><a href="#" class="btn btn-success" id="add_gst">ADD GST</a></p>
                        </div>
                     
 
                 
                    <div class="container add_gst "  style="margin-left:120px;">
                            <div class="table-responsive"   >
                                <table class="table table-stripped viewinvoicetable" style="width: 300px; " >
                                    <thead>
                                        <th style=" width: 60px;">SI No</th>
                                        <th>GST%</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require_once('bhavidb.php');

                                        $sql = "SELECT * FROM gst_no";
                                        $res = $conn->query($sql);

                                        if ($res === false) {
                                        
                                        } else {
                                            while ($row = mysqli_fetch_assoc($res)) {
                                                echo "<tr>";
                                                echo "<td>" . $row['si_No'] . "</td>";
                                                echo "<td>" . $row['gst'] . "</td>";
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

    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            var addServiceModal = new bootstrap.Modal(document.getElementById('modal_service'));

            var addServiceButton = document.getElementById('add_service');
            addServiceButton.addEventListener('click', function() {
                addServiceModal.show();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var addGstModal = new bootstrap.Modal(document.getElementById('modal_gst'));

            var addGstButton = document.getElementById('add_gst');
            addGstButton.addEventListener('click', function() {
                addGstModal.show();
            });
        });
    </script>

</body>

</html>