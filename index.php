<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Sistem Pengajuan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 80px;
        }
        .info-section {
            margin-top: 30px;
        }
        .info-section h4 {
            margin-top: 20px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card {
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-body {
            text-align: center;
        }
        .img-fluid {
            transition: transform 0.3s ease-in-out;
        }
        .img-fluid:hover {
            transform: scale(1.05);
        }
        .footer-section {
            background-color: #212529;
            color: white;
            padding: 20px 0;
        }
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        .footer-section ul li {
            margin: 10px 0;
        }
        .footer-section ul li a {
            color: white;
            text-decoration: none;
        }
        .footer-section ul li a:hover {
            text-decoration: underline;
        }
        .login-btns {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .login-btns .btn {
            font-size: 18px;
            padding: 10px 20px;
            width: 200px;
        }
        .btn-user {
            background-color: #28a745;
            color: white;
        }
        .btn-user:hover {
            background-color: #218838;
        }
        .btn-admin {
            background-color: #007bff;
            color: white;
        }
        .btn-admin:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistem Pengajuan</a>
            <div class="navbar-nav ms-auto">
                <!-- Login Buttons -->
                <div class="login-btns">
                    <a href="user/login.php" class="btn btn-user">
                        <i class="bi bi-person-circle"></i> Login as User
                    </a>
                    <a href="admin/login.php" class="btn btn-admin">
                        <i class="bi bi-person-lock"></i> Login as Admin
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Selamat Datang di Sistem Pengajuan</h2>
        <p class="text-center">Sistem ini memungkinkan Anda untuk mengajukan keluhan atau permintaan layanan dengan mudah dan cepat. Jelajahi berbagai informasi dan manfaat yang dapat Anda peroleh di sini.</p>

        <!-- Information Sections -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <!-- Jokowi's Image in the "Pengajuan Keluhan" card -->
                    <img src="uploads/image copy 2.png" class="card-img-top" alt="Fitur Pengajuan" loading="lazy">
                    <div class="card-body">
                        <h5 class="card-title">Sistem Keluhan</h5>
                        <p class="card-text">Kirimkan keluhan Anda terkait perangkat atau layanan yang Anda gunakan dengan mudah dan cepat.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <img src="uploads/image copy.png" class="card-img-top" alt="Permintaan Layanan" loading="lazy"> <!-- External image link -->
                    <div class="card-body">
                        <h5 class="card-title">Permintaan Layanan</h5>
                        <p class="card-text">Ajukan permintaan layanan atau bantuan terkait masalah teknis atau lainnya yang Anda alami.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <img src="uploads/image.png" class="card-img-top" alt="Status Pengajuan" loading="lazy"> <!-- External image link -->
                    <div class="card-body">
                        <h5 class="card-title">Status Pengajuan</h5>
                        <p class="card-text">Cek status dari setiap pengajuan yang telah Anda buat secara real-time untuk memastikan pengajuan Anda diproses dengan cepat.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section with more descriptive image -->
        <div class="info-section">
            <h4>Manfaat Menggunakan Sistem Ini</h4>
            <div class="row">
                <div class="col-md-6">
                    <img src="uploads/image copy 3.png" class="img-fluid rounded" alt="Manfaat Pengajuan" loading="lazy"> <!-- External image link -->
                </div>
                <div class="col-md-6">
                    <ul>
                        <li><strong>Penyelesaian Cepat:</strong> Dapatkan solusi cepat terhadap masalah yang Anda hadapi.</li>
                        <li><strong>Akses Mudah:</strong> Akses sistem kapan saja dan dari mana saja dengan login yang mudah.</li>
                        <li><strong>Keamanan Data:</strong> Kami menjaga kerahasiaan informasi Anda dengan sistem yang aman.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section with step-by-step process -->
        <div class="info-section">
            <h4>Cara Menggunakan Sistem</h4>
            <div class="row">
                <div class="col-md-4">
                    <img src="uploads/image copy 4.png" class="img-fluid rounded" alt="Step 1" loading="lazy"> <!-- External image link -->
                    <h5 class="text-center">Langkah 1: Login</h5>
                    <p class="text-center">Masuk ke sistem dengan menggunakan email dan password Anda.</p>
                </div>
                <div class="col-md-4">
                    <img src="uploads/image copy 5.png" class="img-fluid rounded" alt="Step 2" loading="lazy"> <!-- External image link -->
                    <h5 class="text-center">Langkah 2: Ajukan Permintaan</h5>
                    <p class="text-center">Pilih jenis pengajuan dan isi form sesuai dengan kebutuhan Anda.</p>
                </div>
                <div class="col-md-4">
                    <img src="uploads/image copy 6.png" class="img-fluid rounded" alt="Step 3" loading="lazy"> <!-- External image link -->
                    <h5 class="text-center">Langkah 3: Cek Status</h5>
                    <p class="text-center">Cek status pengajuan Anda dan pastikan pengajuan Anda diproses.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer-section">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5>Kontak Kami</h5>
                    <ul>
                        <li><strong>Email:</strong> <a href="mailto:support@sistem.com">support@sistem.com</a></li>
                        <li><strong>Telepon:</strong> <a href="tel:+62123456789">+62 123 456 789</a></li>
                        <li><strong>Alamat:</strong> Jalan Raya No.123, Kota ABC, Indonesia</li>
                    </ul>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Follow Us</h5>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Twitter</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include Bootstrap Icons for the icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
