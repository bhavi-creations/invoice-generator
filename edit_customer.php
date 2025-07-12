<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

require_once('bhavidb.php');

// Validate customer ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid customer ID.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM customer WHERE Id = $id");

if (!$result || $result->num_rows === 0) {
    die("Customer not found.");
}

$customer = $result->fetch_assoc();
$error = "";

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firm = trim($_POST['company_name']);
    $name = trim($_POST['cname']);
    $address = trim($_POST['caddress']);
    $phone = trim($_POST['cphone']);
    $email = trim($_POST['cemail']);
    $gst = trim($_POST['cgst']);

    $stmt = $conn->prepare("UPDATE customer SET Company_name = ?, Name = ?, Address = ?, Phone = ?, Email = ?, Gst_no = ? WHERE Id = ?");
    $stmt->bind_param("ssssssi", $firm, $name, $address, $phone, $email, $gst, $id);

    if ($stmt->execute()) {
        header("Location: viewcustomers.php?updated=1");
        exit();
    } else {
        $error = "Failed to update customer. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('header.php'); ?>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include('sidebar.php'); ?>

            <!-- Main Content -->
            <section class="col-lg-10">
                <div class="container mt-5">
                    <h3 class="mb-4 text-center">Edit Customer Details</h3>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" class="p-4 rounded shadow bg-white" style="max-width: 700px; margin: auto;">
                        <div class="mb-3">
                            <label class="form-label">Firm Name</label>
                            <input type="text" name="company_name" class="form-control" required value="<?= htmlspecialchars($customer['Company_name']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Client Name</label>
                            <input type="text" name="cname" class="form-control" required value="<?= htmlspecialchars($customer['Name']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="caddress" class="form-control" value="<?= htmlspecialchars($customer['Address']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="cphone" class="form-control" required value="<?= htmlspecialchars($customer['Phone']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="cemail" class="form-control" value="<?= htmlspecialchars($customer['Email']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="cgst" class="form-control" value="<?= htmlspecialchars($customer['Gst_no']) ?>">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="viewcustomers.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
