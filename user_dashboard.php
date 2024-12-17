<?php
session_start();
require_once 'db.php';

// Pastikan hanya user yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT id, username, email, role FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Dashboard User - Kursus Musik Profesional</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="user_dashboard.php">Dashboard</a></li>
                    <li><a href="jenis_kursus.php">Jenis Kursus</a></li> <!-- Menambahkan navigasi ke jenis_kursus -->
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="profile">
                <h2>Profil Pengguna</h2>
                <p><strong>Nama Pengguna:</strong> <?php echo $user['username']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>

                <a href="edit_profile.php" class="btn">Edit Profil</a>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Kursus Musik Profesional</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
