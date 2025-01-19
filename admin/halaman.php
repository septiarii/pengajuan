<?php
require("../admin/Konfig.php"); // Database connection
require("../admin/lib/fpdf/fpdf.php"); // FPDF Library

session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil data admin yang sedang login
$user_id = $_SESSION['user_id'];
$sql = "SELECT nama, email, alamat, no_tlp FROM tb_admin WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

// Handle logout request
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Handle AJAX untuk update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);
    $sql = "UPDATE pengajuan SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $status, $id);
        $stmt->execute();
        echo json_encode(["message" => "Status updated", "success" => true]);
    } else {
        echo json_encode(["message" => "Failed to update status", "success" => false]);
    }
    exit();
}

// Generate PDF
if (isset($_GET['download_pdf'])) {
    $sql = "SELECT p.id, u.nama, u.alamat, u.no_tlp, p.keluhan, p.status 
            FROM pengajuan p 
            JOIN users u ON p.user_id = u.id";
    $result = $conn->query($sql);
    
    if (!$result) {
        die("Error fetching data: " . $conn->error);
    }

    // Initialize PDF generation
    $pdf = new FPDF('L', 'mm', 'A4'); // 'L' for Landscape orientation
    $pdf->SetMargins(10, 10, 10); // Set margins: left, top, right
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 10);

    // Header
    $pdf->Cell(10, 10, 'ID', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Nama', 1, 0, 'C');
    $pdf->Cell(70, 10, 'Alamat', 1, 0, 'C');
    $pdf->Cell(40, 10, 'No. Telpon', 1, 0, 'C');
    $pdf->Cell(100, 10, 'Keluhan', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Status', 1, 1, 'C'); // End of row

    // Data
    $pdf->SetFont('Arial', '', 10);
    while ($row = $result->fetch_assoc()) {
        $statusText = $row['status'] == 1 ? 'Selesai' : 'Belum';
        $pdf->Cell(10, 10, $row['id'], 1);
        $pdf->Cell(40, 10, $row['nama'], 1);
        $pdf->Cell(70, 10, $row['alamat'], 1);
        $pdf->Cell(40, 10, $row['no_tlp'], 1);
        $pdf->Cell(100, 10, $row['keluhan'], 1);
        $pdf->Cell(20, 10, $statusText, 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'daftar_pengajuan.pdf'); // Force download
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Make the body take the full height of the page */
        html, body {
            height: 100%;
            margin: 0;
        }

        /* Ensure content area expands to fill available space */
        .content {
            min-height: calc(100vh - 120px); /* Adjust the height based on navbar/footer height */
            padding-bottom: 60px; /* Adjust to footer height */
        }

        /* Add padding to prevent content from being hidden behind the fixed navbar */
        body {
            padding-top: 80px; /* Adjust based on the navbar height */
        }

        /* Sticky footer styles */
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: left;
            width: 100%;
            position: fixed;
            bottom: 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-logo {
            width: 50px; /* Adjust size as needed */
            height: auto;
            margin-left: 10px; /* Optional, for spacing between text and image */
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
                    <a class="nav-link active" href="halaman.php">Home</a>
                    <a class="nav-link active" href="selesai.php">Selesai</a>
                    <a class="nav-link active" href="belum.php">Belum</a>
                </div>
                <!-- Align Logout to the far right -->
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="?logout=true">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Daftar Pengajuan</h1>
        <a href="?download_pdf=true" class="btn btn-danger mb-3">Download PDF</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No Telpon</th>
                    <th>Keluhan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT p.id, u.nama, u.alamat, u.no_tlp, p.keluhan, p.status 
                        FROM pengajuan p 
                        JOIN users u ON p.user_id = u.id";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Error fetching data: " . $conn->error);
                }

                while ($row = $result->fetch_assoc()) {
                    $checked = $row['status'] == 1 ? 'checked' : '';
                    echo "<tr>
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['nama']) . "</td>
                            <td>" . htmlspecialchars($row['alamat']) . "</td>
                            <td>" . htmlspecialchars($row['no_tlp']) . "</td>
                            <td>" . htmlspecialchars($row['keluhan']) . "</td>
                            <td><input type='checkbox' class='status-checkbox' data-id='" . htmlspecialchars($row['id']) . "' $checked></td>
                            <td>
                                <a href='edit.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2025 Sistem Pengajuan.</p>
            </div>
        </div>
    </footer>

    <script>
        $(document).ready(function () {
            $('.status-checkbox').change(function () {
                var status = $(this).is(':checked') ? 1 : 0;
                var id = $(this).data('id');
                $.post('', { id: id, status: status }, function (response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            alert('Status berhasil diperbarui!');
                        } else {
                            alert('Gagal memperbarui status!');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan!');
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
