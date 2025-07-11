<?php
include("bhavidb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sid'])) {
    $sid = intval($_POST['sid']);

    $stmt = $conn->prepare("DELETE FROM invoice WHERE Sid = ?");
    $stmt->bind_param("i", $sid);

    if ($stmt->execute()) {
        header("Location: viewinvoices.php?msg=deleted");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
