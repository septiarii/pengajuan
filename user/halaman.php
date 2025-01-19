<?php
session_start();
require("../user/konfig.php"); // Include database connection

// Handle logout request
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Initialize variables
$edit_keluhan = "";
$edit_id = 0;
$message = "";

// Redirect after POST to avoid resubmission on refresh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keluhan = htmlspecialchars($_POST['keluhan']);

    try {
        if (isset($_POST['submit'])) {
            // Insert new keluhan
            $sql = "INSERT INTO pengajuan (user_id, keluhan, status) VALUES (?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $user_id, $keluhan);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Pengajuan berhasil ditambahkan.";
                header("Location: halaman.php");
                exit();
            } else {
                throw new Exception("Terjadi kesalahan saat menambahkan pengajuan.");
            }
        } elseif (isset($_POST['update'])) {
            // Update existing keluhan
            $id = intval($_POST['id']);
            $sql = "UPDATE pengajuan SET keluhan = ? WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $keluhan, $id, $user_id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Pengajuan berhasil diperbarui.";
                header("Location: halaman.php");
                exit();
            } else {
                throw new Exception("Terjadi kesalahan saat memperbarui pengajuan.");
            }
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        header("Location: halaman.php");
        exit();
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Check if the complaint belongs to the logged-in user before deletion
    $check_sql = "SELECT id FROM pengajuan WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $sql = "DELETE FROM pengajuan WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $user_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Pengajuan berhasil dihapus.";
        } else {
            $_SESSION['message'] = "Terjadi kesalahan saat menghapus pengajuan.";
        }
    } else {
        $_SESSION['message'] = "Pengajuan tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.";
    }
    header("Location: halaman.php");
    exit();
}

// Handle edit request (prefill the form)
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $sql = "SELECT keluhan FROM pengajuan WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $edit_keluhan = htmlspecialchars($row['keluhan']);
        $edit_id = $id;
    }
}

// Fetch all applications made by the logged-in user with pagination
$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$sql = "SELECT p.id, p.keluhan, p.status, u.nama FROM pengajuan p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.user_id = ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count total entries for pagination
$count_sql = "SELECT COUNT(*) AS total FROM pengajuan WHERE user_id = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_entries = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_entries / $limit);

// Display message if set
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        table {
            word-wrap: break-word;
            table-layout: auto; 
            width: 100%;
        }
        td {
            word-wrap: break-word;
            white-space: normal;
        }
        footer {
    margin-top: auto;
    background-color: #343a40;
    color: white;
    text-align: left; /* Align the text to the left */
    padding: 15px 0;
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="halaman.php">Home</a>
                    <a class="nav-link active" href="informasi.php">Informasi</a>
                </div>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="?logout=true">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Daftar Pengajuan</h2>

        <!-- Success or Error message -->
        <?php if (!empty($message)) { ?>
            <div class="alert alert-info"> <?php echo $message; ?> </div>
        <?php } ?>

        <!-- Application Submission Form -->
        <div class="mb-4">
            <h4><?php echo $edit_id > 0 ? "Edit Pengajuan" : "Ajukan Pengajuan Baru"; ?></h4>
            <form method="POST">
                <div class="mb-3">
                    <label for="keluhan" class="form-label">Keluhan / Permintaan</label>
                    <input type="text" class="form-control" id="keluhan" name="keluhan" value="<?php echo $edit_keluhan; ?>" required>
                </div>
                <?php if ($edit_id > 0) { ?>
                    <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    <a href="halaman.php" class="btn btn-secondary">Batal</a>
                <?php } else { ?>
                    <button type="submit" name="submit" class="btn btn-primary">Ajukan</button>
                <?php } ?>
            </form>
        </div>

        <!-- Table of User's Applications -->
        <h4>Daftar Pengajuan Anda</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Keluhan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) { 
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['keluhan']); ?></td>
                                <td class="text-center">
                                    <?php echo ($row['status'] == 0) ? 'Belum Diproses' : 'Selesai'; ?>
                                </td>
                                <td class="text-center">
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">Hapus</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada pengajuan yang ditemukan.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5>User Information</h5>
                    <ul>
                        <li><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['nama']); ?></li>
                        <li><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></li>
                        <li><strong>Address:</strong> <?php echo htmlspecialchars($_SESSION['alamat']); ?></li>
                        <li><strong>Phone:</strong> <?php echo htmlspecialchars($_SESSION['no_tlp']); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
