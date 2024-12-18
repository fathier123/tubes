<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <h1>Selamat Datang di Platform Kami</h1>
            <!-- Navigasi -->
            <nav>
                <ul id="nav-links">
                    <!-- Placeholder untuk menu dinamis -->
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Selamat Datang!</h2>
        <p>Eksplorasi fitur dan layanan terbaik kami.</p>
        <a href="#cards-section" class="btn">Mulai Sekarang</a>
    </section>

    <!-- Card Navigation -->
    <section id="cards-section" class="jenis-kursus">
        <h2>Fitur Kami</h2>
        <div class="kursus-list">
            <!-- Card Jenis Kursus -->
            <div class="kursus-item">
                <div class="kursus-icon">
                    <img src="jenis_kursus_icon.png" alt="Jenis Kursus">
                </div>
                <h3>Jenis Kursus</h3>
                <p>Jelajahi kursus menarik yang kami tawarkan.</p>
                <a href="jenis_kursus.html" class="btn">Lihat Detail</a>
            </div>
            <!-- Card About Us -->
            <div class="kursus-item">
                <div class="kursus-icon">
                    <img src="about_us_icon.png" alt="Tentang Kami">
                </div>
                <h3>Tentang Kami</h3>
                <p>Pelajari lebih lanjut tentang platform kami.</p>
                <a href="about_us.html" class="btn">Lihat Detail</a>
            </div>
            <!-- Card Dashboard -->
            <div class="kursus-item">
                <div class="kursus-icon">
                    <img src="dashboard_icon.png" alt="Dashboard">
                </div>
                <h3>Dashboard</h3>
                <p>Akses ke dashboard pribadi Anda.</p>
                <a href="dashboard.html" class="btn">Lihat Detail</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Platform Kami. Semua Hak Dilindungi.</p>
    </footer>

    <script>
        // Simulasi Role: Ganti 'admin' atau 'user' sesuai role
        const userRole = 'user'; // Ganti menjadi 'admin' untuk role admin
        
        // Navigasi berdasarkan role
        const navLinks = document.getElementById('nav-links');
        if (userRole === 'admin') {
            navLinks.innerHTML = `
                <li><a href="dashboard.html">Dashboard</a></li>
                <li><a href="manage_users.html">Kelola Pengguna</a></li>
                <li><a href="laporan.html">Laporan</a></li>
                <li><a href="logout.html">Keluar</a></li>
            `;
        } else if (userRole === 'user') {
            navLinks.innerHTML = `
                <li><a href="jenis_kursus.html">Jenis Kursus</a></li>
                <li><a href="about_us.html">Tentang Kami</a></li>
                <li><a href="dashboard.html">Dashboard</a></li>
                <li><a href="logout.html">Keluar</a></li>
            `;
        }
    </script>
</body>
</html>
