 <?php
    require_once('bhavidb.php');

    if (!isset($_GET['id'])) {
        die("Stock ID missing.");
    }

    $id = (int) $_GET['id'];

    // Fetch the stock entry
    $query = "SELECT * FROM stocks WHERE id = $id";
    $result = $conn->query($query);
    if (!$result || $result->num_rows === 0) {
        die("Stock not found.");
    }
    $stock = $result->fetch_assoc();

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['stock_name'];
        $desc = $_POST['stock_desc'];
        $qty = $_POST['stock_qty'];
        $details = $_POST['stock_details'];

        $stmt = $conn->prepare("UPDATE stocks SET stock_name=?, stock_desc=?, stock_qty=?, stock_details=? WHERE id=?");
        $stmt->bind_param("ssisi", $name, $desc, $qty, $details, $id);

        if ($stmt->execute()) {
            header("Location: stocks.php?updated=1");
            exit;
        } else {
            echo "Update failed: " . $conn->error;
        }
    }
    ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <title>Customers with Invoices</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="img/style.css">
     <link rel="stylesheet" href="img/stylemi.css">
     <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

 </head>

 <body>

     <div class="container-fluid">
         <div class="row">

             <!-- Sidebar -->
             <?php include('sidebar.php'); ?>


             <!-- Main Content -->
             <section class="col-lg-10">

                 <div class="container mt-5">
                     <h3>Edit Stock Entry</h3>
                     <form method="POST">
                         <div class="mb-3">
                             <label class="form-label">Stock Name</label>
                             <input type="text" name="stock_name" class="form-control" value="<?= htmlspecialchars($stock['stock_name']) ?>" required>
                         </div>
                         <div class="mb-3">
                             <label class="form-label">Stock Description</label>
                             <input type="text" name="stock_desc" class="form-control" value="<?= htmlspecialchars($stock['stock_desc']) ?>" required>
                         </div>
                         <div class="mb-3">
                             <label class="form-label">Quantity</label>
                             <input type="number" name="stock_qty" class="form-control" value="<?= $stock['stock_qty'] ?>" required>
                         </div>
                         <div class="mb-3">
                             <label class="form-label">Additional Details</label>
                             <textarea name="stock_details" class="form-control" rows="3"><?= htmlspecialchars($stock['stock_details']) ?></textarea>
                         </div>
                         <button type="submit" class="btn btn-success">Update</button>
                         <a href="stocks.php" class="btn btn-secondary">Cancel</a>
                     </form>
                 </div>
             </section>

         </div>
     </div>


     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


 </body>

 </html>