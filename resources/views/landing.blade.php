<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Barang - Kemenkumham Provinsi Kepulauan Riau</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e3a8a;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .header-text h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .header-text p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .login-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: white;
            color: #1e3a8a;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 4rem 0;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .hero h2 {
            font-size: 2.5rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: #1e3a8a;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: #1e3a8a;
            border: 2px solid #1e3a8a;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #1e3a8a;
            color: white;
        }

        /* Features Section */
        .features {
            padding: 4rem 0;
            background: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h3 {
            font-size: 2rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.1rem;
            color: #64748b;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .feature-card h4 {
            font-size: 1.3rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 3rem 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h4 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* About Section */
        .about {
            padding: 4rem 0;
            background: #f8fafc;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .about-text h3 {
            font-size: 2rem;
            color: #1e3a8a;
            margin-bottom: 1.5rem;
        }

        .about-text p {
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .about-features {
            list-style: none;
        }

        .about-features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            color: #374151;
        }

        .about-features i {
            color: #10b981;
            font-size: 1.2rem;
        }

        .about-image {
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-image i {
            font-size: 8rem;
            color: #1e3a8a;
            opacity: 0.3;
        }

        /* Footer */
        .footer {
            background: #1f2937;
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #f9fafb;
        }

        .footer-section p,
        .footer-section li {
            color: #d1d5db;
            margin-bottom: 0.5rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #3b82f6;
        }

        .footer-bottom {
            border-top: 1px solid #374151;
            padding-top: 1rem;
            text-align: center;
            color: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .hero h2 {
                font-size: 2rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .about-content {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .hero h2 {
                font-size: 1.75rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="header-text">
                    <h1>KEMENKUMHAM KEPRI</h1>
                    <p>Sistem Inventaris Barang</p>
                </div>
            </div>
            <a href="/admin" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Masuk Sistem
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Sistem Inventaris Barang Digital</h2>
            <p>Solusi modern untuk pengelolaan inventaris barang Kementerian Hukum dan HAM Provinsi Kepulauan Riau. Kelola aset dengan efisien, transparan, dan akuntabel.</p>
            <div class="cta-buttons">
                <a href="/admin" class="btn-primary">
                    <i class="fas fa-rocket"></i> Mulai Sekarang
                </a>
                <a href="#features" class="btn-secondary">
                    <i class="fas fa-info-circle"></i> Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-title">
                <h3>Fitur Unggulan</h3>
                <p>Sistem yang dirancang khusus untuk kebutuhan inventaris instansi pemerintah</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h4>Manajemen Stok</h4>
                    <p>Kelola stok barang secara real-time dengan sistem tracking yang akurat dan otomatis.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Laporan Komprehensif</h4>
                    <p>Generate laporan inventaris yang detail dan dapat dipertanggungjawabkan untuk audit.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Multi-User Access</h4>
                    <p>Sistem role-based yang memungkinkan akses bertingkat sesuai dengan jabatan dan tanggung jawab.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Keamanan Data</h4>
                    <p>Perlindungan data tingkat enterprise dengan enkripsi dan backup otomatis.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Responsive Design</h4>
                    <p>Akses sistem dari berbagai perangkat - desktop, tablet, maupun smartphone.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h4>Otomatisasi Proses</h4>
                    <p>Workflow otomatis untuk pengadaan, distribusi, dan pemeliharaan barang inventaris.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h4>100%</h4>
                    <p>Akurasi Data</p>
                </div>
                <div class="stat-item">
                    <h4>24/7</h4>
                    <p>Akses Sistem</p>
                </div>
                <div class="stat-item">
                    <h4>99.9%</h4>
                    <p>Uptime Server</p>
                </div>
                <div class="stat-item">
                    <h4>ISO 27001</h4>
                    <p>Standar Keamanan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h3>Mengapa Memilih Sistem Kami?</h3>
                    <p>Sistem Inventaris Barang Kemenkumham Kepri dikembangkan dengan standar pemerintahan yang tinggi, mengutamakan transparansi, akuntabilitas, dan efisiensi dalam pengelolaan aset negara.</p>
                    <p>Dengan teknologi terdepan dan interface yang user-friendly, sistem ini membantu pegawai dalam melaksanakan tugas inventarisasi dengan lebih mudah dan akurat.</p>
                    <ul class="about-features">
                        <li><i class="fas fa-check-circle"></i> Compliance dengan regulasi pemerintah</li>
                        <li><i class="fas fa-check-circle"></i> Audit trail lengkap untuk setiap transaksi</li>
                        <li><i class="fas fa-check-circle"></i> Integration dengan sistem keuangan</li>
                        <li><i class="fas fa-check-circle"></i> Support dan maintenance berkelanjutan</li>
                    </ul>
                </div>
                <div class="about-image">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Kementerian Hukum dan HAM</h4>
                    <p>Kantor Wilayah Provinsi Kepulauan Riau</p>
                    <p>Jl. Teuku Umar No. 1, Tanjungpinang</p>
                    <p>Kepulauan Riau 29111</p>
                </div>
                <div class="footer-section">
                    <h4>Kontak</h4>
                    <p><i class="fas fa-phone"></i> (0771) 21234</p>
                    <p><i class="fas fa-envelope"></i> kepri@kemenkumham.go.id</p>
                    <p><i class="fas fa-globe"></i> www.kemenkumham.go.id</p>
                </div>
                <div class="footer-section">
                    <h4>Layanan</h4>
                    <ul>
                        <li><a href="#">Inventaris Barang</a></li>
                        <li><a href="#">Laporan Aset</a></li>
                        <li><a href="#">Pengadaan Barang</a></li>
                        <li><a href="#">Maintenance</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Informasi</h4>
                    <ul>
                        <li><a href="#">Panduan Penggunaan</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Kementerian Hukum dan HAM Republik Indonesia. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add scroll effect to header
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
            } else {
                header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            }
        });
    </script>
</body>
</html>