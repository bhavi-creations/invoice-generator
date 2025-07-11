<?php
include("bhavidb.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'], $_POST['cname'])) {
    $delete_id = intval($_POST['delete_id']);
    $cname = $_POST['cname'];

    $stmt = $conn->prepare("DELETE FROM quotation WHERE quotation_no = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        header("Location: customer_quotes.php?name=" . urlencode($cname));
        exit;
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
} else {
    echo "Invalid Request.";
}
?>
