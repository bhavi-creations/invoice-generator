<?php
session_start();
require_once('bhavidb.php'); // Your database connection file

// Check if the form was submitted with the "Save" button
if (isset($_POST["save"])) {

    // --- 1. GET CUSTOMER DETAILS ---
    // This is moved inside the main block to ensure it always runs
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
        die("Error: Company not found."); // Stop if company doesn't exist
    }
    $stmt_customer->close();

    // --- 2. CAPTURE & SECURE INVOICE DATA ---
    $invoice_no = $_POST["invoice_no"];
    $invoice_date = date("Y-m-d", strtotime($_POST["invoice_date"]));
    $status = $_POST["status"];
    $payment_details_type = $_POST["payment_details"]; // New field for payment selection
    $note = $_POST["note"];

    // Financials
    $Gst = (float)$_POST["gst"];
    $Gst_total = (float)$_POST["gst_total"];
    $final_total = (float)$_POST["grand_total"];
    $Grand_total = (float)$_POST["Final_total"];
    $advance = (float)$_POST["advance"];
    $balance = (float)$_POST["balance"];
    $Totalin_word = $_POST["words"];
    $balancewords = $_POST["balancewords"];

    // --- 3. SAVE MAIN INVOICE RECORD (USING PREPARED STATEMENTS) ---
    // Note: I've updated your column names slightly for consistency. See the SQL table structure below.
    $sql_invoice = "INSERT INTO invoice (Invoice_no, Invoice_date, Company_name, Cname, Cphone, Caddress, Cmail, Cgst, Final, Gst, Gst_total, Grandtotal, Totalinwords, Note, advance, balance, balancewords, status, payment_details_type) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_invoice = $conn->prepare($sql_invoice);
    // s=string, d=decimal/double
    $stmt_invoice->bind_param("ssssssssddddssdddss", $invoice_no, $invoice_date, $company_name, $cname, $cphone, $caddress, $cemail, $cgst, $final_total, $Gst, $Gst_total, $Grand_total, $Totalin_word, $note, $advance, $balance, $balancewords, $status, $payment_details_type);

    if ($stmt_invoice->execute()) {
        $Sid = $conn->insert_id; // Get the ID of the invoice just inserted

        // --- 4. SAVE LINE ITEMS ---
        $sql_items = "INSERT INTO service (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_items = $conn->prepare($sql_items);

        if (isset($_POST["Sname"]) && is_array($_POST["Sname"])) {
            for ($i = 0; $i < count($_POST["Sname"]); $i++) {
                $Sname = $_POST["Sname"][$i];
                $Description = $_POST["Description"][$i];
                $Qty = $_POST["Qty"][$i];
                $Price = $_POST["Price"][$i];
                $Subtotal = $_POST["subtotal"][$i];
                $Discount = $_POST["discount"][$i];
                $total = $_POST["total"][$i];

                $stmt_items->bind_param("issidddd", $Sid, $Sname, $Description, $Qty, $Price, $Subtotal, $Discount, $total);
                $stmt_items->execute();
            }
        }
        $stmt_items->close();

        // --- 5. HANDLE FILE UPLOADS ---
        // --- 5. HANDLE FILE UPLOADS ---
        $upload_dir = 'uploads/'; // Create a folder named 'uploads' in your project directory
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (isset($_FILES['attachments'])) {
            $sql_file = "INSERT INTO invoice_files (Invoice_id, File_path) VALUES (?, ?)";
            $stmt_file = $conn->prepare($sql_file);
            foreach ($_FILES['attachments']['name'] as $key => $name) {
                if ($_FILES['attachments']['error'][$key] == 0) {
                    $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                    // This variable holds just the unique filename
                    $file_name = $Sid . '-' . uniqid() . '-' . basename($name);
                    // This variable holds the full path for moving the file
                    $target_file = $upload_dir . $file_name;

                    if (move_uploaded_file($tmp_name, $target_file)) {
                        // We are now saving only the $file_name to the database
                        $stmt_file->bind_param("is", $Sid, $file_name);
                        $stmt_file->execute();
                    }
                }
            }
            $stmt_file->close();
        }

        // --- 6. REDIRECT ON SUCCESS ---
        // Redirect to a view page instead of the print page
        echo "<script>
                alert('Invoice Saved Successfully!');
                window.location.href='viewinvoices.php';
              </script>";
        exit();
    } else {
        // Handle insertion error
        echo "Invoice Save Failed: " . $stmt_invoice->error;
    }
    $stmt_invoice->close();
} else {
    // If someone accesses the page directly, redirect them
    header("Location: createinvoice.php");
    exit();
}
