<?php
session_start();
require_once('bhavidb.php');

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

// Check if the form was submitted with the 'save' button
if (isset($_POST['save'])) {

    // --- 1. GET CUSTOMER DETAILS ---
    $company_id = (int)$_POST['company'];
    $stmt_customer = $conn->prepare("SELECT * FROM `customer` WHERE `Id` = ?");
    $stmt_customer->bind_param("i", $company_id);
    $stmt_customer->execute();
    $result_customer = $stmt_customer->get_result();
    if ($customer_row = $result_customer->fetch_assoc()) {
        $company_name = $customer_row['Company_name'];
        $cname = $customer_row['Name'];
        $cphone = $customer_row['Phone'];
        $caddress = $customer_row['Address'];
        $cemail = $customer_row['Email'];
        $cgst = $customer_row['Gst_no'];
    } else {
        die("Error: Selected customer not found.");
    }
    $stmt_customer->close();

    // --- 2. CAPTURE FORM DATA ---
    $quotation_no_full = $_POST['quotation_no'];
    $quotation_no = substr($quotation_no_full, strrpos($quotation_no_full, '_') + 1);
    $quote_date = $_POST['quote_date'];
    $note = $_POST['note'];
    $payment_details_type = $_POST['payment_details'];

    // Capture financial values
    $final_total = (float)($_POST["grand_total"] ?? 0);
    $gst_percentage = (float)($_POST["gst"] ?? 0);
    $gst_total = (float)($_POST["gst_total"] ?? 0);
    $grand_total_final = (float)($_POST["Final_total"] ?? 0);
    $advance = (float)($_POST["advance"] ?? 0);
    $balance = (float)($_POST["balance"] ?? 0);
    $total_in_words = $_POST['words'] ?? '';
    $balance_words = $_POST['balancewords'] ?? '';

    // --- 3. SAVE THE MAIN QUOTATION (CORRECTED) ---
    // FIXED: Removed the 'status' column from the query to match your database table
    $sql_quote = "INSERT INTO quotation (quotation_no, quotation_date, Company_name, Cname, Cphone, Caddress, Cmail, Cgst, Final, Gst, Gst_total, Grandtotal, Totalinwords, Note, advance, balance, balancewords, payment_details_type) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_quote = $conn->prepare($sql_quote);
    // FIXED: Bind all 18 parameters to match the updated query
    $stmt_quote->bind_param(
        "ssssssssiddsdssdss",
        $quotation_no,
        $quote_date,
        $company_name,
        $cname,
        $cphone,
        $caddress,
        $cemail,
        $cgst,
        $final_total,
        $gst_percentage,
        $gst_total,
        $grand_total_final,
        $total_in_words,
        $note,
        $advance,
        $balance,
        $balance_words,
        $payment_details_type
    );

    if ($stmt_quote->execute()) {
        $quote_id = $conn->insert_id;

        // --- 4. SAVE SERVICE ITEMS ---
        $sql_items = "INSERT INTO quservice (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_items = $conn->prepare($sql_items);
        if (isset($_POST["Sname"]) && is_array($_POST["Sname"])) {
            for ($i = 0; $i < count($_POST["Sname"]); $i++) {
                $stmt_items->bind_param("issisddd", $quote_id, $_POST["Sname"][$i], $_POST["Description"][$i], $_POST["Qty"][$i], $_POST["Price"][$i], $_POST["subtotal"][$i], $_POST["discount"][$i], $_POST["total"][$i]);
                $stmt_items->execute();
            }
        }
        $stmt_items->close();

        // --- 5. HANDLE FILE UPLOADS ---
        if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $upload_dir = 'uploads/';
            $stmt_file = $conn->prepare("INSERT INTO quote_files (quote_id, file_path) VALUES (?, ?)");
            foreach ($_FILES['attachments']['name'] as $key => $name) {
                if ($_FILES['attachments']['error'][$key] == 0) {
                    $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                    $file_name_only = $quote_id . '-' . uniqid() . '-' . basename($name);
                    $destination_path = $upload_dir . $file_name_only;
                    if (move_uploaded_file($tmp_name, $destination_path)) {
                        $stmt_file->bind_param("is", $quote_id, $file_name_only);
                        $stmt_file->execute();
                    }
                }
            }
            $stmt_file->close();
        }

        // --- 6. REDIRECT ON SUCCESS ---
        echo "<script>
                alert('Quotation Saved Successfully!');
                window.location.href='viewquotes.php';
              </script>";
        exit();

    } else {
        echo "Error saving quotation: " . $stmt_quote->error;
    }
    $stmt_quote->close();
    
} else {
    header("Location: quotation.php");
    exit();
}
?><?php
session_start();
require_once('bhavidb.php');

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

// Check if the form was submitted with the 'save' button
if (isset($_POST['save'])) {

    // --- 1. GET CUSTOMER DETAILS ---
    $company_id = (int)$_POST['company'];
    $stmt_customer = $conn->prepare("SELECT * FROM `customer` WHERE `Id` = ?");
    $stmt_customer->bind_param("i", $company_id);
    $stmt_customer->execute();
    $result_customer = $stmt_customer->get_result();
    if ($customer_row = $result_customer->fetch_assoc()) {
        $company_name = $customer_row['Company_name'];
        $cname = $customer_row['Name'];
        $cphone = $customer_row['Phone'];
        $caddress = $customer_row['Address'];
        $cemail = $customer_row['Email'];
        $cgst = $customer_row['Gst_no'];
    } else {
        die("Error: Selected customer not found.");
    }
    $stmt_customer->close();

    // --- 2. CAPTURE FORM DATA ---
    $quotation_no_full = $_POST['quotation_no'];
    $quotation_no = substr($quotation_no_full, strrpos($quotation_no_full, '_') + 1);
    $quote_date = $_POST['quote_date'];
    $note = $_POST['note'];
    $payment_details_type = $_POST['payment_details'];

    // Capture financial values
    $final_total = (float)($_POST["grand_total"] ?? 0);
    $gst_percentage = (float)($_POST["gst"] ?? 0);
    $gst_total = (float)($_POST["gst_total"] ?? 0);
    $grand_total_final = (float)($_POST["Final_total"] ?? 0);
    $advance = (float)($_POST["advance"] ?? 0);
    $balance = (float)($_POST["balance"] ?? 0);
    $total_in_words = $_POST['words'] ?? '';
    $balance_words = $_POST['balancewords'] ?? '';

    // --- 3. SAVE THE MAIN QUOTATION (CORRECTED) ---
    // FIXED: Removed the 'status' column from the query to match your database table
    $sql_quote = "INSERT INTO quotation (quotation_no, quotation_date, Company_name, Cname, Cphone, Caddress, Cmail, Cgst, Final, Gst, Gst_total, Grandtotal, Totalinwords, Note, advance, balance, balancewords, payment_details_type) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_quote = $conn->prepare($sql_quote);
    // FIXED: Bind all 18 parameters to match the updated query
    $stmt_quote->bind_param(
        "ssssssssiddsdssdss",
        $quotation_no,
        $quote_date,
        $company_name,
        $cname,
        $cphone,
        $caddress,
        $cemail,
        $cgst,
        $final_total,
        $gst_percentage,
        $gst_total,
        $grand_total_final,
        $total_in_words,
        $note,
        $advance,
        $balance,
        $balance_words,
        $payment_details_type
    );

    if ($stmt_quote->execute()) {
        $quote_id = $conn->insert_id;

        // --- 4. SAVE SERVICE ITEMS ---
        $sql_items = "INSERT INTO quservice (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_items = $conn->prepare($sql_items);
        if (isset($_POST["Sname"]) && is_array($_POST["Sname"])) {
            for ($i = 0; $i < count($_POST["Sname"]); $i++) {
                $stmt_items->bind_param("issisddd", $quote_id, $_POST["Sname"][$i], $_POST["Description"][$i], $_POST["Qty"][$i], $_POST["Price"][$i], $_POST["subtotal"][$i], $_POST["discount"][$i], $_POST["total"][$i]);
                $stmt_items->execute();
            }
        }
        $stmt_items->close();

        // --- 5. HANDLE FILE UPLOADS ---
        if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $upload_dir = 'uploads/';
            $stmt_file = $conn->prepare("INSERT INTO quote_files (quote_id, file_path) VALUES (?, ?)");
            foreach ($_FILES['attachments']['name'] as $key => $name) {
                if ($_FILES['attachments']['error'][$key] == 0) {
                    $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                    $file_name_only = $quote_id . '-' . uniqid() . '-' . basename($name);
                    $destination_path = $upload_dir . $file_name_only;
                    if (move_uploaded_file($tmp_name, $destination_path)) {
                        $stmt_file->bind_param("is", $quote_id, $file_name_only);
                        $stmt_file->execute();
                    }
                }
            }
            $stmt_file->close();
        }

        // --- 6. REDIRECT ON SUCCESS ---
        echo "<script>
                alert('Quotation Saved Successfully!');
                window.location.href='viewquotes.php';
              </script>";
        exit();

    } else {
        echo "Error saving quotation: " . $stmt_quote->error;
    }
    $stmt_quote->close();
    
} else {
    header("Location: quotation.php");
    exit();
}
?>