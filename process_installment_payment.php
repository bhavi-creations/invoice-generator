<?php
session_start();
require_once('bhavidb.php'); // Your database connection file

header('Content-Type: application/json'); // Respond with JSON

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    $response['message'] = "Unauthorized: Please log in.";
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $invoice_sid = isset($_POST['invoice_sid']) ? intval($_POST['invoice_sid']) : 0;
    $amount_paid = isset($_POST['amount_paid']) ? floatval($_POST['amount_paid']) : 0;
    $payment_date = isset($_POST['payment_date']) ? $_POST['payment_date'] : date('Y-m-d');
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'Cash';
    $reference_number = isset($_POST['reference_number']) ? $_POST['reference_number'] : NULL;
    $notes = isset($_POST['notes']) ? $_POST['notes'] : NULL;

    if ($invoice_sid === 0 || $amount_paid <= 0) {
        $response['message'] = "Invalid Invoice ID or Amount Paid. Amount must be greater than 0.";
        echo json_encode($response);
        exit();
    }

    // Basic input sanitization (more robust validation should be considered for production)
    $payment_method = $conn->real_escape_string($payment_method);
    $reference_number = $conn->real_escape_string($reference_number);
    $notes = $conn->real_escape_string($notes);

    $conn->begin_transaction(); // Start a transaction for atomicity

    try {
        // 1. Fetch current invoice details for validation and update
        $stmt_invoice_fetch = $conn->prepare("SELECT Grandtotal, total_paid, balance_due FROM invoice WHERE Sid = ? FOR UPDATE"); // FOR UPDATE locks the row
        $stmt_invoice_fetch->bind_param("i", $invoice_sid);
        $stmt_invoice_fetch->execute();
        $invoice_data = $stmt_invoice_fetch->get_result()->fetch_assoc();

        if (!$invoice_data) {
            throw new Exception("Invoice not found.");
        }

        $current_grand_total = $invoice_data['Grandtotal'];
        $current_total_paid = $invoice_data['total_paid'];
        $current_balance_due = $invoice_data['balance_due'];

        // Validate if amount paid exceeds balance due
        if ($amount_paid > $current_balance_due) {
            throw new Exception("Amount paid (₹" . number_format($amount_paid, 2) . ") exceeds the remaining balance (₹" . number_format($current_balance_due, 2) . "). Please enter a valid amount.");
        }

        // 2. Insert the new payment record
        $stmt_payment_insert = $conn->prepare("INSERT INTO payments (invoice_sid, payment_date, amount_paid, payment_method, reference_number, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_payment_insert->bind_param("isdsss", $invoice_sid, $payment_date, $amount_paid, $payment_method, $reference_number, $notes);
        
        if (!$stmt_payment_insert->execute()) {
            throw new Exception("Failed to record payment: " . $stmt_payment_insert->error);
        }
        $new_payment_id = $conn->insert_id; // Get the ID of the newly inserted payment

        // 3. Update the invoice's total_paid, balance_due, and payment_status
        $new_total_paid = $current_total_paid + $amount_paid;
        $new_balance_due = $current_grand_total - $new_total_paid;

        // Determine new payment status
        $new_payment_status = 'Partial';
        if ($new_balance_due <= 0.01) { // Use a small epsilon for floating point comparison
            $new_payment_status = 'Paid';
            $new_balance_due = 0.00; // Ensure balance is exactly 0 if fully paid
        } else if ($new_total_paid == 0) {
            $new_payment_status = 'Unpaid';
        }

        $stmt_invoice_update = $conn->prepare("UPDATE invoice SET total_paid = ?, balance_due = ?, payment_status = ? WHERE Sid = ?");
        $stmt_invoice_update->bind_param("ddsi", $new_total_paid, $new_balance_due, $new_payment_status, $invoice_sid);

        if (!$stmt_invoice_update->execute()) {
            throw new Exception("Failed to update invoice payment status: " . $stmt_invoice_update->error);
        }

        $conn->commit(); // Commit the transaction if all successful

        $response['status'] = 'success';
        $response['message'] = 'Payment recorded successfully!';
        $response['new_total_paid'] = $new_total_paid;
        $response['new_balance_due'] = $new_balance_due;
        $response['new_payment_status'] = $new_payment_status;
        $response['payment'] = [ // Return details of the new payment for frontend update
            'payment_id' => $new_payment_id,
            'payment_date' => $payment_date,
            'amount_paid' => $amount_paid,
            'payment_method' => $payment_method,
            'reference_number' => $reference_number,
            'notes' => $notes
        ];

    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        $response['message'] = "Error processing payment: " . $e->getMessage();
    } finally {
        // Close prepared statements if they were successfully prepared
        if (isset($stmt_invoice_fetch) && $stmt_invoice_fetch) $stmt_invoice_fetch->close();
        if (isset($stmt_payment_insert) && $stmt_payment_insert) $stmt_payment_insert->close();
        if (isset($stmt_invoice_update) && $stmt_invoice_update) $stmt_invoice_update->close();
    }
} else {
    $response['message'] = "Invalid request method or missing parameters.";
}

echo json_encode($response);
?>