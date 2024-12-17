<?php
$host = 'localhost'; // Nama host
$dbname = 'db_musik'; // Nama database
$username = 'root'; // Username database
$password = 'Prakbasda321'; // Password database (kosong jika default MySQL pada Laragon)

$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>
