<?php

require_once('bhavidb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convert_id'])) {
    $Sid = $_POST['convert_id'];

$sql_quotation = "SELECT * FROM `quotation` WHERE `Sid` = '$Sid'";
$result_quotation = mysqli_query($conn, $sql_quotation);

if (!$result_quotation) {
    die("Query failed: " . mysqli_error($conn) . ". Query: " . $sql_quotation);
}

function getInvoiceId()
{
    $server = 'localhost';
    // Condition to check if the script is running locally or on a server
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
        // Local environment details
        $username = 'root';
        $pass = '';
        $database = 'bhavi_invoice_db';
    } else {
        // Server environment details
        $username = 'cnpthbbs_invoice_user';
        $pass = '%tNc6peV4-}w';
        $database = 'cnpthbbs_invoice';
    }

    $conn = mysqli_connect($server, $username, $pass, $database);

    if ($conn->connect_error) {
        die('Error : (' . $conn->connect_errno . ') ' . $conn->connect_error);
    }

    $query = "SELECT Invoice_no FROM invoice ORDER BY Invoice_no DESC LIMIT 1";

    if ($result = $conn->query($query)) {
        $row_cnt = $result->num_rows;

        $row = mysqli_fetch_assoc($result);

        if ($row_cnt == 0) {
            $nextInvoiceNumber = INVOICE_INITIAL_VALUE;
        } else {
            $nextInvoiceNumber = $row['Invoice_no'] + 1;
        }


        $formattedInvoiceNumber = sprintf('%04d', $nextInvoiceNumber);


        $result->free();


        $conn->close();

        return $formattedInvoiceNumber;
    }
}

$invoiceNumber = getInvoiceId();

$row_quotation = mysqli_fetch_assoc($result_quotation);

$invoice_date = $row_quotation["quotation_date"];
$final_total = $row_quotation["Grandtotal"];
$Gst = $row_quotation["Gst"];
$Gst_total = $row_quotation["Gst_total"];
$Grand_total = $row_quotation["Final"];
$Totalin_word = $row_quotation["Totalinwords"];
$terms = $row_quotation["Terms"];
$note = $row_quotation["Note"];
$advance = $row_quotation["advance"];
$balance = $row_quotation["balance"];
$balancewords = $row_quotation["balancewords"];
$company_name = $row_quotation['Company_name'];
$cname = $row_quotation['Cname'];
$cphone = $row_quotation['Cphone'];
$caddress = $row_quotation['Caddress'];
$cemail = $row_quotation['Cmail'];
$cgst = $row_quotation['Cgst'];

$sql_service = "SELECT * FROM `quservice` WHERE `Sid` = '$Sid'";
$result_service = mysqli_query($conn, $sql_service);

if (!$result_service) {
    die("Query failed: " . mysqli_error($conn) . ". Query: " . $sql_service);
}

if (isset($_POST["submit"])) {
    $sql_invoice = "INSERT INTO invoice (Invoice_no, Invoice_date, Company_name, Cname, Cphone, Caddress, Cmail, Cgst, Final, Gst, Gst_total, Grandtotal, Totalinwords, Terms, Note, advance, balance, balancewords, status) 
                    VALUES ('$invoiceNumber', '$invoice_date', '$company_name', '$cname', '$cphone', '$caddress', '$cemail', '$cgst', '$final_total', '$Gst', '$Gst_total', '$Grand_total', '$Totalin_word', '$terms', '$note', '$advance', '$balance', '$balancewords', 'pending')";

    if ($conn->query($sql_invoice)) {
        $invoice_id = $conn->insert_id;

        // Rewind or use mysqli_data_seek before looping through results again
        mysqli_data_seek($result_service, 0);

        while ($row_service = mysqli_fetch_assoc($result_service)) {
            $Sname = $row_service["Sname"];
            $Description = $row_service["Description"];
            $Qty = $row_service["Qty"];
            $Price = $row_service["Price"];
            $Subtotal = $row_service["Totalprice"];
            $Discount = $row_service["Discount"];
            $total = $row_service["Finaltotal"];

            $sql_service_insert = "INSERT INTO service (invoice_id, Sname, Description, Qty, Price, Totalprice, Discount, Finaltotal) 
                                    VALUES ('$invoice_id', '$Sname', '$Description', '$Qty', '$Price', '$Subtotal', '$Discount', '$total')";

            if (!$conn->query($sql_service_insert)) {
                echo "Service Insert Failed: " . $conn->error;
            }
        }

        echo "<script>
                window.alert('Invoice added successfully');
                window.location.href='print.php?Sid=$invoiceNumber';
              </script>";
    } else {
        echo "Invoice Insert Failed: " . $conn->error;
    }
}
}
?>