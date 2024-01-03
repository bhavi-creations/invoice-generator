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
                            <a class="nav-link active text-primary" href="viewcustomers.php">VIEW CUSTOMERS</a>
                        </li>
                        <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="customized_edits.php">CUSTOMIZED EDITS</a>
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
            <li class="nav-item pe-5">
                            <a class="nav-link text-dark" href="customized_edits.php">CUSTOMIZED EDITS</a>
                        </li>
          </ul>
        </div>
      </div>
    </nav>

  </header>

  <!-- Modal for Add Customers-->

  <div class="container text-center mt-4 ">
    <div class="row">
      <div class="col-6">
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
                    <input type="text" name="company_name"  class="form-control">
                  </div>

                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="cname"  class="form-control">
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
                    <input type="email" name="cemail"  class="form-control">
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
     
        <p class="float-end"><a href="#" class="btn btn-success" id="add_customer">Add Customer</a></p>
      </div>

      <!-- <div class="col-6">
      <input type="text" class="form-control" placeholder="Search..." id="searchInput" style="width:200px;">
      </div> -->


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
                <input type="text" name="cname" id="update_cname"  class="form-control" value="<?php echo $Name; ?>">
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
                <input type="text" name="cemail" id="update_cemail"  class="form-control" value="<?php echo $Email; ?>">
              </div>
              <div class="form-group">
                <label for="update_gstInput">GST_No</label>
                <input type="text" name="cgst" id="update_gstInput"  class="form-control" value="<?php echo $Gst_no; ?>">
              </div>
              <input type="submit" value="update" name="Update" id="update_customer" class="btn btn-success mt-5">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Table for View Customers-->
  <div class="container   mt-2">
    <div class="table-responsive"  style="max-height:525px; overflow-y: auto;">
      <table class="table table-bordered viewinvoicetable">
        <thead style="position: sticky; top: 0;">
          <tr style=" background-color: #f2f2f2;">
            <th> Customer Id </th>
            <th> Company Name </th>
            <th> Customer Name </th>
            <th> Customer Phone </th>
            <th> Customer Email </th>
            <th> Customer Address </th>
            <th> Customer Gst NO </th>
            <th> Actions </th>
          </tr>
        </thead>
        <tbody id="product_tbody" class="viewinvoicetable">
          <?php
          require_once('bhavidb.php');
          $sql = "SELECT * FROM customer";
          $res = $conn->query($sql);
          while ($row = mysqli_fetch_assoc($res)) {
            echo "<tr>";
            echo "<td>" . $row['Id'] . "</td>";
            echo "<td>" . $row['Company_name'] . "</td>";
            echo "<td>" . $row['Name'] . "</td>";
            echo "<td>" . $row['Phone'] . "</td>";
            echo "<td>" . $row['Email'] . "</td>";
            echo "<td>" . $row['Address'] . "</td>";
            echo "<td>" . $row['Gst_no'] . "</td>";

            // Pass the customer ID as a parameter to the JavaScript function
            echo "<td>
            <a href=\"#\" class=\"update_customer\" data-bs-toggle=\"modal\" data-bs-target=\"#update_frm\" data-id=\"{$row['Id']}\">
            <button  class=\"update-button\">Update</button>
            </a>  <br><br> 
             <a href=\"delete.php?Id={$row['Id']}\" onClick=\"return confirm('Are you sure you want to delete?')\">
             <button class=\"delete-button\">Delete</button>
             </a>
             </td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
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
  </script>


</body>

</html>