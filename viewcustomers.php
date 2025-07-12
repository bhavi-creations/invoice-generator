<?php
session_start();
if (!isset($_SESSION['email'])) {
  header('Location:index.php');
  exit();
}

require_once('bhavidb.php');

// Count stats
$sql = "SELECT COUNT(*) AS rowCount FROM customer";
$sql2 = "SELECT COUNT(*) AS rowCount2 FROM invoice";

$result = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql2);

$rowcount = $result->fetch_assoc()['rowCount'] ?? 0;
$rowcount2 = $result2->fetch_assoc()['rowCount2'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<?php include('header.php'); ?>

<body>
  <div class="container-fluid">
    <div class="row">

      <?php include('sidebar.php'); ?>

      <section class="col-lg-10">

        <!-- Add Customer Modal -->
        <div class="container text-center mt-4">
          <div class="row">
            <div class="col-7">
              <div class="modal" tabindex="-1" id="modal_frm">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Add Customer</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="modalform.php" method="post">
                        <div class="mb-2">
                          <label>Firm Name</label>
                          <input type="text" name="company_name" class="form-control">
                        </div>
                        <div class="mb-2">
                          <label>Name</label>
                          <input type="text" name="cname" class="form-control">
                        </div>
                        <div class="mb-2">
                          <label>Address</label>
                          <input type="text" name="caddress" required class="form-control">
                        </div>
                        <div class="mb-2">
                          <label>Phone</label>
                          <input type="tel" name="cphone" required class="form-control">
                        </div>
                        <div class="mb-2">
                          <label>Email</label>
                          <input type="email" name="cemail" class="form-control">
                        </div>
                        <div class="mb-2">
                          <label>GST No</label>
                          <input type="text" name="cgst" id="gstInput" class="form-control">
                        </div>
                        <input type="submit" name="submit" class="btn btn-success mt-3">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Search -->
        <div class="mb-3 mt-5">
          <input type="text" id="invoice_filter" class="form-control" placeholder="Search by Firm, Name, Phone...">
        </div>

        <!-- View Customers Table -->
        <div class="container mango mt-2">
          <div style="max-height: 600px; overflow-y: auto; border-radius: 40px; background-color: white;">
            <div class="table-responsive-custom">

              <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['message'];
                                                  unset($_SESSION['message']); ?></div>
              <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error'];
                                                unset($_SESSION['error']); ?></div>
              <?php endif; ?>

              <table class="table" id="invoice_table">
                <thead>
                  <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Firm</th>
                    <th class="text-center">Client Name</th>
                    <th class="text-center">Phone</th>
                    <th class="text-center">Actions</th>
                  </tr>
                </thead>
                <tbody id="product_tbody" class="viewinvoicetable">
                  <?php
                  $res = $conn->query("SELECT * FROM customer");
                  while ($row = $res->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='text-center'>{$row['Id']}</td>";
                    echo "<td>{$row['Company_name']}</td>";
                    echo "<td>{$row['Name']}</td>";
                    echo "<td>{$row['Phone']}</td>";
                   echo "<td class='text-center'>
        <div class='d-flex justify-content-center gap-2'>

            <!-- View -->
            <a href='view_customer.php?id={$row['Id']}' class='btn btn-outline-primary btn-sm' title='View'>
                <i class='bi bi-eye-fill'></i>
            </a>

            <!-- Edit -->
            <a href='edit_customer.php?id={$row['Id']}' class='btn btn-outline-success btn-sm' title='Edit'>
                <i class='bi bi-pencil-square'></i>
            </a>

            <!-- Delete -->
            <form method='POST' action='delete.php' onsubmit=\"return confirm('Are you sure you want to delete this customer?');\">
                <input type='hidden' name='Id' value='{$row['Id']}'>
                <button type='submit' class='btn btn-outline-danger btn-sm' title='Delete'>
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
        </div>
      </section>

    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Filter logic
    $('#invoice_filter').on('keyup', function() {
      var value = $(this).val().toLowerCase();
      $('#invoice_table tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().includes(value));
      });
    });

    // Uppercase GST field
    document.getElementById('gstInput').addEventListener('input', function() {
      this.value = this.value.toUpperCase();
    });
  </script>

</body>

</html>