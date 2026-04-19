<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Tabunganku</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f7fe;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #0b0b3b;
            padding: 15px 5%;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
        }

        .logo-area img {
            height: 30px;
        }

        .nav-right {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .hero {
            background: white;
            padding: 80px 5%;
            text-align: center;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .hero h1 {
            color: #0000c2;
            font-size: 38px;
            margin-bottom: 20px;
            font-weight: 800;
        }

        .hero p {
            color: #64748b;
            max-width: 600px;
            margin: 0 auto 30px;
            line-height: 1.6;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-daftar {
            background-color: #0b0b3b;
            color: white;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 0; 
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-masuk {
            background-color: white;
            color: #0b0b3b;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 0;
            font-weight: bold;
            border: 2px solid #0b0b3b;
            transition: 0.3s;
        }

        .preview-section {
            padding: 50px 5%;
            text-align: center;
            flex: 1;
        }

        .preview-card {
            background: white;
            max-width: 400px;
            margin: 0 auto;
            padding: 25px;
            border-radius: 0;
            text-align: left;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .bar-bg {
            background: #edf2f7;
            height: 12px;
            border-radius: 0;
            overflow: hidden;
            margin: 15px 0 10px;
        }

        .bar-fill {
            background: #2ecc71;
            height: 100%;
            width: 70%;
        }

        .footer {
            background-color: white;
            padding: 25px 5%;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            border-top: 1px solid #e2e8f0;
            margin-top: 40px;
        }

        @media (max-width: 600px) {
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-area">
            <img src="TABUNG.png" alt="Logo">
            <span>TABUNGANKU</span>
        </div>
        <div class="nav-right">BERANDA</div>
    </nav>

    <main class="preview-section">
        <section class="hero">
            <h1>Selamat Datang di Rencana Tabungan</h1>
            <p>Bantu kamu merencanakan tabungan, memantau progres, dan mencapai tujuan finansial secara bertahap dan mudah.</p>
            <div class="btn-group">
                <a href="register.php" class="btn-daftar">Daftar Sekarang</a>
                <a href="login.php" class="btn-masuk">Masuk</a>
            </div>
        </section>

        <div style="margin-top: 50px;">
            <p style="font-weight: 800; color: #0b0b3b;">Contoh Progres Tabungan</p>
            <div class="preview-card">
                <b style="color: #0b0b3b;">Liburan ke Bali</b>
                <div class="bar-bg">
                    <div class="bar-fill"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold;">
                    <span style="color: #2ecc71;">Rp 3.500.000 (70%)</span>
                    <span style="color: #94a3b8;">Target: Rp 5.000.000</span>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        &copy; 2026 <b>Tabunganku</b> - Kelola Finansial Jadi Lebih Menyenangkan.
    </footer>

</body>
</html>