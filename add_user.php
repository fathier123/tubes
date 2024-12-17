<?php
session_start();
require_once 'db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['message'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $_SESSION['message'] = "Gagal menambahkan pengguna!";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - Admin</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Tambah Pengguna - Kursus Musik Profesional</h1>
    </header>

    <main>
        <section class="auth">
            <h2>Form Tambah Pengguna</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Nama Pengguna" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Kata Sandi" required>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="btn">Tambah Pengguna</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Kursus Musik Profesional</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
