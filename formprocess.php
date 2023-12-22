<?php
require_once('bhavidb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['company'])) {
    $selectedCompanyId = mysqli_real_escape_string($conn, $_POST['company']);

    $sql = "SELECT * FROM `customer` WHERE `Id` = '$selectedCompanyId'";
    $res = $conn->query($sql);

    if ($row = mysqli_fetch_assoc($res)) {

        $company_name = $row['Company_name'];
        $cname = $row['Name'];
        $cphone = $row['Phone'];
        $caddress = $row['Address'];
        $cemail = $row['Email'];
        $cgst = $row['Gst_no'];

    } else {
        echo "Company not found";
    }
}

if (isset($_POST["submit"])) {

    $invoice_no = mysqli_real_escape_string($conn, $_POST["invoice_no"]);
    $invoice_date = date("Y-m-d", strtotime($_POST["invoice_date"]));
    $final_total = mysqli_real_escape_string($conn, $_POST["Final_total"]);

    $sql = "INSERT INTO invoice (Invoice_no, Invoice_date, Company_name, Cname, Cphone, Caddress, Cmail, Cgst, Grandtotal) 
            VALUES ('$invoice_no', '$invoice_date', '$company_name', '$cname', '$cphone', '$caddress', '$cemail', '$cgst', '$final_total')";

    if ($conn->query($sql)) {
        $Sid = $conn->insert_id; // Get the inserted Sid

        if (isset($_POST["Sname"]) && is_array($_POST["Sname"])) {
            $sql2 = "INSERT INTO service (Sid, Sname, Qty, Price, Finaltotal, Description) VALUES ";
            $rows = [];

            // Iterate through service details
            for ($i = 0; $i < count($_POST["Sname"]); $i++) {
                $Sname = mysqli_real_escape_string($conn, $_POST["Sname"][$i]);
                $Qty = mysqli_real_escape_string($conn, $_POST["Qty"][$i]);
                $Price = mysqli_real_escape_string($conn, $_POST["Price"][$i]);
                $Subtotal = mysqli_real_escape_string($conn, $_POST["subtotal"][$i]);
                $Description = mysqli_real_escape_string($conn, $_POST["Description"][$i]);

                // Add service details to the rows array
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
?>
