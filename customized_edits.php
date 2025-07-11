<?php
session_start();
require_once('bhavidb.php');

if (!isset($_SESSION['email'])) {
    header('Location:index.php');
    exit();
}

// --- PHP LOGIC TO HANDLE UPDATES ---

// Handle Service Name Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_service'])) {
    $service_id = (int)$_POST['service_id'];
    $service_name = $_POST['service_name'];

    if ($service_id > 0 && !empty($service_name)) {
        $stmt = $conn->prepare("UPDATE service_names SET service_Name = ? WHERE si_No = ?");
        $stmt->bind_param("si", $service_name, $service_id);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: customized_edits.php');
    exit();
}

// Handle GST Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_gst'])) {
    $gst_id = (int)$_POST['gst_id'];
    $gst_value = $_POST['gst_value'];

    if ($gst_id > 0 && is_numeric($gst_value)) {
        $stmt = $conn->prepare("UPDATE gst_no SET gst = ? WHERE si_No = ?");
        $stmt->bind_param("di", $gst_value, $gst_id);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: customized_edits.php');
    exit();
}

// Include your modals for adding new items
include('addservice-model.php');
include('addgst-modal.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customized Edits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="img/style.css">
    <link rel="stylesheet" href="img/stylemi.css">
    <style>
        .action-buttons button,
        .action-buttons a {
            margin: 0 5px;
        }

        .table-head th {
            padding-bottom: 1rem !important;
        }

        /* Add other necessary styles here */
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">

             <?php include ('sidebar.php') ?>

            <section class="col-lg-10">
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-container p-3 bg-white rounded shadow-sm">
                                <div class="mb-2">
                                    <input type="text" class="form-control" id="service_filter" placeholder="Search Service Name...">
                                </div>

                                <table class="table">
                                    <thead class="table-head">
                                        <th>SI No</th>
                                        <th>Service Name <a href="#" class="btn btn-sm btn-outline-primary" id="add_service">ADD</a></th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $res_services = $conn->query("SELECT * FROM service_names ORDER BY si_No");
                                        while ($row = mysqli_fetch_assoc($res_services)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['si_No'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['service_Name']) . "</td>";
                                            echo "<td class='action-buttons'>
                                                    <div class='d-flex justify-content-center'>
                                                        <button class='btn btn-sm btn-warning edit-service-btn me-2' 
                                                                data-bs-toggle='modal' 
                                                                data-bs-target='#edit_service_modal' 
                                                                data-id='{$row['si_No']}' 
                                                                data-name='" . htmlspecialchars($row['service_Name'], ENT_QUOTES) . "'>
                                                            Edit
                                                        </button>
                                                        <a href=\"delete_service.php?Id={$row['si_No']}\" class='btn btn-sm btn-danger' onClick=\"return confirm('Are you sure?')\">Delete</a>
                                                    </div>
                                                </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="table-container p-3 bg-white rounded shadow-sm">
                                <div class="mb-2">
                                    <input type="text" class="form-control" id="gst_filter" placeholder="Search GST Value...">
                                </div>

                                <table class="table">
                                    <thead class="table-head">
                                        <th>SI No</th>
                                        <th>GST % <a href="#" class="btn btn-sm btn-outline-primary" id="add_gst">ADD</a></th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $res_gst = $conn->query("SELECT * FROM gst_no ORDER BY si_No");
                                        while ($row = mysqli_fetch_assoc($res_gst)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['si_No'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['gst']) . "</td>";
                                            echo "<td class='action-buttons'>
                                                <button class='btn btn-sm btn-warning edit-gst-btn' 
                                                        data-bs-toggle='modal' 
                                                        data-bs-target='#edit_gst_modal' 
                                                        data-id='{$row['si_No']}' 
                                                        data-value='" . htmlspecialchars($row['gst'], ENT_QUOTES) . "'>
                                                    Edit
                                                </button>
                                                <a href=\"delete_gst.php?Id={$row['si_No']}\" class='btn btn-sm btn-danger' onClick=\"return confirm('Are you sure?')\">Delete</a>
                                              </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="modal fade" id="edit_service_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Service Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="customized_edits.php" method="post">
                        <input type="hidden" id="edit_service_id" name="service_id">
                        <div class="form-group">
                            <label>Service Name</label>
                            <input type="text" id="edit_service_name" name="service_name" class="form-control" required>
                        </div>
                        <button type="submit" name="update_service" class="btn btn-success mt-4">Update Service</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_gst_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit GST Value</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="customized_edits.php" method="post">
                        <input type="hidden" id="edit_gst_id" name="gst_id">
                        <div class="form-group">
                            <label>GST %</label>
                            <input type="number" step="0.01" id="edit_gst_value" name="gst_value" class="form-control" required>
                        </div>
                        <button type="submit" name="update_gst" class="btn btn-success mt-4">Update GST</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php include('changepass-modal.php'); ?>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Populate service edit modal
            $('.edit-service-btn').on('click', function() {
                var serviceId = $(this).data('id');
                var serviceName = $(this).data('name');

                $('#edit_service_id').val(serviceId);
                $('#edit_service_name').val(serviceName);
            });

            // Populate GST edit modal
            $('.edit-gst-btn').on('click', function() {
                var gstId = $(this).data('id');
                var gstValue = $(this).data('value');

                $('#edit_gst_id').val(gstId);
                $('#edit_gst_value').val(gstValue);
            });
        });



        // Filter Service Name Table
        $('#service_filter').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.table-container:first tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Filter GST Table
        $('#gst_filter').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.table-container:last tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>

</body>

</html>