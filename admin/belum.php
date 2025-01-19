<?php
require("../admin/Konfig.php");  // Include database connection

// Start session to manage user login status
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the user data from the database
    $sql = "SELECT nama, email, alamat, no_tlp FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
} else {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Handle AJAX request for updating status
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update the status in the database
    $sql = "UPDATE pengajuan SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $id);
    $stmt->execute();
    exit();  // Stop further execution after the AJAX request is handled
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete the submission
    $sql = "DELETE FROM pengajuan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Redirect back to the same page after deletion
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    // Destroy the session and log out
    session_unset(); // Unset session variables
    session_destroy();
    header("Location: login.php");  // Redirect to the login page
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
            text-align: left;  /* Align the text to the left */
            width: 100%;
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

    <!-- Content -->
    <div class="content">
        <div class="container mt-4">
            <h2 class="text-center">Daftar Pengajuan yang Belum Diperiksa</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary text-center">
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
                        // Select data from both users and pengajuan tables where status = 0 (unchecked)
                        $sql = "SELECT p.id, u.nama, u.alamat, u.no_tlp, p.keluhan, p.status 
                                FROM pengajuan p 
                                JOIN users u ON p.user_id = u.id
                                WHERE p.status = 0";  // Only fetch unchecked data
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['no_tlp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['keluhan']) . "</td>";
                                echo "<td class='text-center'>" . ($row['status'] == 1 ? 'Sudah' : 'Belum') . "</td>";
                                echo "<td class='text-center'>"
                                    . "<a href='?edit=" . htmlspecialchars($row['id']) . "' class='btn btn-warning btn-sm'>Edit</a> "
                                    . "<a href='?delete=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a>"
                                    . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Tidak ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Sistem Pengajuan.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

<?php
$conn->close();
?>
