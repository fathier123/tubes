<?php
session_start();
require_once 'db.php';

// Pastikan hanya user yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = $conn->query("SELECT username, email FROM users WHERE id = $user_id");
    $user_data = $result->fetch_assoc();
}

// Proses update data pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET username = '$username', email = '$email', password = '$hashed_password' WHERE id = $user_id";
    } else {
        $update_query = "UPDATE users SET username = '$username', email = '$email' WHERE id = $user_id";
    }

    if ($conn->query($update_query)) {
        $_SESSION['message'] = "Profil berhasil diperbarui.";
        header("Location: user_dashboard.php");
        exit;
    } else {
        $error = "Terjadi kesalahan saat memperbarui profil.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Edit Profil</h1>
        <nav>
            <ul>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Edit Profil Anda</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="username">Nama Pengguna:</label>
                <input type="text" id="username" name="username" value="<?php echo $user_data['username']; ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" required>

                <label for="password">Kata Sandi (kosongkan jika tidak ingin diubah):</label>
                <input type="password" id="password" name="password">

                <button type="submit" class="btn">Simpan Perubahan</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Kursus Musik Profesional</p>
    </footer>
</body>
</html>
