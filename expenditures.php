<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}
require_once('bhavidb.php');

// Generate invoice ID
function getInvoiceId()
{
    include('bhavidb.php');
    $query = "SELECT Invoice_no FROM invoice ORDER BY Invoice_no DESC LIMIT 1";
    $result = $conn->query($query);
    $next = $result && $result->num_rows ? $result->fetch_assoc()['Invoice_no'] + 1 : 1;
    return sprintf('%04d', $next);
}
$invoiceNumber = getInvoiceId();
?>

<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>
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



    .active-link {
        background-color: blue;
        color: white;
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
</style>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>
            <section class="col-lg-10">
                <div class="container mt-4">
                    <form action="exp_formprocess.php" method="POST" class="p-4">
                        <div class="text-center mb-4">
                            <img src="img/Bhavi-Logo-2.png" style="max-height: 20%; max-width: 20%;">
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-8">
                                <h5><strong>Expenditure</strong></h5>
                                <h6><strong>Date:</strong>
                                    <input type="date" name="exp_date" class="form-input" value="<?= date('Y-m-d'); ?>" required>
                                </h6>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead table-light">
                                    <tr>
                                        <th></th>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Mode of Payment</th>
                                        <th>Amount Paid</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="product_tbody">
                                    <tr>
                                        <td class="text-center">
                                            <button type="button" id="btn-add-row" class="btn btn-sm btn-outline-secondary">+</button>
                                        </td>
                                        <td class="serial-number text-center">01</td>
                                        <td><input type="text" name="exp_name[]" class="form-control" placeholder="Enter Name"></td>
                                        <td><textarea name="exp_description[]" class="form-control" rows="1"></textarea></td>
                                        <td>
                                            <select name="mode_payment[]" class="form-control mode-payment">
                                                <option value="">Select</option>
                                                <option value="Google-pay">Google-Pay</option>
                                                <option value="Phone-pay">Phone-Pay</option>
                                                <option value="Paytm">Paytm</option>
                                                <option value="Bank-personal">Bank (personal)</option>
                                                <option value="Bank-professional">Bank (professional)</option>
                                                <option value="cheque">Cheque</option>
                                                <option value="cash">Cash</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="amount[]" class="form-control total" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="btn-row-remove">X</button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end">Total Amount</td>
                                        <td><input type="text" name="total_amount_exp" id="grand_total" class="form-control" required></td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            <input name="exp_words" type="text" class="form-control" readonly id="balancewords">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Notes -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <textarea name="note" class="form-control" placeholder="Note" rows="4"></textarea>
                            </div>
                            <div class="col-md-2 mt-3 mt-md-0">
                                <button type="submit" name="submit" class="btn btn-primary w-100">Save</button>
                            </div>
                        </div>

                        <!-- Payment Type Toggle -->
                        <div class="container pt-5 mb-4">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_details" id="office_details" value="office" checked>
                                        <label class="form-check-label" for="office_details"><strong>With GST Payment</strong></label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_details" id="personal_details" value="personal">
                                        <label class="form-check-label" for="personal_details"><strong>Use Personal Payment Details</strong></label>
                                    </div>
                                </div>

                                <!-- Office Payment -->
                                <div id="office_payment_section" class="col-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <p><strong>Scan to Pay:</strong></p>
                                            <img src="img/qrcode.jpg" width="120" height="120">
                                        </div>
                                        <div class="col-lg-6">
                                            <p><strong>Bank Name:</strong> HDFC Bank, Kakinada</p>
                                            <p><strong>Account:</strong> Bhavi Creations Private Limited</p>
                                            <p><strong>Account No.:</strong> 59213749999999</p>
                                            <p><strong>IFSC:</strong> HDFC0000426</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Payment -->
                                <div id="personal_payment_section" class="col-12 d-none">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <p><strong>Scan to Pay:</strong></p>
                                            <img src="img/personal_qrcode.jpg" width="120" height="120">
                                        </div>
                                        <div class="col-lg-6">
                                            <p><strong>Bank Name:</strong> State Bank Of India</p>
                                            <p><strong>Account:</strong> Chalikonda Naga Phaneendra Naidu</p>
                                            <p><strong>Account No.:</strong> 20256178992</p>
                                            <p><strong>IFSC:</strong> SBIN00001917</p>
                                            <p><strong>Google Pay, Phone Pay, Paytm:</strong> 8686394079</p>
                                             
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </section>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function amountToWords(num) {
            // Skipping logic for brevity (use existing one)
            return num + " only";
        }

        function updateSerialNumbers() {
            $('.serial-number').each((i, el) => $(el).text((i + 1).toString().padStart(2, '0')));
        }

        function calculateTotal() {
            let total = 0;
            $('input[name="amount[]"]').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#grand_total').val(total.toFixed(2));
            $('#balancewords').val(amountToWords(total));
        }

        $(document).ready(function() {
            // Add row
            $('#btn-add-row').on('click', function() {
                const row = $('#product_tbody tr:first').clone();
                row.find('input, textarea').val('');
                $('#product_tbody').append(row);
                updateSerialNumbers();
            });

            // Remove row
            $(document).on('click', '#btn-row-remove', function() {
                if ($('#product_tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                    updateSerialNumbers();
                    calculateTotal();
                }
            });

            // On amount input
            $(document).on('input', 'input[name="amount[]"]', calculateTotal);
            calculateTotal();

            // Toggle payment sections
            function toggleSections() {
                const isPersonal = $('#personal_details').is(':checked');
                $('#personal_payment_section').toggleClass('d-none', !isPersonal);
                $('#office_payment_section').toggleClass('d-none', isPersonal);
                $('.mode-payment').prop('disabled', !isPersonal).val(isPersonal ? '' : '');
            }
            $('input[name="payment_details"]').on('change', toggleSections);
            toggleSections(); // Initialize on load
        });
    </script>
</body>

</html>