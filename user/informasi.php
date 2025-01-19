<?php
session_start();  // Start the session to access user data

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}
// Handle logout request
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
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
        /* Add padding to prevent content from being hidden behind the fixed navbar */
        body {
            padding-top: 80px; /* Adjust based on the navbar height */
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
                    <a class="nav-link" href="halaman.php">Home</a>
                    <a class="nav-link active" href="informasi.php">Informasi</a>
                </div>
                <!-- Align Logout to the far right -->
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="?logout=true">Logout</a>
                </div>
            </div>
        </div>
    </nav>

   <!-- Main Content Section -->
<div class="container mt-4">
    <h2 class="text-center">Informasi Sistem Pengajuan</h2>

    <div class="row">
        <!-- Sejarah Section -->
        <div class="col-md-6">
            <h4>Sejarah</h4>
            <p>
                Sistem Pengajuan ini dimulai dengan tujuan untuk memudahkan pengguna dalam menyampaikan keluhan atau permintaan terkait layanan. Dengan adanya sistem ini, diharapkan proses pengajuan menjadi lebih terstruktur dan terorganisir.
            </p>
        </div>

        <!-- Visi Section -->
        <div class="col-md-6">
            <h4>Visi</h4>
            <p>
                Menjadi sistem pengajuan yang transparan dan efisien, memberikan solusi cepat dan tepat untuk setiap keluhan atau permintaan dari pengguna.
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Misi Section -->
        <div class="col-md-6">
            <h4>Misi</h4>
            <p>
                1. Memberikan pelayanan pengajuan yang cepat dan responsif.<br>
                2. Menyediakan saluran komunikasi yang jelas dan terbuka antara pengguna dan pihak terkait.<br>
                3. Meningkatkan kualitas layanan dengan menerima umpan balik dari pengguna secara efektif.
            </p>
        </div>
    </div>

    <!-- Location Section -->
    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Lokasi Universitas Hamzanwadi</h4>
            <p>
                Berikut adalah lokasi Universitas Hamzanwadi di peta:
            </p>
            <!-- Google Map Embed -->
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15818.436373680983!2d116.51552537228586!3d-8.645072708566748!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcc4eb0976cb78f%3A0x1dfeb9992591f5bf!2sUniversitas%20Hamzanwadi!5e0!3m2!1sen!2sid!4v1674144814622!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white mt-4">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
