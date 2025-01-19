<?php
require("../admin/Konfig.php");  // Include database connection

// Start session to manage user login status
session_start();

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the data of the pengajuan
    $sql = "SELECT p.id, p.keluhan, p.status, u.nama, u.alamat, u.no_tlp 
            FROM pengajuan p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            // If no record is found, redirect to the list page
            header("Location: halaman.php");
            exit();
        }
    } else {
        die("Error preparing statement: " . $conn->error);
    }
} else {
    // If ID is not passed, redirect to the list page
    header("Location: halaman.php");
    exit();
}

// Handle the form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keluhan = htmlspecialchars(trim($_POST['keluhan']));
    $status = htmlspecialchars(trim($_POST['status']));

    // Validate inputs
    if (!empty($keluhan) && is_numeric($status)) {
        $sql = "UPDATE pengajuan SET keluhan = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sii", $keluhan, $status, $id);
            $stmt->execute();

            // Redirect to the list page after update
            header("Location: halaman.php");
            exit();
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    } else {
        $error_message = "Pastikan semua data diisi dengan benar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengajuan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            padding-top: 80px;
        }
         /* Table responsiveness */
         .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Media query to make the table more responsive */
        @media (max-width: 768px) {
            .table th, .table td {
                font-size: 10px;
                padding: 8px;
            }

            .table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistem Pengajuan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="u.php">Home</a>
                    <a class="nav-link active" href="informasi.php">Informasi</a>
                </div>
                <!-- Align Logout to the far right -->
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="?logout=true">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Edit Pengajuan</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="keluhan" class="form-label">Keluhan</label>
                <textarea name="keluhan" id="keluhan" class="form-control" rows="4" required><?= htmlspecialchars($row['keluhan']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="0" <?= $row['status'] == 0 ? 'selected' : ''; ?>>belum</option>
                    <option value="1" <?= $row['status'] == 1 ? 'selected' : ''; ?>>sudah</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <footer class="bg-dark text-white mt-4">
        <div class="container py-4">
            <p>&copy; 2025 Sistem Pengajuan</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
