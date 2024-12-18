<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Periksa pengguna di database
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password dengan password yang ter-hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Jika role adalah admin, arahkan ke dashboard admin
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
                exit;
            } else {
                // Jika role adalah user, arahkan ke dashboard user
                header("Location: index.php");
                exit;
            }
        } else {
            $error = "Kata sandi salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

    <header>
        <div class="container">
            <h1>Kursus Musik Profesional</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="auth">
            <div class="container">
                <h2>Masuk</h2>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Kata Sandi" required>
                    <button type="submit" class="btn">Masuk</button>
                </form>
                <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Kursus Musik Profesional</p>
    </footer>

</body>
</html>