<?php
session_start();
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $result = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$email'");

        if ($result->num_rows > 0) {
            $error = 'Username atau email sudah terdaftar!';
        } else {
            $conn->query("INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'user')");
            $_SESSION['message'] = 'Pendaftaran berhasil! Silakan login.';
            header("Location: login.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Register - Kursus Musik Profesional</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="auth">
                <h2>Daftar Akun Baru</h2>

                <?php if ($error): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="POST" action="register.php">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                    <button type="submit">Daftar</button>
                </form>
                <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
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
