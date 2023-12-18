<?php
require_once('bhavidb.php');

if (isset($_POST["submit"])) {
    // print_r($_POST);
    // exit;
    $invoice_no = mysqli_real_escape_string($conn, $_POST["invoice_no"]);
    $invoice_date = date("Y-m-d", strtotime($_POST["invoice_date"]));
    $cname = mysqli_real_escape_string($conn, $_POST["cname"]);
    $cphone = mysqli_real_escape_string($conn, $_POST["cphone"]);
    $caddress = mysqli_real_escape_string($conn, $_POST["caddress"]);
    $cemail = mysqli_real_escape_string($conn, $_POST["cemail"]);
    $cgst = mysqli_real_escape_string($conn, $_POST["cgst"]);
    $final_total = mysqli_real_escape_string($conn, $_POST["Final_total"]);

    // Insert into invoice table
    $sql = "INSERT INTO invoice (Invoice_no, Invoice_date, Cname, Cphone, Caddress, Cmail, Cgst, Grandtotal) VALUES ('$invoice_no', '$invoice_date', '$cname', '$cphone', '$caddress', '$cemail', '$cgst', '$final_total')";

    if ($conn->query($sql)) {
        $Sid = $conn->insert_id; // Get the inserted Sid

        if (isset($_POST["Sname"]) && is_array($_POST["Sname"])) {
            $sql2 = "INSERT INTO service (Sid, Sname, Qty, Price, Finaltotal, Description) VALUES ";
            $rows = [];
            for ($i = 0; $i < count($_POST["Sname"]); $i++) {
                $Sname = mysqli_real_escape_string($conn, $_POST["Sname"][$i]);
                $Qty = mysqli_real_escape_string($conn, $_POST["Qty"][$i]);
                $Price = mysqli_real_escape_string($conn, $_POST["Price"][$i]);
                $Subtotal = mysqli_real_escape_string($conn, $_POST["subtotal"][$i]);
                $Description = mysqli_real_escape_string($conn, $_POST["Description"][$i]);
                $rows[] = "('$Sid', '$Sname', '$Qty', '$Price', '$Subtotal', '$Description')";
            }
            $sql2 .= implode(",", $rows);

            // Insert into service table
            if ($conn->query($sql2)) {
                echo "<SCRIPT>
                window.alert('invoice added')
                window.location.href='invoice.php';</SCRIPT>";
            } else {
                echo "Invoice Added Failed: " . $conn->error;
            }
        } else {
            echo "Invoice Added Failed: 'Sname' key not set or is not an array.";
        }
    } else {
        echo "Invoice Added Failed: " . $conn->error;
    }
}
