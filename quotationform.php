<?php
error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 1); // Display errors immediately

session_start();
require_once('bhavidb.php'); // Ensure this path is correct

// --- DEBUGGING: Display all POST data received ---
echo "<h2>POST Data Received:</h2>";
echo "<pre>";
var_dump($_POST);
echo "</pre>";
// REMOVED: exit(); // This line was added for debugging, now removed for normal operation

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

if (isset($_POST['save'])) {

    // --- 1. GET CUSTOMER DETAILS ---
    $company_id = (int)($_POST['company'] ?? 0); // Use null coalesce for safety
    if ($company_id === 0) {
        die("Error: Company ID not provided or invalid.");
    }

    $stmt_customer = $conn->prepare("SELECT * FROM `customer` WHERE `Id` = ?");
    if ($stmt_customer === false) {
        die("Error preparing customer statement: " . $conn->error);
    }
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
        die("Error: Selected customer not found for ID: " . $company_id);
    }
    $stmt_customer->close();

    // --- 2. CAPTURE FORM DATA ---
    $quotation_no_full = $_POST['quotation_no'] ?? '';
    $quotation_no = substr($quotation_no_full, strrpos($quotation_no_full, '_') + 1); 
    $quote_date = $_POST['quote_date'] ?? '';
    $note = $_POST['note'] ?? '';
    $payment_details_type = $_POST['payment_details'] ?? ''; 

    $final_total = (float)($_POST["grand_total"] ?? 0);
    $gst_percentage = (float)($_POST["gst"] ?? 0);
    $gst_total = (float)($_POST["gst_total"] ?? 0);
    $grand_total_final = (float)($_POST["Final_total"] ?? 0);
    $advance = (float)($_POST["advance"] ?? 0);
    $balance = (float)($_POST["balance"] ?? 0);
    $total_in_words = $_POST['words'] ?? '';
    $balance_words = $_POST['balancewords'] ?? '';

    // --- CAPTURE SELECTED STAMP AND SIGNATURE FILENAMES ---
    // Correctly get stamp path from 'stamp_image_path'
    $selected_stamp_filename = $_POST['stamp_image_path'] ?? null; 
    // Signature is still missing from POST, so this will be null for now
    $selected_signature_filename = $_POST['selected_signature_filename'] ?? null; 

    // Extract just the filename for both if they exist
    if ($selected_stamp_filename) {
        $selected_stamp_filename = basename($selected_stamp_filename);
    }
    if ($selected_signature_filename) {
        $selected_signature_filename = basename($selected_signature_filename);
    }


    // --- 3. SAVE THE MAIN QUOTATION ---
    // Ensure 'selected_stamp_filename' and 'selected_signature_filename' columns
    // exist in your 'quotation' table.
    $sql_quote = "INSERT INTO quotation (quotation_no, quotation_date, Company_name, Cname, Cphone, Caddress, Cmail, Cgst, Final, Gst, Gst_total, Grandtotal, Totalinwords, Note, advance, balance, balancewords, payment_details_type, selected_stamp_filename, selected_signature_filename) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_quote = $conn->prepare($sql_quote);

    if ($stmt_quote === false) {
        // This will now clearly show if there's an issue with the SQL query or column names
        die("Error preparing statement for quotation insertion: " . $conn->error);
    }

    // Bind all 20 parameters
    $stmt_quote->bind_param(
        "ssssssssiddsdssdssss", // 18 existing parameters + 2 new string parameters for stamp and signature
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
        $payment_details_type,
        $selected_stamp_filename,      // New parameter
        $selected_signature_filename   // New parameter
    );

    if ($stmt_quote->execute()) {
        $quote_id = $conn->insert_id; // Get the ID of the newly inserted quotation

        // --- 4. SAVE SERVICE ITEMS ---
        $sql_items = "INSERT INTO quservice (Sid, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_items = $conn->prepare($sql_items);
        if ($stmt_items === false) {
            die("Error preparing statement for quotation items insertion: " . $conn->error);
        }

        if (isset($_POST["Sname"]) && is_array($_POST["Sname"])) {
            for ($i = 0; $i < count($_POST["Sname"]); $i++) {
                $stmt_items->bind_param("issddddd", 
                    $quote_id, 
                    $_POST["Sname"][$i], 
                    $_POST["Description"][$i], 
                    $_POST["Qty"][$i], 
                    $_POST["Price"][$i], 
                    $_POST["subtotal"][$i], 
                    $_POST["discount"][$i], 
                    $_POST["total"][$i]
                );
                $stmt_items->execute();
            }
        }
        $stmt_items->close();

        // --- 5. HANDLE FILE UPLOADS (ATTACHMENTS) ---
        if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $upload_dir = 'uploads/'; 
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $stmt_file = $conn->prepare("INSERT INTO quote_files (quote_id, file_path) VALUES (?, ?)");
            if ($stmt_file === false) {
                die("Error preparing statement for file uploads: " . $conn->error);
            }
            foreach ($_FILES['attachments']['name'] as $key => $name) {
                if ($_FILES['attachments']['error'][$key] == 0) { 
                    $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                    $file_name_only = $quote_id . '-' . uniqid() . '-' . basename($name);
                    $destination_path = $upload_dir . $file_name_only;
                    if (move_uploaded_file($tmp_name, $destination_path)) {
                        $stmt_file->bind_param("is", $quote_id, $file_name_only);
                        $stmt_file->execute();
                    } else {
                        error_log("Failed to move uploaded file: " . $tmp_name . " to " . $destination_path);
                    }
                } else {
                    error_log("File upload error for " . $name . ": " . $_FILES['attachments']['error'][$key]);
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
        // This will show if the execute method itself failed (e.g., data type mismatch)
        echo "Error saving quotation: " . $stmt_quote->error;
    }
    $stmt_quote->close();
    $conn->close(); 
    
} else {
    // If the form was not submitted via POST, redirect
    header("Location: quotation.php");
    exit();
}
?>