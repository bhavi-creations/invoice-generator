<?php
require_once('bhavidb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $names = $_POST['stock_name'];
    $descs = $_POST['stock_desc'];
    $qtys = $_POST['stock_qty'];
    $details = $_POST['stock_details'];

    $total = count($names);
    $errors = [];

    for ($i = 0; $i < $total; $i++) {
        $name = mysqli_real_escape_string($conn, $names[$i]);
        $desc = mysqli_real_escape_string($conn, $descs[$i]);
        $qty = (int)$qtys[$i];
        $detail = mysqli_real_escape_string($conn, $details[$i]);

        if (!empty($name) && !empty($desc) && $qty > 0) {
            $insert = "INSERT INTO stocks (stock_name, stock_desc, stock_qty, stock_details) 
                       VALUES ('$name', '$desc', '$qty', '$detail')";
            if (!$conn->query($insert)) {
                $errors[] = "Error inserting row $i: " . $conn->error;
            }
        }
    }

    if (empty($errors)) {
        header("Location: stocks.php?success=1");
        exit();
    } else {
        echo "<h4>Some rows failed to save:</h4><pre>" . implode("\n", $errors) . "</pre>";
    }
} else {
    echo "Invalid access.";
}
?>
