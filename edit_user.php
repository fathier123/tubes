<?php
session_start();
require_once 'db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);

    if ($stmt->execute()) {
        $_SESSION['message'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $_SESSION['message'] = "Gagal memperbarui pengguna!";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - Admin</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Edit Pengguna - Kursus Musik Profesional</h1>
    </header>

    <main>
        <section class="auth">
            <h2>Edit Data Pengguna</h2>
            <form method="POST">
                <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                <select name="role" required>
                    <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
                <button type="submit" class="btn">Perbarui Pengguna</button>
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
