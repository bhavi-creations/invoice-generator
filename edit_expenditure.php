<?php
require_once('bhavidb.php');

if (!isset($_GET['id'])) {
    die("Missing expenditure ID.");
}

$id = (int) $_GET['id'];

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update expenditure_tbl
    $date = $_POST['date'];
    $total_amount = $_POST['total_amount'];
    $amount_in_words = $_POST['amount_in_words'];
    $exp_note = $_POST['exp_note'];

    $stmt = $conn->prepare("UPDATE expenditure_tbl SET date = ?, total_amount = ?, amount_in_words = ?, exp_note = ? WHERE id = ?");
    $stmt->bind_param("sdssi", $date, $total_amount, $amount_in_words, $exp_note, $id);
    $stmt->execute();

    // Delete existing description rows
    $conn->query("DELETE FROM expenditure_desc_tbl WHERE main_expenditure_id = $id");

    // Insert updated rows
    $exp_name = $_POST['exp_name'];
    $exp_description = $_POST['exp_description'];
    $mode_payment = $_POST['mode_payment'];
    $amount = $_POST['amount'];

    for ($i = 0; $i < count($exp_name); $i++) {
        if (trim($exp_name[$i]) !== '') {
            $stmt = $conn->prepare("INSERT INTO expenditure_desc_tbl (main_expenditure_id, exp_name, exp_description, mode_payment, amount) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isssd", $id, $exp_name[$i], $exp_description[$i], $mode_payment[$i], $amount[$i]);
            $stmt->execute();
        }
    }

    header("Location: view_expenditure.php");
    exit;
}

// Fetch main record
$main = $conn->query("SELECT * FROM expenditure_tbl WHERE id = $id")->fetch_assoc();

// Fetch line items
$items = [];
$res = $conn->query("SELECT * FROM expenditure_desc_tbl WHERE main_expenditure_id = $id");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BHAVIINVOICE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css" integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet"> -->


    <link rel="stylesheet" href="img/style.css">

    <link rel="stylesheet" href="img/stylemi.css">



    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 20px;
            /* text-align: center; */
        }

        .dropdown-content a {
            color: black;
            padding: 12 px 16px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .navbar-nav li:hover .dropdown-content {
            display: block;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 58px 0 0;
            /* Height of navbar */
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
            width: 240px;
            z-index: 600;
        }

        .nav-links {
            background-color: aliceblue;
            border-radius: 20px;
        }

        .active-link {
            background-color: blue;
            color: white;
        }

        body {
            background-color: #f9f9f9;
        }

        form {
            background-color: white;
            border-radius: 50px;
        }

        .form-input {
            border-radius: 20px;
            border: none;
            background-color: aliceblue;
            padding: 5px;
        }



        table {
            border-collapse: collapse;
            width: 100%;
        }

        .thead {
            /* background-color: aliceblue; */
            border: 1px solid black;
        }

        th {
            border: none;
            padding: 4px;
            /* Adjust padding as needed */
            text-align: center;
        }

        .table-responsive {
            border-radius: 10px;
            border: 1px solid black;
        }

        .nav-item {
            padding-top: 20px;
        }

        /* 
        .navbar-nav {
            color: black;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 17px;
        }
         */
    </style>

</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>




            <!--  INVOICE  FORM  -->

            <section class="col-lg-10 col-md-12">
                <div class="container mt-5">
                    <h3 class="mb-4">Edit Expenditure</h3>
                    <form method="POST">
                        <!-- Main Info -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" value="<?= $main['date'] ?>" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Amount</label>
                                <input type="number" name="total_amount" value="<?= $main['total_amount'] ?>" step="0.01" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Amount in Words</label>
                                <input type="text" name="amount_in_words" value="<?= htmlspecialchars($main['amount_in_words']) ?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea name="exp_note" class="form-control" rows="3"><?= htmlspecialchars($main['exp_note']) ?></textarea>
                        </div>

                        <!-- Editable Line Items -->
                        <h5 class="mt-4 mb-2">Expense Breakdown</h5>
                        <table class="table table-bordered line-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Mode of Payment</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="line-items">
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><input type="text" name="exp_name[]" class="form-control" value="<?= htmlspecialchars($item['exp_name']) ?>" required></td>
                                        <td><input type="text" name="exp_description[]" class="form-control" value="<?= htmlspecialchars($item['exp_description']) ?>"></td>
                                        <td><input type="text" name="mode_payment[]" class="form-control" value="<?= htmlspecialchars($item['mode_payment']) ?>"></td>
                                        <td><input type="number" name="amount[]" class="form-control" step="0.01" value="<?= $item['amount'] ?>" required></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($items)): ?>
                                    <!-- At least one empty row -->
                                    <tr>
                                        <td><input type="text" name="exp_name[]" class="form-control" required></td>
                                        <td><input type="text" name="exp_description[]" class="form-control"></td>
                                        <td><input type="text" name="mode_payment[]" class="form-control"></td>
                                        <td><input type="number" name="amount[]" class="form-control" step="0.01" required></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary" onclick="addRow()">+ Add Row</button>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="view_expenditure.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>

            </section>

            <script>
                function addRow() {
                    const table = document.getElementById('line-items');
                    const row = document.createElement('tr');
                    row.innerHTML = `
        <td><input type="text" name="exp_name[]" class="form-control" required></td>
        <td><input type="text" name="exp_description[]" class="form-control"></td>
        <td><input type="text" name="mode_payment[]" class="form-control"></td>
        <td><input type="number" name="amount[]" class="form-control" step="0.01" required></td>
        `;
                    table.appendChild(row);
                }
            </script>

</body>

</html>