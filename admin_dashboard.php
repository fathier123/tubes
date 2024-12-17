<?php
session_start();
require_once 'db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Menampilkan pesan error atau sukses
if (isset($_SESSION['message'])) {
    echo '<p>' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']);
}

// Ambil data pengguna dari database, termasuk status pembayaran
$result = $conn->query("SELECT id, username, email, role, payment_status FROM users");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Dashboard Admin - Kursus Musik Profesional</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                    <!-- Menambahkan navigasi ke jenis_kursus -->
                    <li><a href="jenis_kursus.php">Jenis Kursus</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="crud">
                <h2>Data Pengguna</h2>
                <a href="add_user.php" class="btn">Tambah Pengguna</a>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo ucfirst($user['role']); ?></td>
                                <td>
                                    <?php echo ucfirst($user['payment_status']); ?>
                                </td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="edit">Edit</a>
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="delete" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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
