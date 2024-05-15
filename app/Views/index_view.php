<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyDocs - Layanan Penyimpanan Dokumen</title>
    <!-- Bootstrap CSS -->
    <?php include('partials/notification.php'); ?>
    <style>
        /* Tambahkan CSS tambahan di sini jika diperlukan */
        .content-section {
            padding: 60px 0;
        }

        .content-section h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .content-section p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
        }

        .content-section ul {
            padding-left: 20px;
            list-style: none;
        }

        .content-section ul li {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 10px;
            position: relative;
            padding-left: 25px;
        }

        .content-section ul li:before {
            content: "\2022";
            color: #007bff;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
            position: absolute;
            left: 0;
        }

        .btn-login {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-login:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-register {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-register:hover {
            background-color: #218838;
            border-color: #218838;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">MyDocs</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Tentang Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Layanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Hubungi Kami</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-login text-white mr-2" href="<?= site_url('/loginForm') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-register text-white mr-2" href="<?= site_url('/user-form') ?>">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container content-section">
        <div class="row">
            <div class="col-md-6">
                <h2>Tentang Kami</h2>
                <p>MyDocs adalah penyedia solusi penyimpanan dokumen dan file yang inovatif dan aman. Kami berkomitmen untuk membantu bisnis dan organisasi dalam mengelola dan menyimpan data mereka dengan efisien, sehingga mereka dapat fokus pada inti bisnis mereka tanpa khawatir tentang keamanan dan keteraturan dokumen.</p>
                <h2>Keuntungan Penggunaan Penyimpanan Cloud</h2>
                <ul>
                    <li>Akses Dokumen Dari Mana Saja</li>
                    <li>Skalabilitas Fleksibel</li>
                    <li>Keamanan dan Cadangan Otomatis</li>
                    <li>Kolaborasi Tim yang Mudah</li>
                    <li>Hemat Biaya Infrastruktur</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h2>Layanan Kami</h2>
                <ul>
                    <li>Penyimpanan Dokumen Digital</li>
                    <li>Pencarian dan Penyortiran Otomatis</li>
                    <li>Keamanan Data</li>
                    <li>Pemulihan Bencana</li>
                </ul>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2024 MyDocs. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </footer>

</body>

</html>