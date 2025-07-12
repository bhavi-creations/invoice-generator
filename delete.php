<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}

require_once('bhavidb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Id'])) {
    $id = intval($_POST['Id']);

    // Delete from customer table
    $sql = "DELETE FROM customer WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Customer deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete customer.";
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect back to customer view page
header("Location: viewcustomers.php");
exit();
