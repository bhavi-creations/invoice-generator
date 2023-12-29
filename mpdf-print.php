<?php

require_once('bhavidb.php');

$sql = "SELECT * FROM invoice
JOIN service ON invoice.Sid = service.Sid
WHERE invoice.Sid = 7;";
$result = mysqli_query($conn, $sql);

// Check for query execution success
if (!$result) {
	die("Query failed: " . mysqli_error($conn));
}

// Fetch data from the result set
$row = mysqli_fetch_assoc($result);

$html = '
<html>
<head>
<style>
body {font-family: sans-serif;
	font-size: 10pt;
}
p {	margin: 0pt; }
table.items {
	border: 0.1mm solid #000000;
}
td { vertical-align: top; }
.items td {
	border-left: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
table thead td { background-color: #EEEEEE;
	text-align: center;
	border: 0.1mm solid #000000;
	font-variant: small-caps;
}
.items td.blanktotal {
	background-color: #EEEEEE;
	border: 0.1mm solid #000000;
	background-color: #FFFFFF;
	border: 0mm none #000000;
	border-top: 0.1mm solid #000000;
	border-right: 0.1mm solid #000000;
}
.items td.totals {
	text-align: right;
	border: 0.1mm solid #000000;
}
.items td.cost {
	text-align: "." center;
}

.table-heading{
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
    <table width="100%" height="50%">
        <tr>
            <td style="text-align: center;">
                <img src="img/logo.png" alt="" class="" height="12%" width="25%">
            </td>
        </tr>
    </table>
</htmlpageheader>

<htmlpagefooter name="myfooter">
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->


<table width="100%" style="font-family: Arial; " cellpadding="8" class="table-heading"><tr>
<td width="70%" style="text-align: left;">
INVOICE
</td>
<td width="40%" style="text-align: left;">
INVOICE NUMBER
</td>
</tr>
<tr>
<td width="70%" style="text-align: left;">
DATE:  ' . $row['Invoice_date'] . '
</td>
<td width="40%" style="text-align: left;">
BHAVI_KKD_2023_ ' . $row['Invoice_no'] . '
</td>
</tr>
</table>

<table width="100%" style="font-family: Arial; font-size: 12px;" cellpadding="10"><tr>
<td width="45%" style=" "><span style="font-size: 7pt; color: #555555; font-family: sans;">SOLD From:</span><br /><br />Bhavi Creations Pvt. Ltd<br />Plot no28, H No70, 17-28, RTO Office Rd,
<br />opposite to New RTO Office, behind J.N.T.U,<br />Engineering College Play Ground,RangaRaoNagar, Kakinada,
<br />Phone no: 9642343434 <br /> Email: admin@bhavicreations.com <br /> GSTIN 37AAKCB6060HIZB <br /></td>
<td width="30%"></td>
<td width="45%" style=""><span style="font-size: 7pt; color: #555555; font-family: sans;">SHIP TO:</span><br /><br /> ' . $row['Company_name'] . ', <br />' . $row['Cname'] . ', <br /> ' . $row['Caddress'] . ' <br /> ' . $row['Cphone'] . ', <br /> ' . $row['Cmail'] . ' <br /> ' . $row['Cgst'] . ' </td>
</tr></table>

<br />

<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
<thead>
<tr>
<td width="25%">Services</td>
<td width="45%">Description</td>
<td width="10%">Qty</td>
<td width="15%">Unit Price</td>
<td width="15%">Total</td>
<td width="15%">Discount</td>
<td width="15%">Final</td>
</tr>
</thead>
<tbody>';

while ($row = mysqli_fetch_assoc($result)) {
	$html .= '
		<tr>
			<td align="center">' . $row["Sname"] . ' </td>
			<td align="center">' . $row['Description'] . '</td>
			<td>' . $row['Qty'] . '</td>
			<td class="cost">' . $row['Price'] . '</td>
			<td class="cost">' . $row['Totalprice'] . '</td>
			<td class="cost">' . $row['Discount'] . '</td>
			<td class="cost">' . $row['Finaltotal'] . '</td>
		</tr>';
}

$html .= '
<tr>
<td class="blanktotal" colspan="3" rowspan="6"></td>
<td class="totals">Subtotal:</td>
<td class="totals cost">&pound;1825.60</td>
</tr>
<tr>
<td class="totals">Tax:</td>
<td class="totals cost">&pound;18.25</td>
</tr>
<tr>
<td class="totals">Shipping:</td>
<td class="totals cost">&pound;42.56</td>
</tr>
<tr>
<td class="totals"><b>TOTAL:</b></td>
<td class="totals cost"><b>&pound;1882.56</b></td>
</tr>
<tr>
<td class="totals">Deposit:</td>
<td class="totals cost">&pound;100.00</td>
</tr>
<tr>
<td class="totals"><b>Balance due:</b></td>
<td class="totals cost"><b>&pound;1782.56</b></td>
</tr>
</tbody>
</table>


<div style="text-align: center; font-style: italic;">Payment terms: payment due in 30 days</div>


</body>
</html>
';

$path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
require_once $path . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
	'margin_left' => 20,
	'margin_right' => 15,
	'margin_top' => 35,
	'margin_bottom' => 25,
	'margin_header' => 5,
	'margin_footer' => 10
]);

$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Acme Trading Co. - Invoice");
$mpdf->SetAuthor("Acme Trading Co.");
$mpdf->SetWatermarkText("Paid");
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($html);

$mpdf->Output();
