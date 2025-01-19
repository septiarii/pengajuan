<?php
// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'tim';

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}