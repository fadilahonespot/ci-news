<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Preview</title>
    <!-- JSZip -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <!-- unrar.js -->
    <script src="https://cdn.jsdelivr.net/npm/unrar-js@1.0.0/umd/unrar.min.js"></script>
    <!-- Mammoth.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    <!-- SheetJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <!-- Bootstrap CSS -->
    <?php include('partials/notification.php'); ?>
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

        .zip-preview,
        .rar-preview {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .zip-preview h3,
        .rar-preview h3 {
            color: #333;
            margin-bottom: 20px;
        }

        .zip-preview ul,
        .rar-preview ul {
            list-style: none;
            padding: 0;
        }

        .zip-preview li,
        .rar-preview li {
            background-color: #fff;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 3px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .zip-preview li img,
        .rar-preview li img {
            margin-right: 10px;
        }

        .zip-preview li span,
        .rar-preview li span {
            font-weight: bold;
            color: #555;
        }

        .excel-preview {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .excel-preview h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .excel-preview table {
            width: 100%;
            border-collapse: collapse;
        }

        .excel-preview th,
        .excel-preview td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .excel-preview th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .excel-preview tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .excel-preview tr:hover {
            background-color: #ddd;
        }

        .excel-preview .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <?php include('partials/navbar.php'); ?>

    <div class="preview-container">
        <?php
        $fileExtension = pathinfo($document['path'], PATHINFO_EXTENSION);

        switch ($fileExtension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
        ?>
                <!-- Preview Gambar -->
                <img src="<?= base_url('uploads/' . $document['path']) ?>" alt="Preview Image" class="preview-image">
            <?php
                break;

            case 'docx':
            case 'doc':
            ?>
                <!-- Preview DOCX -->
                <div id="docx-content" style="font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6;"></div>
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

                    var fileUrl = '<?= base_url('uploads/' . $document['path']) ?>';
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', fileUrl, true);
                    xhr.responseType = 'blob';
                    xhr.onload = function(event) {
                        var blob = xhr.response;
                        reader.readAsArrayBuffer(blob);
                    };
                    xhr.send();
                </script>
            <?php
                break;

            case 'xlsx':
            case 'xls':
            ?>
                <!-- Preview Excel -->
                <div class="excel-preview">
                    <div id="excel-content" class="table-responsive"></div>
                </div>

                <script>
                    var fileUrl = '<?= base_url('uploads/' . $document['path']) ?>';
                    var oReq = new XMLHttpRequest();
                    oReq.open("GET", fileUrl, true);
                    oReq.responseType = "arraybuffer";
                    oReq.onload = function(e) {
                        var arraybuffer = oReq.response;
                        var data = new Uint8Array(arraybuffer);
                        var arr = [];
                        for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
                        var bstr = arr.join("");
                        var workbook = XLSX.read(bstr, {
                            type: "binary"
                        });
                        var first_sheet_name = workbook.SheetNames[0];
                        var worksheet = workbook.Sheets[first_sheet_name];
                        var html = XLSX.utils.sheet_to_html(worksheet);
                        document.getElementById('excel-content').innerHTML = html;
                    }
                    oReq.send();
                </script>
            <?php
                break;

            case 'pptx':
            case 'ppt':
            ?>
                <!-- Preview PowerPoint -->
                <div id="ppt-content"></div>
                <script>
                    var fileUrl = '<?= base_url('uploads/' . $document['path']) ?>';
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', fileUrl, true);
                    xhr.responseType = 'blob';
                    xhr.onload = function(event) {
                        var blob = xhr.response;
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var arrayBuffer = reader.result;
                            var zip = new JSZip();
                            zip.loadAsync(arrayBuffer).then(function(zip) {
                                zip.forEach(function(relativePath, zipEntry) {
                                    if (relativePath.match(/ppt\/slides\/slide\d+\.xml/)) {
                                        zipEntry.async("string").then(function(content) {
                                            var parser = new DOMParser();
                                            var doc = parser.parseFromString(content, "application/xml");
                                            var textElements = doc.getElementsByTagName("a:t");
                                            var slideText = "";
                                            for (var i = 0; i < textElements.length; i++) {
                                                slideText += textElements[i].textContent + " ";
                                            }
                                            var div = document.createElement('div');
                                            div.classList.add('ppt-slide');
                                            div.textContent = slideText;
                                            document.getElementById('ppt-content').appendChild(div);
                                        });
                                    }
                                });
                            });
                        };
                        reader.readAsArrayBuffer(blob);
                    };
                    xhr.send();
                </script>
            <?php
                break;

            case 'pdf':
            ?>
                <!-- Preview PDF -->
                <iframe src="<?= base_url('uploads/' . $document['path']) ?>" width="100%" height="1000px" frameborder="0"></iframe>
            <?php
                break;

            case 'zip':
            ?>
                <!-- Preview ZIP -->
                <div class="zip-preview">
                    <h3>Contents of ZIP file:</h3>
                    <ul id="zip-file-list"></ul>
                </div>
                <script>
                    var fileUrl = '<?= base_url('uploads/' . $document['path']) ?>';
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', fileUrl, true);
                    xhr.responseType = 'blob';
                    xhr.onload = function(event) {
                        var blob = xhr.response;
                        JSZip.loadAsync(blob).then(function(zip) {
                            zip.forEach(function(relativePath, zipEntry) {
                                var li = document.createElement('li');
                                li.innerHTML = '<img src="<?= base_url('icon/file-zip.svg') ?>" alt="file icon" width="20">' + '<span>' + relativePath + '</span>';
                                document.getElementById('zip-file-list').appendChild(li);
                            });
                        }).catch(function(err) {
                            console.log(err);
                        });
                    };
                    xhr.send();
                </script>
            <?php
                break;

            case 'rar':
            ?>
                <!-- Preview RAR -->
                <div class="rar-preview">
                    <h3>Contents of RAR file:</h3>
                    <ul id="rar-file-list"></ul>
                </div>
                <script>
                    var fileUrl = '<?= base_url('uploads/' . $document['path']) ?>';
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', fileUrl, true);
                    xhr.responseType = 'blob';
                    xhr.onload = function(event) {
                        var blob = xhr.response;
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var arrayBuffer = reader.result;
                            var unrar = new Unrar(arrayBuffer);
                            unrar.getFiles().then(function(files) {
                                files.forEach(function(file) {
                                    var li = document.createElement('li');
                                    li.innerHTML = '<img src="<?= base_url('icon/file-zip.svg') ?>" alt="file icon" width="20">' + '<span>' + file.name + '</span>';
                                    document.getElementById('rar-file-list').appendChild(li);
                                });
                            }).catch(function(err) {
                                console.log(err);
                            });
                        };
                        reader.readAsArrayBuffer(blob);
                    };
                    xhr.send();
                </script>
            <?php
                break;

            default:
            ?>
                <h3>Document tidak didukung</h3>
        <?php
        }
        ?>
    </div>
</body>

</html>