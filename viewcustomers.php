<?php

session_start();
if (!isset($_SESSION['email'])) {
  header('Location:index.php');
  exit();
}

include('bhavidb.php');

$sql = "SELECT COUNT(*) AS rowCount From `customer`";
$sql2 = "SELECT COUNT(*) AS rowCount2 From `invoice`";


$result = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql2);

if ($result && $result2) {
  $row = $result->fetch_assoc();
  $row2 = $result2->fetch_assoc();

  $rowcount2 = $row2['rowCount2'];
  $rowcount = $row['rowCount'];
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}



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



  <style>
    /* --- General Layout / Navbar / Sidebar --- */
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
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
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
      width: 240px;
      z-index: 600;
      box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
    }

    .nav-links {
      background-color: aliceblue;
      border-radius: 20px;
    }

    .active-link {
      background-color: blue;
      color: white;
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


      <!-- Modal for Add Customers-->
      <section class="col-lg-10">
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
                        <input type="submit" name="submit" id="" class="btn btn-success mt-5">
                      </form>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Modal for Update Customers-->
        <div class="container  ">
          <div class="modal" tabindex="-1" id="update_frm">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Update Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="updatemodal.php" method="post">
                    <input type="text" name="Id" required hidden class="form-control" value="<?php echo $Cid; ?>">
                    <div class="form-group">
                      <label for="update_company_name">Company Name</label>
                      <input type="text" name="company_name" id="update_company_name" class="form-control" value="<?php echo $Company_name; ?>">
                    </div>
                    <div class="form-group">
                      <label for="update_cname">Name</label>
                      <input type="text" name="cname" id="update_cname" class="form-control" value="<?php echo $Name; ?>">
                    </div>
                    <div class="form-group">
                      <label for="update_caddress">Address</label>
                      <input type="text" name="caddress" id="update_caddress" required class="form-control" value="<?php echo $Address; ?>">
                    </div>
                    <div class="form-group">
                      <label for="update_cphone">Phone</label>
                      <input type="text" name="cphone" id="update_cphone" required class="form-control" value="<?php echo $Phone; ?>">
                    </div>
                    <div class="form-group">
                      <label for="update_cemail">Email</label>
                      <input type="text" name="cemail" id="update_cemail" class="form-control" value="<?php echo $Email; ?>">
                    </div>
                    <div class="form-group">
                      <label for="update_gstInput">GST_No</label>
                      <input type="text" name="cgst" id="update_gstInput" class="form-control" value="<?php echo $Gst_no; ?>">
                    </div>
                    <input type="submit" value="update" name="Update" id="update_customer" class="btn btn-success mt-5">
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="mb-3 mt-5">
          <input type="text" id="invoice_filter" class="form-control" placeholder="Search Invoice by Customer Name, Invoice No, Date...">
        </div>


        <!-- Table for View Customers-->
        <div class="container mango mt-2">
          <div class="" style="max-height: 600px; overflow-y: auto; border-radius: 40px; background-color: white;">

            <div class="table-responsive-custom">




              <!-- <div class="table-responsive-custom"> -->
              <table class="table" id="invoice_table">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th> Name</th>
                    <th> Name</th>
                    <th> Phone</th>
                    <th> Email</th>
                    <th> Address</th>
                    <th> Gst NO</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="product_tbody" class="viewinvoicetable" style="border: hidden;">


                  <?php
                  require_once('bhavidb.php');
                  $sql = "SELECT * FROM customer";
                  $res = $conn->query($sql);
                  while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr style='border: visible;'>";
                    echo "<td style='border: visible  ;'>" . $row['Id'] . "</td>";
                    echo "<td style='border: visible;'>" . $row['Company_name'] . "</td>";
                    echo "<td style='border: visible;'>" . $row['Name'] . "</td>";
                    echo "<td style='border: visible;'>" . $row['Phone'] . "</td>";
                    echo "<td style='border: visible;'>" . $row['Email'] . "</td>";
                    echo "<td style='border: visible;'>" . $row['Address'] . "</td>";
                    echo "<td style='border: visible;'>" . $row['Gst_no'] . "</td>";

                    // Pass the customer ID as a parameter to the JavaScript function
                    echo "<td>
                  <div class='btn-group'>
                    <a href=\"#\" class=\"update_customer\" data-bs-toggle=\"modal\" data-bs-target=\"#update_frm\" data-id=\"{$row['Id']}\">
                      <button class=\"update-button-cus\"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
                        <path d='M23 4V10H17' stroke='#569C10' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                        <path d='M1 20V14H7' stroke='#569C10' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                        <path d='M3.51 9C4.01717 7.56678 4.87913 6.2854 6.01547 5.27542C7.1518 4.26543 8.52547 3.55976 10.0083 3.22426C11.4911 2.88875 13.0348 2.93434 14.4952 3.35677C15.9556 3.77921 17.2853 4.56471 18.36 5.64L23 10M1 14L5.64 18.36C6.71475 19.4353 8.04437 20.2208 9.50481 20.6432C10.9652 21.0657 12.5089 21.1112 13.9917 20.7757C15.4745 20.4402 16.8482 19.7346 17.9845 18.7246C19.1209 17.7146 19.9828 16.4332 20.49 15' stroke='#569C10' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                      </svg></button>
                    </a>  <br><br>
                    <span style='margin-left: 10px;'></span> 
                    <a href=\"delete.php?Id={$row['Id']}\" onClick=\"return confirm('Are you sure you want to delete?')\">
                      <button class=\"delete-button-cus\"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var addCustomerModal = new bootstrap.Modal(document.getElementById('modal_frm'));
      var updateCustomerModal = new bootstrap.Modal(document.getElementById('update_frm'));

      var addCustomerButton = document.getElementById('add_customer');
      var updateCustomerButtons = document.querySelectorAll('.update_customer');

      addCustomerButton.addEventListener('click', function() {
        addCustomerModal.show();
      });

      updateCustomerButtons.forEach(function(button) {
        button.addEventListener('click', function() {
          var row = button.closest('tr');
          var id = row.cells[0].innerText;
          var company_name = row.cells[1].innerText;
          var name = row.cells[2].innerText;
          var address = row.cells[5].innerText;
          var phone = row.cells[3].innerText;
          var email = row.cells[4].innerText;
          var gstNo = row.cells[6].innerText;

          document.querySelector('#update_frm [name="Id"]').value = id;
          document.querySelector('#update_frm [name="company_name"]').value = company_name;
          document.querySelector('#update_frm [name="cname"]').value = name;
          document.querySelector('#update_frm [name="caddress"]').value = address;
          document.querySelector('#update_frm [name="cphone"]').value = phone;
          document.querySelector('#update_frm [name="cemail"]').value = email;
          document.querySelector('#update_frm [name="cgst"]').value = gstNo;

          updateCustomerModal.show();
        });
      });
    });

    document.getElementById('gstInput').addEventListener('input', function() {
      this.value = this.value.toUpperCase();
    });

    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('update_gstInput').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
      });
    });




    let debounceTimer;
    $('#invoice_filter').on('keyup', function() {
      clearTimeout(debounceTimer);
      var value = $(this).val().toLowerCase();
      debounceTimer = setTimeout(function() {
        $('#invoice_table tbody tr').filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      }, 200);
    });
  </script>











  <?php include('changepass-modal.php'); ?>


</body>

</html>