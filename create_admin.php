<?php
// Password yang akan di-hash
$password = 'admin123';

// Menggunakan password_hash untuk meng-hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Kode untuk memasukkan data ke database
$conn = new mysqli('localhost', 'root', 'Prakbasda321', 'db_musik'); // Ganti dengan kredensial database Anda
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Masukkan akun admin
$sql = "INSERT INTO users (username, email, password, role) 
        VALUES ('admin', 'admin@example.com', '$hashed_password', 'admin')";

if ($conn->query($sql) === TRUE) {
    echo "Akun admin berhasil dibuat!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
