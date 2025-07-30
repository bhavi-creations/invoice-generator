<?php
ini_set('display_errors', 1); // Keep for now for debugging
ini_set('display_startup_errors', 1); // Keep for now for debugging
error_reporting(E_ALL); // Keep for now for debugging

session_start();
require_once('bhavidb.php'); // Your database connection file

 
// Check if the form was submitted with the "Save" or "Update" button
if (isset($_POST["save"]) || isset($_POST["update"])) {

    // --- 1. GET CUSTOMER DETAILS ---
    $selectedCompanyId = (int)$_POST['company'];
    $sql_customer = "SELECT * FROM `customer` WHERE `Id` = ?";
    $stmt_customer = $conn->prepare($sql_customer);
    $stmt_customer->bind_param("i", $selectedCompanyId);
    $stmt_customer->execute();
    $result_customer = $stmt_customer->get_result();

    if ($row = $result_customer->fetch_assoc()) {
        $company_name = $row['Company_name'];
        $cname = $row['Name'];
        $cphone = $row['Phone'];
        $caddress = $row['Address'];
        $cemail = $row['Email'];
        $cgst = $row['Gst_no'];
    } else {
        die("Error: Company not found. Please select a valid company."); // Stop if company doesn't exist
    }
    $stmt_customer->close();

    // --- 2. CAPTURE & SECURE INVOICE DATA ---
    $invoice_no = htmlspecialchars($_POST["invoice_no"]); // Use htmlspecialchars for output safety
    $invoice_date = date("Y-m-d", strtotime($_POST["invoice_date"]));
    $status = htmlspecialchars($_POST["status"]);
    $payment_details_type = htmlspecialchars($_POST['payment_details']);

    // Retrieve financial values and convert to float/decimal
    $grand_total = floatval($_POST["grand_total"]);
    $gst_percentage = floatval($_POST["gst"]); // Assuming 'gst' select gives percentage
    $gst_total = floatval($_POST["gst_total"]);
    $final_total = floatval($_POST["Final_total"]);
    $advance = floatval($_POST["advance"]);
    $balance = floatval($_POST["balance"]);

    $totalinwords = htmlspecialchars($_POST["words"]);
    $balancewords = htmlspecialchars($_POST["balancewords"]);
    $note = htmlspecialchars($_POST["note"]);

    // Handle stamp and signature paths - MODIFIED TO SAVE ONLY FILENAME
    $stamp_image_filename = !empty($_POST['stamp_image_path']) ? basename(htmlspecialchars($_POST['stamp_image_path'])) : null;
    $signature_image_filename = !empty($_POST['signature_image_path']) ? basename(htmlspecialchars($_POST['signature_image_path'])) : null;

    // --- Define a variable for 'Terms' to be passed by reference ---
    $terms_value = ''; // Assign the empty string to a variable

    if (isset($_POST["save"])) {
        // --- INSERT NEW INVOICE ---
        $sql_invoice = "INSERT INTO invoice (Invoice_no, Invoice_date, Company_name, Cname, Cphone, Caddress, Cmail, Cgst, Customer_id, Final, Gst, Gst_total, Grandtotal, total_paid, balance_due, payment_status, Totalinwords, Terms, Note, advance, balance, balancewords, status, payment_details_type, stamp_image, signature_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_invoice = $conn->prepare($sql_invoice);
        // Corrected bind parameters for INSERT for invoice table: 26 types for 26 variables
        // Type string: ssssssssididddddsssddsssss
        $stmt_invoice->bind_param(
            "ssssssssididddddsssddsssss", // Corrected type string
            $invoice_no,
            $invoice_date,
            $company_name, // Company name from selected customer
            $cname, // Customer name from selected customer
            $cphone,
            $caddress,
            $cemail,
            $cgst,
            $selectedCompanyId, // Customer_id (int)
            $final_total, // Final (float)
            $gst_percentage, // Gst (int) - Note: float from form, but DB is int
            $gst_total, // Gst_total (float)
            $grand_total, // Grandtotal (float)
            $advance, // total_paid (decimal)
            $balance, // balance_due (decimal)
            $status, // payment_status (string)
            $totalinwords,
            $terms_value, // Terms (string)
            $note,
            $advance, // advance (float)
            $balance, // balance (float)
            $balancewords,
            $status, // status (string)
            $payment_details_type,
            $stamp_image_filename, // Now only filename
            $signature_image_filename // Now only filename
        );

        if ($stmt_invoice->execute()) {
            $Sid = $conn->insert_id; // Get the ID of the newly inserted invoice
            // echo "New record created successfully. Last inserted ID is: " . $Sid; // Debugging line

            // --- 4. INSERT SERVICES ---
            if (isset($_POST['Sname']) && is_array($_POST['Sname'])) {
                // Corrected column names: Subtotal -> Totalprice, Discount_percentage -> Discount
                $sql_service = "INSERT INTO service (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_service = $conn->prepare($sql_service);

                foreach ($_POST['Sname'] as $key => $s_name) {
                    $description = htmlspecialchars($_POST['Description'][$key]);
                    $qty = floatval($_POST['Qty'][$key]); // PHP var for Qty
                    $price = floatval($_POST['Price'][$key]); // PHP var for Price
                    $subtotal = floatval($_POST['subtotal'][$key]); // PHP var for Totalprice
                    $discount = floatval($_POST['discount'][$key]); // PHP var for Discount
                    $finaltotal = floatval($_POST['total'][$key]); // PHP var for Finaltotal

                    // Corrected bind_param types for service table: issdidii
                    // Sid (int), Sname (varchar), Description (text), Qty (int), Price (double), Totalprice (double), Discount (int), Finaltotal (int)
                    $stmt_service->bind_param("issdidii",
                        $Sid,
                        $s_name,
                        $description,
                        $qty,
                        $price,
                        $subtotal, // Maps to Totalprice in DB
                        $discount, // Maps to Discount in DB
                        $finaltotal
                    );
                    $stmt_service->execute();
                }
                $stmt_service->close();
            }

            // --- 5. HANDLE FILE UPLOADS ---
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (isset($_FILES['attachments'])) {
                $sql_file = "INSERT INTO invoice_files (Invoice_id, File_path) VALUES (?, ?)";
                $stmt_file = $conn->prepare($sql_file);
                foreach ($_FILES['attachments']['name'] as $key => $name) {
                    if ($_FILES['attachments']['error'][$key] == 0) {
                        $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                        $file_name = $Sid . '-' . uniqid() . '-' . basename($name);
                        $target_file = $upload_dir . $file_name;

                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $stmt_file->bind_param("is", $Sid, $file_name);
                            $stmt_file->execute();
                        }
                    }
                }
                $stmt_file->close();
            }

            echo "<script>
                    alert('Invoice Saved Successfully!');
                    window.location.href='viewinvoices.php';
                  </script>";
            exit();
        } else {
            echo "Invoice Save Failed: " . $stmt_invoice->error;
        }
        $stmt_invoice->close();
    } elseif (isset($_POST["update"])) {
        // --- UPDATE EXISTING INVOICE ---
        $Sid = (int)$_POST['Sid'];

        $sql_invoice_update = "UPDATE invoice SET Invoice_no=?, Invoice_date=?, Company_name=?, Cname=?, Cphone=?, Caddress=?, Cmail=?, Cgst=?, Customer_id=?, Final=?, Gst=?, Gst_total=?, Grandtotal=?, total_paid=?, balance_due=?, payment_status=?, Totalinwords=?, Terms=?, Note=?, advance=?, balance=?, balancewords=?, status=?, payment_details_type=?, stamp_image=?, signature_image=? WHERE Sid=?";

        $stmt_invoice_update = $conn->prepare($sql_invoice_update);
        // Corrected bind parameters for UPDATE for invoice table: 27 types for 27 variables (26 columns + 1 for WHERE clause)
        // Type string: ssssssssididddddsssddsssssi
        $stmt_invoice_update->bind_param(
            "ssssssssididddddsssddsssssi", // Corrected type string
            $invoice_no,
            $invoice_date,
            $company_name,
            $cname,
            $cphone,
            $caddress,
            $cemail,
            $cgst,
            $selectedCompanyId, // Customer_id (int)
            $final_total, // Final (float)
            $gst_percentage, // Gst (int)
            $gst_total, // Gst_total (float)
            $grand_total, // Grandtotal (float)
            $advance, // total_paid (decimal)
            $balance, // balance_due (decimal)
            $status, // payment_status (string)
            $totalinwords,
            $terms_value, // Terms (string)
            $note,
            $advance, // advance (float)
            $balance, // balance (float)
            $balancewords,
            $status, // status (string)
            $payment_details_type,
            $stamp_image_filename, // Now only filename
            $signature_image_filename, // Now only filename
            $Sid // SID for WHERE clause (int)
        );

        if ($stmt_invoice_update->execute()) {
            // Delete existing services for this invoice and re-insert (simpler for updates)
            $conn->query("DELETE FROM service WHERE Sid = $Sid");
            if (isset($_POST['Sname']) && is_array($_POST['Sname'])) {
                // Corrected column names: Subtotal -> Totalprice, Discount_percentage -> Discount
                $sql_service = "INSERT INTO service (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_service = $conn->prepare($sql_service);

                foreach ($_POST['Sname'] as $key => $s_name) {
                    $description = htmlspecialchars($_POST['Description'][$key]);
                    $qty = floatval($_POST['Qty'][$key]);
                    $price = floatval($_POST['Price'][$key]);
                    $subtotal = floatval($_POST['subtotal'][$key]);
                    $discount = floatval($_POST['discount'][$key]);
                    $finaltotal = floatval($_POST['total'][$key]);

                    // Corrected bind_param types for service table: issdidii
                    // Sid (int), Sname (varchar), Description (text), Qty (int), Price (double), Totalprice (double), Discount (int), Finaltotal (int)
                    $stmt_service->bind_param("issdidii",
                        $Sid,
                        $s_name,
                        $description,
                        $qty,
                        $price,
                        $subtotal, // Maps to Totalprice in DB
                        $discount, // Maps to Discount in DB
                        $finaltotal
                    );
                    $stmt_service->execute();
                }
                $stmt_service->close();
            }

            // Handle file deletions
            if (isset($_POST['delete_files']) && is_array($_POST['delete_files'])) {
                foreach ($_POST['delete_files'] as $file_id_to_delete) {
                    // First, get the file path to delete the physical file
                    $stmt_get_file = $conn->prepare("SELECT File_path FROM invoice_files WHERE id = ? AND Invoice_id = ?");
                    $stmt_get_file->bind_param("ii", $file_id_to_delete, $Sid);
                    $stmt_get_file->execute();
                    $result_get_file = $stmt_get_file->get_result();
                    if ($row_file = $result_get_file->fetch_assoc()) {
                        $file_to_unlink = $upload_dir . $row_file['File_path'];
                        if (file_exists($file_to_unlink)) {
                            unlink($file_to_unlink);
                        }
                    }
                    $stmt_get_file->close();

                    // Then delete record from database
                    $stmt_delete_file = $conn->prepare("DELETE FROM invoice_files WHERE id = ? AND Invoice_id = ?");
                    $stmt_delete_file->bind_param("ii", $file_id_to_delete, $Sid);
                    $stmt_delete_file->execute();
                    $stmt_delete_file->close();
                }
            }


            // Handle new file uploads for update
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            if (isset($_FILES['attachments'])) {
                $sql_file_new = "INSERT INTO invoice_files (Invoice_id, File_path) VALUES (?, ?)";
                $stmt_file_new = $conn->prepare($sql_file_new);
                foreach ($_FILES['attachments']['name'] as $key => $name) {
                    if ($_FILES['attachments']['error'][$key] == 0) {
                        $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                        $file_name = $Sid . '-' . uniqid() . '-' . basename($name);
                        $target_file = $upload_dir . $file_name;

                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $stmt_file_new->bind_param("is", $Sid, $file_name);
                            $stmt_file_new->execute();
                        }
                    }
                }
                $stmt_file_new->close();
            }

            echo "<script>
                    alert('Invoice Updated Successfully!');
                    window.location.href='viewinvoices.php';
                  </script>";
            exit();
        } else {
            echo "Invoice Update Failed: " . $stmt_invoice_update->error;
        }
        $stmt_invoice_update->close();
    }
} else {
    // If someone accesses the page directly, redirect them
    header("Location: createinvoice.php");
    exit();
}
$conn->close();
?>