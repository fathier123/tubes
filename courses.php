<?php
session_start();
require_once 'db.php';

$stmt = $conn->query("SELECT id, course_name, description FROM courses");
$courses = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kursus - Kursus Musik</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Daftar Kursus Musik</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="login.php">Masuk</a></li>
                <li><a href="register.php">Daftar</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Kursus Kami</h2>
        <ul>
            <?php foreach ($courses as $course): ?>
                <li>
                    <h3><?php echo $course['course_name']; ?></h3>
                    <p><?php echo $course['description']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <footer>
        <p>&copy; 2024 Kursus Musik Profesional</p>
    </footer>
</body>
</html>
