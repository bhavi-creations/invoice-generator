<?php
require_once('bhavidb.php');

$Cid = (isset($_GET['Id']) ? $_GET['Id'] : '');

// Initialize variables with default values
$Name = $Phone = $Email = $Address = $Gst_no = '';

// Fetch customer details for update
$stmt = $conn->prepare("SELECT * FROM `customer` WHERE Id = ?");
$stmt->bind_param("i", $Cid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $Name = $row['Name'];
    $Phone = $row['Phone'];
    $Email = $row['Email'];
    $Address = $row['Address'];
    $Gst_no = $row['Gst_no'];
  }
}
if (isset($_POST['Update'])) {
  $Cid = (isset($_GET['Id']) ? $_GET['Id'] : '');

  // Validate and sanitize inputs
  $Name = mysqli_real_escape_string($conn, $_POST['cname']);
  $Phone = mysqli_real_escape_string($conn, $_POST['cphone']);
  $Email = mysqli_real_escape_string($conn, $_POST['cemail']);
  $Address = mysqli_real_escape_string($conn, $_POST['caddress']);
  $Gst_no = mysqli_real_escape_string($conn, $_POST['cgst']);

  // Update query with prepared statement
  $stmt = $conn->prepare("UPDATE `customer` SET `Name`=?, `Phone`=?, `Email`=?, `Address`=?, `Gst_no`=? WHERE `Id`=?");
  $stmt->bind_param("sssssi", $Name, $Phone, $Email, $Address, $Gst_no, $Cid);
  $stmt->execute();

  // Check for success or failure
  if ($stmt->affected_rows > 0) {
    echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Successfully Updated')
            window.location.href='viewcustomers.php';
        </SCRIPT>");
  } else {
    echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('No changes made. Please make sure to modify some fields before updating.')
            window.location.href='viewcustomers.php';
        </SCRIPT>");
  }

  $stmt->close();
}
?>