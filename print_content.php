<?php
 
// Function to convert numbers to words (Indian Rupees format) - COPIED HERE
function numberToWordsINR($num) {
    $num = (float)str_replace(',', '', $num); // Ensure float and remove commas if any

    if ($num == 0) {
        return "Zero Rupees Only.";
    }

    $ones = array(
        0 => "", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 5 => "Five",
        6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine", 10 => "Ten",
        11 => "Eleven", 12 => "Twelve", 13 => "Thirteen", 14 => "Fourteen",
        15 => "Fifteen", 16 => "Sixteen", 17 => "Seventeen", 18 => "Eighteen",
        19 => "Nineteen"
    );
    $tens = array(
        2 => "Twenty", 3 => "Thirty", 4 => "Forty", 5 => "Fifty",
        6 => "Sixty", 7 => "Seventy", 8 => "Eighty", 9 => "Ninety"
    );

    $num_arr = explode('.', number_format($num, 2, '.', '')); // Ensure 2 decimal places for accurate paisa
    $integer_part = (int)$num_arr[0];
    $decimal_part = isset($num_arr[1]) ? (int)$num_arr[1] : 0;

    $in_words = "";

    // Process integer part
    if ($integer_part > 0) {
        $crore = floor($integer_part / 10000000);
        $integer_part %= 10000000;
        if ($crore > 0) {
            $in_words .= numberToWordsINRRecursive($crore, $ones, $tens) . " Crore ";
        }

        $lakh = floor($integer_part / 100000);
        $integer_part %= 100000;
        if ($lakh > 0) {
            $in_words .= numberToWordsINRRecursive($lakh, $ones, $tens) . " Lakh ";
        }

        $thousand = floor($integer_part / 1000);
        $integer_part %= 1000;
        if ($thousand > 0) {
            $in_words .= numberToWordsINRRecursive($thousand, $ones, $tens) . " Thousand ";
        }

        $hundred = floor($integer_part / 100);
        $integer_part %= 100;
        if ($hundred > 0) {
            $in_words .= $ones[$hundred] . " Hundred ";
        }

        if ($integer_part > 0) {
            if ($hundred > 0 || $thousand > 0 || $lakh > 0 || $crore > 0) {
                $in_words .= "And "; // As per Indian convention
            }
            $in_words .= numberToWordsINRRecursive($integer_part, $ones, $tens);
        }
    }

    $in_words = trim($in_words);
    $final_string = ($in_words == "" && $decimal_part == 0 ? "Zero" : $in_words) . " Rupees";

    if ($decimal_part > 0) {
        $final_string .= " And " . numberToWordsINRRecursive($decimal_part, $ones, $tens) . " Paisa";
    }

    $final_string .= " Only.";
    return $final_string;
}

// Helper function for recursive calls (COPIED HERE)
function numberToWordsINRRecursive($num, $ones, $tens) {
    if ($num < 20) {
        return $ones[$num];
    } else {
        $word = $tens[floor($num / 10)];
        if (($num % 10) > 0) {
            $word .= " " . $ones[$num % 10];
        }
        return $word;
    }
}

 
 

// Calculate Grand Total in words for invoice
$grand_total_in_words_invoice = numberToWordsINR($invoice['Grandtotal']);

// Calculate Balance in words for invoice
$balance_in_words_invoice = numberToWordsINR($invoice['balance']);


?>
<div class="invoice-container">

     <img src="<?php echo $base_url; ?>/img/Bhavi-Logo-2.png" alt="Bhavi Creations Logo" class="mx-auto d-block img-fluid pt-3" style="max-height: 100px;">

     <div class="<?php echo $is_pdf ? '' : 'row container pt-5 ps-5 mb-5'; ?>" style="<?php echo $is_pdf ? 'display: flex; justify-content: space-between; margin-bottom: 30px;' : ''; ?>">
          <div class="<?php echo $is_pdf ? '' : 'col-6'; ?>" style="<?php echo $is_pdf ? 'width: 48%;' : ''; ?>">

               <h5><strong>Date:</strong> <?php echo date("d-m-Y", strtotime($invoice['Invoice_date'])); ?></h5>
          </div>
          <div class="col-6 text-end">
               <h5><strong>Invoice Number:</strong> BHAVI_KKD_2024_<?php echo htmlspecialchars($invoice['Invoice_no']); ?></h5>
          </div>
     </div>

     <div class="container ps-5 mb-5">
          <div class="row">
               <div class="col-6">
                    <h4 class="pb-2"><strong>From:</strong></h4>
                    <address>
                         <h5>Bhavi Creations Pvt Ltd</h5>
                         <h6>Plot no28, H No70, 17-28, RTO Office Rd,</h6>
                         <h6>RangaRaoNagar, Kakinada, AndhraPradesh 533003.</h6>
                         <h6><strong>Phone:</strong> 9642343434</h6>
                         <h6><strong>Email:</strong> admin@bhavicreations.com</h6>
                         <h6><strong>GSTIN:</strong> 37AAKCB6960H1ZB</h6>
                    </address>
               </div>
               <div class="col-6 text-end">
                    <h4 class="pb-2"><strong>To (Bill To):</strong></h4>
                    <address>
                         <h5><?php echo htmlspecialchars($invoice['Company_name']); ?></h5>
                         <h6><?php echo htmlspecialchars($invoice['Cname']); ?></h6>
                         <h6><?php echo htmlspecialchars($invoice['Caddress']); ?></h6>
                         <h6><strong>Phone:</strong> <?php echo htmlspecialchars($invoice['Cphone']); ?></h6>
                         <h6><strong>Email:</strong> <?php echo htmlspecialchars($invoice['Cmail']); ?></h6>
                         <h6><strong>GSTIN:</strong> <?php echo htmlspecialchars($invoice['Cgst']); ?></h6>
                    </address>
               </div>
          </div>
     </div>

     <div class="billing px-4">
          <div class="table-responsive">
               <table class="table table-bordered">
                    <thead style="background-color: #e9ecef;">
                         <tr>
                              <th>S.no</th>
                              <th>Services</th>
                              <th>Description</th>
                              <th>Qty</th>
                              <th>Price/Unit</th>
                              <th>Sub Total</th>
                              <th>Disc %</th>
                              <th>Total</th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php $counter = 1;
                           foreach ($services as $service): ?>
                              <tr>
                                   <td><?php echo sprintf('%02d', $counter++); ?></td>
                                   <td><?php echo htmlspecialchars($service['Sname']); ?></td>
                                   <td><?php echo htmlspecialchars($service['Description']); ?></td>
                                   <td><?php echo htmlspecialchars($service['Qty']); ?></td>
                                   <td><?php echo number_format((float)$service['Price'], 2); ?></td>
                                   <td><?php echo number_format((float)$service['Totalprice'], 2); ?></td>
                                   <td><?php echo htmlspecialchars($service['Discount']); ?></td>
                                   <td><?php echo number_format((float)$service['Finaltotal'], 2); ?></td>
                              </tr>
                         <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                         <tr>
                              <td colspan="6" rowspan="5" style="text-align: left; vertical-align: bottom; border:none;">
                                   <p class="mb-2"><strong>Total in words:</strong><br><?php echo htmlspecialchars($grand_total_in_words_invoice); ?></p>
                                   <p><strong>Balance in words:</strong><br><?php echo htmlspecialchars($balance_in_words_invoice); ?></p>
                              </td>
                              <td style="text-align: right;"><strong>Subtotal</strong></td>
                              <td><?php echo number_format((float)$invoice['Final'], 2); ?></td>
                         </tr>
                         <tr>
                              <td style="text-align: right;"><strong>GST %</strong></td>
                              <td><?php echo htmlspecialchars($invoice['Gst']); ?></td>
                         </tr>
                         <tr>
                              <td style="text-align: right;"><strong>GST Total</strong></td>
                              <td><?php echo number_format((float)$invoice['Gst_total'], 2); ?></td>
                         </tr>
                         <tr>
                              <td style="text-align: right;" class="bg-light"><strong>Grand Total</strong></td>
                              <td class="bg-light"><strong><?php echo number_format((float)$invoice['Grandtotal'], 2); ?></strong></td>
                         </tr>
                         <tr>
                              <td style="text-align: right;"><strong>Advance</strong></td>
                              <td><?php echo number_format((float)$invoice['advance'], 2); ?></td>
                         </tr>
                         <tr>
                              <td colspan="6" style="border:none;"></td>
                              <td style="text-align: right;" class="bg-light"><strong>Balance</strong></td>
                              <td class="bg-light"><strong><?php echo number_format((float)$invoice['balance'], 2); ?></strong></td>
                         </tr>
                    </tfoot>
               </table>
          </div>
     </div>

     <div class="container mt-4">
          <div class="row">
               <div class="col-lg-6">
                    <p><strong>Note:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($invoice['Note'])); ?></p>
               </div>
               <div class="col-lg-6 no-print">
                    <?php if (!empty($files)): ?>
                         <p><strong>Attachments:</strong></p>
                         <ul>
                              <?php foreach ($files as $file): ?>
                                   <li>
                                        <a href="uploads/attachments/<?php echo htmlspecialchars($file['File_path']); ?>" target="_blank">
                                             <?php echo htmlspecialchars(substr($file['File_path'], strpos($file['File_path'], '-', strpos($file['File_path'], '-') + 1) + 1)); ?>
                                        </a>
                                   </li>
                              <?php endforeach; ?>
                         </ul>
                    <?php endif; ?>
               </div>

          </div>
     </div>

     <hr class="my-5">

     <div class="container pt-3 mb-4 payment-details">
          <div class="row payment-row">

               <?php if ($invoice['payment_details_type'] == 'office'): ?>
                    <div class="col-md-6">
                         <h5 class="mb-3"><strong>Scan to Pay:</strong></h5>
                         <img src="<?php echo $base_url; ?>/img/qrcode.jpg" alt="Office QR Code" style="height:120px; width:120px;">
                    </div>
                    <div class="col-md-6">
                         <h5 class="mb-3"><strong>Payment details</strong></h5>
                         <h6><strong>Bank Name:</strong> HDFC Bank, Kakinada</h6>
                         <h6><strong>Account Name:</strong> Bhavi Creations Private Limited</h6>
                         <h6><strong>Account No.:</strong> 59213749999999</h6>
                         <h6><strong>IFSC:</strong> HDFC0000426</h6>
                    </div>
               <?php elseif ($invoice['payment_details_type'] == 'personal'): ?>
                    <div class="col-md-6">
                         <h5 class="mb-3"><strong>Scan to Pay:</strong></h5>
                         <img src="<?php echo $base_url; ?>/img/personal_qrcode.jpg" alt="Personal QR Code" style="height:120px; width:120px;">
                    </div>
                    <div class="col-md-6">
                         <h5 class="mb-3"><strong>Payment details</strong></h5>
                         <h6><strong>Bank Name:</strong> State Bank Of India</h6>
                         <h6><strong>Account Name:</strong> Chalikonda Naga Phaneendra Naidu</h6>
                         <h6><strong>Account No.:</strong> 20256178992</h6>
                         <h6><strong>IFSC:</strong> SBIN00001917</h6>
                         <h6><strong>Google Pay, Phone Pay, Paytm:</strong> 8686394079</h6>
                    </div>
               <?php endif; ?>

          </div>

          <div class="row justify-content-end me-5 mt-5">
               <div class="col-auto text-center d-flex flex-column align-items-center me-5 mt-5">
                    <?php if (!empty($invoice['stamp_image'])): ?>
                         <img src="<?php echo $base_url; ?>/uploads/<?php echo htmlspecialchars($invoice['stamp_image']); ?>"
                              alt="Company Stamp"
                              class="img-fluid mb-2"
                              style="max-height: 200px; max-width: 200px;">
                    <?php endif; ?>

                    <?php if (!empty($invoice['signature_image'])): ?>
                         <img src="<?php echo $base_url; ?>/uploads/<?php echo htmlspecialchars($invoice['signature_image']); ?>"
                              alt="Authorized Signature"
                              class="img-fluid"
                              style="max-height: 100px; max-width: 100px;">
                         <p class="mb-2"><strong>Authorized Signature:</strong></p>

                    <?php endif; ?>
               </div>
          </div>


     </div>

</div>