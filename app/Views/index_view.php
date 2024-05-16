<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyDocs - Layanan Penyimpanan Dokumen</title>
    <!-- Bootstrap CSS -->
    <?php include('partials/notification.php'); ?>
    <style>
        /* Section About */
        .section-about {
            padding: 80px 0;
            background-color: #f9f9f9;
        }

        .section-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .section-description {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .section-list {
            list-style-type: none;
            padding-left: 0;
        }

        .section-list li {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
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
    <?php include('partials/header_index.php'); ?>
    <section class="section-about">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="section-title">Tentang Kami</h2>
                    <p class="section-description">MyDocs adalah penyedia solusi penyimpanan dokumen dan file yang inovatif dan aman. Kami berkomitmen untuk membantu bisnis dan organisasi dalam mengelola dan menyimpan data mereka dengan efisien, sehingga mereka dapat fokus pada inti bisnis mereka tanpa khawatir tentang keamanan dan keteraturan dokumen.</p>
                    <h2 class="section-title">Keuntungan Penggunaan Penyimpanan Cloud</h2>
                    <ul class="section-list">
                        <li>Akses Dokumen Dari Mana Saja</li>
                        <li>Skalabilitas Fleksibel</li>
                        <li>Keamanan dan Cadangan Otomatis</li>
                        <li>Kolaborasi Tim yang Mudah</li>
                        <li>Hemat Biaya Infrastruktur</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h2 class="section-title">Layanan Kami</h2>
                    <ul class="section-list">
                        <li>Penyimpanan Dokumen Digital</li>
                        <li>Pencarian dan Penyortiran Otomatis</li>
                        <li>Keamanan Data</li>
                        <li>Pemulihan Bencana</li>
                    </ul>
                    <h2 class="section-title">Mengapa Memilih MyDocs?</h2>
                    <p class="section-description">Kemudahan, keamanan, dan keterjangkauan - tiga pilar yang membentuk layanan penyimpanan dokumen kami. Dengan MyDocs, Anda dapat mengakses dokumen penting dari mana saja, kapan saja, sementara kami menjaga data Anda tetap aman dan terlindungi.</p>
                    <p class="section-description">Bergabunglah dengan ribuan bisnis yang telah mempercayai MyDocs untuk mengelola dan menyimpan dokumen mereka dengan mudah dan efisien. Daftar sekarang dan rasakan perbedaannya!</p>
                </div>
            </div>
        </div>
    </section>



    <?php include('partials/footer_index.php'); ?>
</body>

</html>