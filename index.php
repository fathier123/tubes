<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <h1>Kursus Musik Profesional</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><a href="admin_dashboard.php">Admin Dashboard</a></li>  <!-- Menampilkan Admin Dashboard untuk admin -->
                        <?php else: ?>
                            <li><a href="user_dashboard.php">Dashboard</a></li>  <!-- Menampilkan Dashboard untuk user -->
                        <?php endif; ?>
                        <li><a href="jenis_kursus.php">Jenis Kursus</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Masuk</a></li>
                        <li><a href="register.php">Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <h2>Kembangkan Bakat Musikmu</h2>
                <p>Belajar musik dengan para ahli. Kursus profesional untuk semua tingkat keterampilan. Bergabunglah dan jadilah maestro!</p>
                <a href="register.php" class="btn">Bergabung Sekarang</a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <h2>Mengapa Memilih Kami?</h2>
                <div class="feature-list">
                    <div class="feature">
                        <div class="icon-bg">
                            <img src="assets/gitar.jpg" alt="Ikon Gitar">
                        </div>
                        <h3>Belajar Alat Musik Apapun</h3>
                        <p>Pilih dari berbagai kursus gitar, piano, drum, dan lainnya.</p>
                    </div>
                    <div class="feature">
                        <div class="icon-bg">
                            <img src="assets/not.jpg" alt="Ikon Not Musik">
                        </div>
                        <h3>Instruktur Profesional</h3>
                        <p>Pelajari langsung dari ahli dengan pengalaman bertahun-tahun.</p>
                    </div>
                    <div class="feature">
                        <div class="icon-bg">
                            <img src="assets/jam.jpg" alt="Ikon Jadwal">
                        </div>
                        <h3>Jadwal Fleksibel</h3>
                        <p>Belajar kapan saja sesuai waktu yang Anda tentukan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials">
            <div class="container">
                <h2>Apa Kata Siswa Kami</h2>
                <div class="testimonial-list">
                    <div class="testimonial">
                        <p>"Instruktur sangat ramah dan membantu. Belajar gitar jadi mudah dan menyenangkan!"</p>
                        <span>- Sarah A.</span>
                    </div>
                    <div class="testimonial">
                        <p>"Kursus ini membantu saya mengasah keterampilan piano saya ke level profesional."</p>
                        <span>- David R.</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Kursus Musik Profesional. Semua Hak Dilindungi.</p>
        </div>
    </footer>
</body>
</html>
