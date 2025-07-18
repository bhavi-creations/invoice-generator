<?php



error_reporting(E_ALL);
ini_set('display_errors', 1);

include("bhavidb.php");

// Get customer name from query string
if (!isset($_GET['name'])) {
    die("Customer name not provided.");
}

$cname = urldecode($_GET['name']);

// Fetch quotations for the customer
$stmt = $conn->prepare("SELECT * FROM quotation WHERE Cname = ?");
$stmt->bind_param("s", $cname);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">


<?php include('header.php'); ?>

<body>
    <div class="container-fluid">
        <div class="row">


            <?php include('sidebar.php'); ?>


            <section class="col-lg-10">

                <div class="content mt-5">
                    <div class="container-box">
                        <h3 class="mb-4">Quotations for <?= htmlspecialchars($cname) ?></h3>
                        <?php if (isset($_GET['status'])): ?>
                            <div class="alert <?= $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger' ?>">
                                <?= $_GET['status'] === 'success' ? 'Quotation deleted successfully.' : 'Failed to delete quotation.' ?>
                            </div>
                        <?php endif; ?>

                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Quotation No</th>
                                    <th>Date</th>
                                    <th>Company</th>
                                    <th>Final Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    $sno = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>{$sno}</td>";
                                        echo "<td>{$row['quotation_no']}</td>";
                                        echo "<td>{$row['quotation_date']}</td>";
                                        echo "<td>{$row['Company_name']}</td>";
                                        echo "<td>₹ " . number_format($row['Grandtotal'], 2) . "</td>";
                                        echo "<td style='border: hidden;'> 
                                                <div class='btn-group'>

                                                    <!-- Convert Button -->
                                                   <!--   <form method='POST' action='convert.php' style='display:inline;'>
                                                        <input type='hidden' name='convert_id' value='" . $row['Sid'] . "'>
                                                        <button type='submit' class='' style='border:none;'>
                                                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                                                                <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                                                            </svg> Convert
                                                        </button>
                                                    </form> -->

                                                    <span style='margin-left: 10px;'></span>

                                                    <!-- Edit Button -->
                                                    <a href='edit_quotation.php?Sid=" . $row['Sid'] . "' class='bg_color_icon' style='border:none; display:inline-block;'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                                                            <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                                                            <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
                                                        </svg>
                                                    </a>

                                                    


                                                    <span style='margin-left: 10px;'></span>

                                                    <!-- View Button -->
                                                    <a target=blank href='quprint.php?Sid=" . $row['Sid'] . "' style='display:inline-block;'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='black' class='bi bi-eye-fill' viewBox='0 0 16 16'>
                                                            <path d='M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0'/>
                                                            <path d='M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7'/>
                                                        </svg>
                                                    </a>

                                                    <span style='margin-left: 10px;'></span>

                                                    <!-- Delete Button -->
                                                    <form method='POST' action='delete_quotation.php' onsubmit=\"return confirm('Are you sure you want to delete this record?');\" style='display:inline;'>
                                                        <input type='hidden' name='delete_id' value='" . $row['quotation_no'] . "'>
                                                        <input type='hidden' name='cname' value='" . htmlspecialchars($cname, ENT_QUOTES) . "'>

                                                        <button type='submit' class='' style='border:none;background:none;'>
                                                            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
                                                                <path d='M3 6H5H21' stroke='#C01818' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                                                <path d='M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6' stroke='#C01818' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                                                <path d='M10 11V17' stroke='#C01818' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                                                <path d='M14 11V17' stroke='#C01818' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>";


                                        echo "</tr>";
                                        $sno++;
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No quotations found for this customer.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>