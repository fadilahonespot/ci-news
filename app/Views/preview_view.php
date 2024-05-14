<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Preview</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #docx-content {
            width: 100%;
            max-width: 800px;
            /* Sesuaikan dengan lebar yang diinginkan */
            margin: 20px auto;
            /* Menengahkan card di halaman */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Menambahkan bayangan pada card */
            background-color: #fff;
            /* Warna latar belakang card */
            border-radius: 8px;
            /* Sudut border card */
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }

        @media print {
            #docx-content {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    <?php include('partials/navbar.php'); ?>

    <?php
    $fileExtension = pathinfo($document['path'], PATHINFO_EXTENSION);

    switch ($fileExtension) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
    ?>
            <!-- Preview Gambar -->
            <div style="text-align: center;">
                <img src="<?= base_url('uploads/' . $document['path']) ?>" alt="Preview Image" width="500" style="display: inline-block;">
            </div>
        <?php
            break;

        case 'docx':
        case 'doc':
        ?>
            <div id="docx-content" style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6;"></div>
            <script src="<?= base_url('js/mammoth.browser.min.js') ?>"></script>
            <script>
                var reader = new FileReader();
                reader.onload = function(event) {
                    var arrayBuffer = reader.result;
                    mammoth.convertToHtml({
                            arrayBuffer: arrayBuffer
                        })
                        .then(function(result) {
                            document.getElementById('docx-content').innerHTML = result.value;
                        })
                        .catch(function(err) {
                            console.log(err);
                        });
                };

                // Ganti URL dengan URL lengkap ke file dokumen Anda
                var fileUrl = '<?= base_url('uploads/' . $document['path']) ?>';

                // Buat permintaan HTTP untuk mendapatkan file
                var xhr = new XMLHttpRequest();
                xhr.open('GET', fileUrl, true);
                xhr.responseType = 'blob'; // Set tipe respons menjadi blob
                xhr.onload = function(event) {
                    var blob = xhr.response;
                    reader.readAsArrayBuffer(blob); // Membaca file sebagai array buffer
                };
                xhr.send();
            </script>

        <?php
            break;

        case 'pdf':
        ?>
            <!-- Preview PDF -->
            <div>
                <!-- Placeholder for PDF Preview -->
                <iframe src="<?= base_url('uploads/' . $document['path']) ?>" width="100%" height="1000px" frameborder="0"></iframe>
            </div>
        <?php
            break;

        default:
        ?>
            <div>
                <h3>Document tidak didukung</h3>
            </div>
    <?php
    }
    ?>
</body>

</html>