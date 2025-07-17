<?php
ini_set('display_errors', 1); // Temporarily enable for debugging
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Main Configuration and Database Connection
require_once('bhavidb.php'); // Your existing database connection/config file

// Check if connection was successful
if (!$conn) {
    die("Database Connection Failed in stamps.php: " . mysqli_connect_error());
}

// Ensure BASE_URL and ROOT_PATH are defined
// As bhavidb.php is in the root (htdocs/invoice-generator), ROOT_PATH should be defined there.
// If not, uncomment these lines and ensure they are correct for your setup.
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/invoice-generator'); // *** DOUBLE-CHECK THIS ***
}
if (!defined('ROOT_PATH')) {
    // Since bhavidb.php is in the same directory as stamps.php,
    // and bhavidb.php is setting the DB connection,
    // ROOT_PATH should point to 'invoice-generator'.
    // If bhavidb.php defines __DIR__ as its ROOT_PATH, it will be C:\xampp\htdocs\invoice-generator
    define('ROOT_PATH', __DIR__); // This stamps.php's directory, which should be invoice-generator
}

$db_conn = $conn; // Use your existing MySQLi connection variable

$message = '';
$message_type = ''; // 'success' or 'danger'
$action = $_GET['action'] ?? 'list'; // Default action is 'list'
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stamp_data = null; // To hold data for editing

// --- Handle Form Submissions (Upload, Edit, Delete) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine the POST action
    if (isset($_POST['upload_stamp'])) {
        // --- UPLOAD LOGIC ---
        $display_name = trim($_POST['display_name'] ?? '');
        $type = $_POST['type'] ?? '';
        $description = trim($_POST['description'] ?? '');

        if (empty($display_name) || empty($type) || !in_array($type, ['company_stamp', 'director_stamp', 'signature'])) {
            $message = 'Please fill all required fields and select a valid type.';
            $message_type = 'danger';
        } elseif (!isset($_FILES['stamp_file']) || $_FILES['stamp_file']['error'] !== UPLOAD_ERR_OK) {
            $message = 'Error uploading file. Please check file size and type.';
            $message_type = 'danger';
        } else {
            $file = $_FILES['stamp_file'];
            $upload_dir = ROOT_PATH . '/uploads/'; // Use ROOT_PATH for server-side file operations
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $max_file_size = 2 * 1024 * 1024; // 2MB

            if (!in_array($file['type'], $allowed_types)) {
                $message = 'Invalid file type. Only JPG, PNG, GIF, WEBP images are allowed.';
                $message_type = 'danger';
            } elseif ($file['size'] > $max_file_size) {
                $message = 'File size exceeds 2MB limit.';
                $message_type = 'danger';
            } else {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $unique_filename = uniqid('stamp_', true) . '.' . $file_extension;
                $destination_path = $upload_dir . $unique_filename;

                if (move_uploaded_file($file['tmp_name'], $destination_path)) {
                    // Using MySQLi prepared statement
                    $stmt = mysqli_prepare($db_conn, "INSERT INTO stamps (file_name, display_name, type, description) VALUES (?, ?, ?, ?)");
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ssss", $unique_filename, $display_name, $type, $description);
                        if (mysqli_stmt_execute($stmt)) {
                            $message = 'Stamp/Signature uploaded successfully!';
                            $message_type = 'success';
                            header('Location: ' . BASE_URL . '/stamps.php?status=uploaded');
                            exit;
                        } else {
                            $message = 'Database error: ' . mysqli_error($db_conn);
                            $message_type = 'danger';
                            if (file_exists($destination_path)) {
                                unlink($destination_path); // Clean up uploaded file
                            }
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        $message = 'Failed to prepare statement: ' . mysqli_error($db_conn);
                        $message_type = 'danger';
                        if (file_exists($destination_path)) {
                            unlink($destination_path); // Clean up uploaded file
                        }
                    }
                } else {
                    $message = 'Failed to move uploaded file.';
                    $message_type = 'danger';
                }
            }
        }
    } elseif (isset($_POST['update_stamp'])) {
        // --- UPDATE LOGIC ---
        $edit_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$edit_id) {
            $message = 'Invalid stamp ID for update.';
            $message_type = 'danger';
        } else {
            $display_name = trim($_POST['display_name'] ?? '');
            $type = $_POST['type'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            // Fetch current stamp data to get old filename
            $stmt = mysqli_prepare($db_conn, "SELECT file_name FROM stamps WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $edit_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $current_stamp = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if (!$current_stamp) {
                $message = 'Stamp not found for update.';
                $message_type = 'danger';
                $upload_successful = false; // Prevent further processing
            } else {
                $new_filename = $current_stamp['file_name']; // Assume old filename by default
                $upload_successful = true;

                if (isset($_FILES['stamp_file']) && $_FILES['stamp_file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['stamp_file'];
                    $upload_dir = ROOT_PATH . '/uploads/';
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $max_file_size = 2 * 1024 * 1024; // 2MB

                    if (!in_array($file['type'], $allowed_types)) {
                        $message = 'Invalid file type. Only JPG, PNG, GIF, WEBP images are allowed.';
                        $message_type = 'danger';
                        $upload_successful = false;
                    } elseif ($file['size'] > $max_file_size) {
                        $message = 'File size exceeds 2MB limit.';
                        $message_type = 'danger';
                        $upload_successful = false;
                    } else {
                        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $new_filename = uniqid('stamp_', true) . '.' . $file_extension;
                        $destination_path = $upload_dir . $new_filename;

                        if (move_uploaded_file($file['tmp_name'], $destination_path)) {
                            // Delete old file if it's different and exists
                            if ($current_stamp['file_name'] && $current_stamp['file_name'] !== $new_filename && file_exists($upload_dir . $current_stamp['file_name'])) {
                                unlink($upload_dir . $current_stamp['file_name']);
                            }
                        } else {
                            $message = 'Failed to move new uploaded file.';
                            $message_type = 'danger';
                            $upload_successful = false;
                        }
                    }
                }

                if ($upload_successful) {
                    $stmt = mysqli_prepare($db_conn, "UPDATE stamps SET display_name = ?, type = ?, description = ?, file_name = ?, is_active = ? WHERE id = ?");
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ssssii", $display_name, $type, $description, $new_filename, $is_active, $edit_id);
                        if (mysqli_stmt_execute($stmt)) {
                            $message = 'Stamp/Signature updated successfully!';
                            $message_type = 'success';
                            header('Location: ' . BASE_URL . '/stamps.php?status=updated');
                            exit;
                        } else {
                            $message = 'Database error: ' . mysqli_error($db_conn);
                            $message_type = 'danger';
                            // If DB update fails after a new file was uploaded, delete the new file
                            if ($new_filename !== $current_stamp['file_name'] && file_exists($upload_dir . $new_filename)) {
                                unlink($upload_dir . $new_filename);
                            }
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        $message = 'Failed to prepare statement: ' . mysqli_error($db_conn);
                        $message_type = 'danger';
                    }
                }
            }
        }
    } elseif (isset($_POST['delete_stamp'])) {
        // --- DELETE LOGIC ---
        $delete_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$delete_id) {
            $message = 'Invalid stamp ID for deletion.';
            $message_type = 'danger';
        } else {
            // First, get the filename to delete the actual file
            $stmt = mysqli_prepare($db_conn, "SELECT file_name FROM stamps WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $delete_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $stamp_to_delete = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($stamp_to_delete) {
                // Delete from database
                $stmt = mysqli_prepare($db_conn, "DELETE FROM stamps WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $delete_id);
                if (mysqli_stmt_execute($stmt)) {
                    // Delete the physical file
                    $file_path = ROOT_PATH . '/uploads/' . $stamp_to_delete['file_name'];
                    if (file_exists($file_path) && is_file($file_path)) {
                        unlink($file_path);
                    }
                    $message = 'Stamp/Signature deleted successfully!';
                    $message_type = 'success';
                    header('Location: ' . BASE_URL . '/stamps.php?status=deleted');
                    exit;
                } else {
                    $message = 'Database error: ' . mysqli_error($db_conn);
                    $message_type = 'danger';
                }
                mysqli_stmt_close($stmt);
            } else {
                $message = 'Stamp/Signature not found.';
                $message_type = 'danger';
            }
        }
    }
}

// --- Fetch Data for Display ---
$stamps = [];
$result = mysqli_query($db_conn, "SELECT * FROM stamps ORDER BY uploaded_at DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $stamps[] = $row;
    }
    mysqli_free_result($result);
} else {
    $message = "Error fetching stamps: " . mysqli_error($db_conn);
    $message_type = 'danger';
}

// --- Handle GET actions (for displaying forms) ---
if ($action === 'edit' && $id) {
    $stmt = mysqli_prepare($db_conn, "SELECT * FROM stamps WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $stamp_data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$stamp_data) {
            $message = 'Stamp not found for editing.';
            $message_type = 'danger';
            $action = 'list'; // Fallback to list view if not found
        }
    } else {
        $message = 'Failed to prepare statement for edit: ' . mysqli_error($db_conn);
        $message_type = 'danger';
        $action = 'list';
    }
}

// Check for status messages from redirects
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'uploaded') {
        $message = 'Stamp/Signature uploaded successfully!';
        $message_type = 'success';
    } elseif ($_GET['status'] === 'updated') {
        $message = 'Stamp/Signature updated successfully!';
        $message_type = 'success';
    } elseif ($_GET['status'] === 'deleted') {
        $message = 'Stamp/Signature deleted successfully!';
        $message_type = 'success';
    } elseif ($_GET['status'] === 'error') {
        $message = 'An error occurred during an operation.';
        $message_type = 'danger';
    }
    // Remove the status param from URL for cleaner display
    // This part requires JS or a header redirect, but header() is already used for main POST actions.
    // For simple GET status, a meta refresh or client-side JS is needed to clear URL after display.
    // For now, it will remain in the URL, which is acceptable for status.
    // header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?')); exit; // This causes a double redirect if used here
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>

<body>
    <div class="container-fluid">
        <div class="row">

            <?php include('sidebar.php'); ?>

            <section class="col-lg-10">
                <div class="container mt-5">
                    <h1 class="mb-4">Manage Company Assets (Stamps/Signatures)</h1>

                    <?php if ($message): ?>
                        <div class="alert alert-<?= $message_type ?>" role="alert">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($action === 'list'): ?>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>All Assets</h2>
                            <a href="<?= BASE_URL ?>/stamps.php?action=upload" class="btn btn-primary">Upload New Stamp/Signature</a>
                        </div>

                        <?php if (empty($stamps)): ?>
                            <div class="alert alert-info" role="alert">
                                No stamps or signatures uploaded yet.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($stamps as $stamp): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                <?php if ($stamp['file_name'] && file_exists(ROOT_PATH . '/uploads/' . $stamp['file_name'])): ?>
                                                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($stamp['file_name']) ?>" alt="<?= htmlspecialchars($stamp['display_name']) ?>" class="img-fluid mb-3" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                                                <?php else: ?>
                                                    <img src="https://via.placeholder.com/150?text=No+Image" alt="No Image" class="img-fluid mb-3" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                                                <?php endif; ?>
                                                <h5 class="card-title"><?= htmlspecialchars($stamp['display_name']) ?></h5>
                                                <p class="card-text"><small class="text-muted"><?= ucfirst(str_replace('_', ' ', $stamp['type'])) ?></small></p>
                                                <p class="card-text"><small class="text-muted">Uploaded: <?= htmlspecialchars(date('Y-m-d H:i', strtotime($stamp['uploaded_at']))) ?></small></p>
                                                <?php if (!empty($stamp['description'])): ?>
                                                    <p class="card-text small"><?= nl2br(htmlspecialchars($stamp['description'])) ?></p>
                                                <?php endif; ?>
                                                <div class="mt-3">
                                                    <a href="<?= BASE_URL ?>/stamps.php?action=edit&id=<?= $stamp['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                                    <form action="<?= BASE_URL ?>/stamps.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?= $stamp['id'] ?>">
                                                        <button type="submit" name="delete_stamp" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this asset? This cannot be undone.');">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    <?php elseif ($action === 'upload'): ?>
                        <h2 class="mb-4">Upload New Stamp/Signature</h2>
                        <a href="<?= BASE_URL ?>/stamps.php" class="btn btn-secondary mb-4">Back to List</a>

                        <form action="<?= BASE_URL ?>/stamps.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="display_name">Display Name:</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" value="" required>
                                <small class="form-text text-muted">e.g., "Director John Doe's Signature", "Company Seal V2"</small>
                            </div>
                            <div class="form-group">
                                <label for="type">Asset Type:</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="company_stamp">Company Stamp</option>
                                    <option value="director_stamp">Director Stamp</option>
                                    <option value="signature">Signature</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="stamp_file">Upload File (JPG, PNG, GIF, WEBP - Max 2MB):</label>
                                <input type="file" class="form-control-file" id="stamp_file" name="stamp_file" accept="image/jpeg,image/png,image/gif,image/webp" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description (Optional):</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <button type="submit" name="upload_stamp" class="btn btn-primary">Upload Asset</button>
                        </form>

                    <?php elseif ($action === 'edit' && $stamp_data): ?>
                        <h2 class="mb-4">Edit Stamp/Signature</h2>
                        <a href="<?= BASE_URL ?>/stamps.php" class="btn btn-secondary mb-4">Back to List</a>

                        <form action="<?= BASE_URL ?>/stamps.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($stamp_data['id']) ?>">
                            <div class="form-group">
                                <label>Current Image:</label>
                                <?php if ($stamp_data['file_name'] && file_exists(ROOT_PATH . '/uploads/' . $stamp_data['file_name'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($stamp_data['file_name']) ?>" alt="Current Image" class="img-fluid mb-3" style="max-width: 150px; max-height: 150px; object-fit: contain; border: 1px solid #ddd; padding: 5px;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/150?text=No+Image" alt="No Image" class="img-fluid mb-3" style="max-width: 150px; max-height: 150px; object-fit: contain; border: 1px solid #ddd; padding: 5px;">
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="display_name">Display Name:</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" value="<?= htmlspecialchars($stamp_data['display_name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Asset Type:</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="company_stamp" <?= ($stamp_data['type'] === 'company_stamp') ? 'selected' : '' ?>>Company Stamp</option>
                                    <option value="director_stamp" <?= ($stamp_data['type'] === 'director_stamp') ? 'selected' : '' ?>>Director Stamp</option>
                                    <option value="signature">Signature</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="stamp_file">Upload New File (Optional - JPG, PNG, GIF, WEBP - Max 2MB):</label>
                                <input type="file" class="form-control-file" id="stamp_file" name="stamp_file" accept="image/jpeg,image/png,image/gif,image/webp">
                                <small class="form-text text-muted">Upload a new image to replace the current one.</small>
                            </div>
                            <div class="form-group">
                                <label for="description">Description (Optional):</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($stamp_data['description']) ?></textarea>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= $stamp_data['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Is Active (Show in dropdowns)</label>
                            </div>
                            <button type="submit" name="update_stamp" class="btn btn-primary">Update Asset</button>
                        </form>
                    <?php endif; ?>

                </div>
            </section>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>