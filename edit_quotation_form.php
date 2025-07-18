<?php
error_reporting(E_ALL); // Keep for now
ini_set('display_errors', 1); // Keep for now

session_start();
require_once('bhavidb.php');

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

// Check if the form was submitted from your edit_quotation.php page
if (isset($_POST["save"])) {

    // --- 1. GET THE ID OF THE QUOTATION TO UPDATE ---
    $quote_id = isset($_POST['quote_id']) ? (int)$_POST['quote_id'] : 0;
    if ($quote_id === 0) {
        die("ERROR: Quotation ID not found.");
    }

    // --- 2. FETCH THE NEW CUSTOMER'S DETAILS ---
    // This is the fix for the address not changing.
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
        die("ERROR: Could not find the selected customer's details.");
    }
    $stmt_customer->close();

    // --- 3. GET THE REST OF THE FORM DATA ---
    $quotation_date = date("Y-m-d", strtotime($_POST["quotation_date"]));
    $note = $_POST["note"];
    $payment_details_type = $_POST['payment_details'];

    // --- 4. UPDATE THE MAIN QUOTATION RECORD ---
    $sql_update = "UPDATE quotation SET 
        quotation_date = ?, Company_name = ?, Cname = ?, Cphone = ?, Caddress = ?, Cmail = ?, Cgst = ?, Note = ?, payment_details_type = ?
        WHERE Sid = ?";

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param(
        "sssssssssi",
        $quotation_date,
        $company_name,
        $cname,
        $cphone,
        $caddress,
        $cemail,
        $cgst,
        $note,
        $payment_details_type,
        $quote_id
    );

    if ($stmt_update->execute()) {
        // --- 5. UPDATE SERVICE ITEMS (DELETE AND RE-INSERT) ---
        $stmt_delete_items = $conn->prepare("DELETE FROM quservice WHERE Sid = ?");
        $stmt_delete_items->bind_param("i", $quote_id);
        $stmt_delete_items->execute();
        $stmt_delete_items->close();

        $sql_items = "INSERT INTO quservice (Sid, Sname, Description, Qty, Price, Finaltotal) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_items = $conn->prepare($sql_items);
        if (isset($_POST["Sname"]) && is_array($_POST["Sname"])) {
            for ($i = 0; $i < count($_POST["Sname"]); $i++) {
                $stmt_items->bind_param("issisd", $quote_id, $_POST["Sname"][$i], $_POST["Description"][$i], $_POST["Qty"][$i], $_POST["Price"][$i], $_POST["total"][$i]);
                $stmt_items->execute();
            }
        }
        $stmt_items->close();

        // --- 6. HANDLE FILE UPLOADS AND DELETIONS ---
        // Delete marked files
        if (!empty($_POST['delete_files'])) {
            foreach ($_POST['delete_files'] as $file_id) {
                // You can add logic here to also delete the file from the 'uploads' folder
                $stmt_delete_file = $conn->prepare("DELETE FROM quote_files WHERE id = ?");
                $stmt_delete_file->bind_param("i", $file_id);
                $stmt_delete_file->execute();
                $stmt_delete_file->close();
            }
        }
        // Add new files
        if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $upload_dir = 'uploads/';
            $stmt_file = $conn->prepare("INSERT INTO quote_files (quote_id, file_path) VALUES (?, ?)");
            foreach ($_FILES['attachments']['name'] as $key => $name) {
                if ($_FILES['attachments']['error'][$key] == 0) {
                    $tmp_name = $_FILES['attachments']['tmp_name'][$key];
                    $file_name_only = $quote_id . '-' . uniqid() . '-' . basename($name);
                    if (move_uploaded_file($tmp_name, $upload_dir . $file_name_only)) {
                        $stmt_file->bind_param("is", $quote_id, $file_name_only);
                        $stmt_file->execute();
                    }
                }
            }
            $stmt_file->close();
        }

        // --- 7. REDIRECT ON SUCCESS ---
        echo "<script>
                alert('Quotation Updated Successfully!');
                window.location.href='viewquotes.php';
              </script>";
        exit();
    } else {
        echo "Error updating quotation: " . $stmt_update->error;
    }
    $stmt_update->close();
}
