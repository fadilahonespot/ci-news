<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents</title>
    <?php include('partials/notification.php'); ?>
    <!-- Custom CSS -->
    <style>
        .file-extension {
            width: 24px;
            height: 24px;
            margin-right: 5px;
        }

        .card-hover:hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .context-menu {
            z-index: 1000;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            border-radius: 5px;
            padding: 0;
            width: 150px;
        }

        .context-menu .list-group-item {
            cursor: pointer;
        }

        .context-menu .list-group-item:hover {
            background-color: #f5f5f5;
        }

        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .card-hover:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .card-body {
            display: flex;
            align-items: center;
        }

        .file-icon {
            max-width: 54px;
            margin-right: 2px;
        }

        .permission-icon {
            width: 20px;
            height: 15px;
            margin-right: 5px;
        }

        .text-muted {
            display: flex;
            align-items: center;
            font-size: 0.9em;
        }

        .card-title {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 0.5em;
        }

        .card-text {
            margin-bottom: 0.5em;
        }
    </style>
</head>

<body>
    <?php include('partials/navbar.php'); ?>

    <div class="container mt-5">

        <!-- Upload Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Form upload dokumen -->
                        <form id="uploadForm" action="<?= site_url('upload') ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" class="form-control" id="judul" name="judul">
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="path">File</label>
                                <input type="file" class="form-control-file" id="path" name="path" onchange="checkFileSize()">
                                <small id="fileSizeMessage" class="form-text text-muted"></small>
                                <!-- Hidden input for file size -->
                                <input type="hidden" id="fileSize" name="fileSize">
                            </div>
                            <!-- Hidden input for category id -->
                            <input type="hidden" id="categoryId" name="categoryId">
                            <button type="submit" class="btn btn-primary" id="uploadButton" disabled>Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Document -->
        <div class="modal fade" id="editDocumentModal" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDocumentModalLabel">Edit Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form edit dokumen -->
                        <form id="editDocumentForm" action="<?= site_url('update-document') ?>" method="post" autocomplete="off">
                            <input type="hidden" id="editDocumentId" name="id">
                            <input type="hidden" id="editPermission" name="permission">
                            <div class="form-group">
                                <label for="editJudul">Judul:</label>
                                <input type="text" class="form-control" id="editJudul" name="judul">
                            </div>
                            <div class="form-group">
                                <label for="editKeterangan">Keterangan:</label>
                                <textarea class="form-control" id="editKeterangan" name="keterangan" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editCategory">Kategori:</label>
                                <select class="form-control" id="editCategoryId" name="categoryId">
                                    <?php foreach ($categories as $categoryList) : ?>
                                        <option value="<?= $categoryList['id'] ?>"><?= $categoryList['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <!--Delete Document Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Penghapusan Dokument</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus dokument ini? <span id="deleteDocumentName" class="font-weight-bold"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="#" id="deleteDocumentButton" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Share Document -->
        <div class="modal fade" id="shareDocumentModal" tabindex="-1" role="dialog" aria-labelledby="shareDocumentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shareDocumentModalLabel">Share Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form share dokumen -->
                        <form id="shareDocumentForm" action="<?= site_url('update-document') ?>" method="post">
                            <div class="form-group">
                                <label for="shareDocumentName">Nama</label>
                                <input type="text" class="form-control" id="shareDocumentName" name="name" readonly>
                            </div>
                            <div class="form-group">
                                <label for="shareDocumentUrl">Preview URL</label>
                                <input type="text" class="form-control" id="shareDocumentPreviewUrl" name="url" readonly>
                            </div>
                            <div class="form-group">
                                <label for="shareDocumentUrl">Download URL</label>
                                <input type="text" class="form-control" id="shareDocumentDownloadUrl" name="url" readonly>
                            </div>
                            <div class="form-group">
                                <label for="shareDocumentPermission">Izin Akses</label>
                                <select class="form-control" id="shareDocumentPermission" name="permission">
                                    <option value="1">Hanya Saya </option>
                                    <option value="2">Sesama Pengguna</option>
                                    <option value="3">Semua Orang</option>
                                </select>
                            </div>
                            <input type="hidden" id="shareDocumentId" name="id">
                            <input type="hidden" id="shareDocumentJudul" name="judul">
                            <input type="hidden" id="shareDocumentDescription" name="keterangan">
                            <input type="hidden" id="shareDocumentCategoryId" name="categoryId">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Modal -->
        <div class="modal fade" id="downloadDocumentModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="downloadModalLabel">Download Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin mengunduh dokumen ini? <span id="downloadDocumentName" class="font-weight-bold"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="#" id="downloadDocumentButton" class="btn btn-primary">Unduh</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analyze Document Modal -->
        <div class="modal fade" id="analyzeDocumentModal" tabindex="-1" role="dialog" aria-labelledby="analyzeDocumentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="analyzeDocumentModalLabel">Analyze Document</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="analysisResult"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Upload Document Section -->
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><a href="<?= site_url('/category') ?>">Category</a> > <?= $category['nama'] ?></h5>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">Upload</button>
        </div>
        <hr>

        <!-- List Documents Section -->
        <div class="row">
            <?php foreach ($documents as $document) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card card-hover" data-category-id="<?= $document['category_id'] ?>" data-id="<?= $document['id'] ?>" data-judul="<?= $document['judul'] ?>" data-keterangan="<?= $document['keterangan'] ?>" data-path="<?= $document['path'] ?>" data-permission="<?= $document['permission'] ?>">
                        <div class="card-body" onclick="previewDocument(<?= $document['id'] ?>)">
                            <div class="row align-items-center">
                                <!-- File Extension Icon -->
                                <div class="col-auto">
                                    <?php
                                    $extension = pathinfo($document['path'], PATHINFO_EXTENSION);
                                    $icon = '';
                                    switch ($extension) {
                                        case 'png':
                                            $icon = 'png.svg';
                                            break;
                                        case 'jpg':
                                        case 'jpeg':
                                            $icon = 'jpg.svg';
                                            break;
                                        case 'gif':
                                            $icon = 'gif.svg';
                                            break;
                                        case 'doc':
                                        case 'docx':
                                            $icon = 'doc.svg';
                                            break;
                                        case 'pdf':
                                            $icon = 'pdf.svg';
                                            break;
                                        case 'zip':
                                            $icon = 'zip.svg';
                                            break;
                                        case 'rar':
                                            $icon = 'rar.svg';
                                            break;
                                        case 'xlsx':
                                        case 'xls':
                                            $icon = 'xls.svg';
                                            break;
                                        case 'ppt':
                                        case 'pptx':
                                            $icon = 'ppt.svg';
                                            break;
                                        default:
                                            $icon = 'default.svg';
                                    }
                                    ?>
                                    <img src="<?= base_url('icon/' . $icon) ?>" alt="File Extension Icon" class="file-icon img-fluid">
                                </div>
                                <?php
                                $permission = $document['permission'];
                                $iconPermission = '';
                                switch ($permission) {
                                    case '1':
                                        $iconPermission = 'private.svg';
                                        break;
                                    case '2':
                                        $iconPermission = 'member.svg';
                                        break;
                                    case '3':
                                        $iconPermission = 'public.svg';
                                        break;
                                }
                                ?>
                                <div class="col">
                                    <h6 class="card-title"><?= $document['judul'] ?></h6>
                                    <p class="card-text"><?= $document['keterangan'] ?></p>
                                    <p class="card-text text-muted">
                                        <img src="<?= base_url('icon/' . $iconPermission) ?>" alt="Permission Icon" class="permission-icon">
                                        <?= $document['created_at'] ?> | <?= $extension ?> | <?= $document['size'] ?>
                                    </p>
                                    <!-- Add more details about the document -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Menu klik kanan -->
        <div id="contextMenu" class="context-menu" style="display: none; position: absolute;">
            <ul class="list-group">
                <li class="list-group-item downloadDocument" data-toggle="modal" data-target="#downloadDocumentModal">Download</li>
                <li class="list-group-item shareDocument" data-toggle="modal" data-target="#shareDocumentModal">Share</li>
                <li class="list-group-item editDocument" data-toggle="modal" data-target="#editDocumentModal">Edit</li>
                <li class="list-group-item deleteDocument" data-toggle="modal" data-target="#confirmDeleteModal">Delete</li>
                <li class="list-group-item analyzeDocument" data-toggle="modal" data-target="#analyzeDocumentModal">Analyze</li>
            </ul>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($currentPage > 1) : ?>
                    <li class="page-item"><a class="page-link" href="?category_id=<?= $categoryId ?>&page=<?= $currentPage - 1 ?>">Previous</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>"><a class="page-link" href="?category_id=<?= $categoryId ?>&page=<?= $i ?>"><?= $i ?></a></li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages) : ?>
                    <li class="page-item"><a class="page-link" href="?category_id=<?= $categoryId ?>&page=<?= $currentPage + 1 ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script>
        // Function to extract category id from URL parameters
        function getCategoryIdFromURL() {
            const url = window.location.href;
            const urlParts = url.split('/');
            return urlParts[urlParts.length - 1]; // Last part of the URL
        }

        // Function to set category id
        function setCategoryId() {
            const categoryId = getCategoryIdFromURL();
            document.getElementById('categoryId').value = categoryId;
        }

        // Call setCategoryId function when the page loads
        window.onload = setCategoryId;

        function checkFileSize() {
            var fileInput = document.getElementById('path');
            var fileSizeMessage = document.getElementById('fileSizeMessage');
            var uploadButton = document.getElementById('uploadButton');
            var fileSizeInput = document.getElementById('fileSize');

            if (fileInput.files.length > 0) {
                var fileSize = fileInput.files[0].size / 1024 / 1024; // Convert to MB
                if (fileSize > 10) { // Check if file size exceeds 5MB
                    fileSizeMessage.textContent = 'File size exceeds the limit (10MB)';
                    uploadButton.disabled = true;
                } else {
                    fileSizeMessage.textContent = '';
                    uploadButton.disabled = false;
                }
                // Set the file size value in hidden input
                fileSizeInput.value = fileSize.toFixed(2) + 'MB';
            }
        }

        function previewDocument(documentId) {
            // Redirect to the preview controller with document ID as parameter
            window.location.href = "<?= site_url('preview/') ?>" + documentId;
        }

        document.addEventListener('contextmenu', function(e) {
            e.preventDefault(); // Mencegah konteks menu standar muncul
            var card = e.target.closest('.card'); // Mendapatkan card yang diklik
            if (card) {
                var documentId = card.getAttribute('data-id'); // Mendapatkan id dokumen dari id card
                var documentTitle = card.querySelector('.card-title').innerText; // Mendapatkan judul dokumen
                var documentDescription = card.querySelector('.card-text').innerText; // Mendapatkan deskripsi dokumen
                var documentCategoryId = card.getAttribute('data-category-id'); // Mendapatkan category_id dokumen
                var documentPath = card.getAttribute('data-path'); // mendapatkan path dokumen
                var documentPermission = parseInt(card.getAttribute('data-permission')); // Mendapatkan permission dokumen sebagai integer

                // Mendapatkan referensi ke menu konteks
                var menu = document.getElementById('contextMenu');

                // Menyimpan data dokumen di dalam atribut data
                var downloadDocument = menu.querySelector('.downloadDocument');
                downloadDocument.setAttribute('data-path', documentPath);
                downloadDocument.setAttribute('data-name', documentTitle);

                var editDocument = menu.querySelector('.editDocument');
                editDocument.setAttribute('data-id', documentId);
                editDocument.setAttribute('data-judul', documentTitle);
                editDocument.setAttribute('data-keterangan', documentDescription);
                editDocument.setAttribute('data-category-id', documentCategoryId);
                editDocument.setAttribute('data-permission', documentPermission);

                var deleteDocument = menu.querySelector('.deleteDocument');
                deleteDocument.setAttribute('data-id', documentId);
                deleteDocument.setAttribute('data-name', documentTitle);

                var analizeDocument = menu.querySelector('.analyzeDocument');
                analizeDocument.setAttribute('data-id', documentId);

                var shareDocument = menu.querySelector('.shareDocument');
                shareDocument.setAttribute('data-id', documentId);
                shareDocument.setAttribute('data-title', documentTitle);
                shareDocument.setAttribute('data-description', documentDescription);
                shareDocument.setAttribute('data-category-id', documentCategoryId);
                shareDocument.setAttribute('data-path', documentPath);
                shareDocument.setAttribute('data-permission', documentPermission);

                // Menampilkan menu di posisi klik
                menu.style.left = e.pageX + 'px';
                menu.style.top = e.pageY + 'px';
                menu.style.display = 'block';

                // Menghapus menu saat pengguna mengklik di luar menu
                document.addEventListener('click', function closeMenu(event) {
                    if (!menu.contains(event.target)) {
                        menu.style.display = 'none';
                        document.removeEventListener('click', closeMenu);
                    }
                });
            }
        });

        // Event listener untuk tombol Edit
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('editDocument')) {
                var documentId = e.target.getAttribute('data-id');
                var documentTitle = e.target.getAttribute('data-judul');
                var documentDescription = e.target.getAttribute('data-keterangan');
                var documentCategoryId = e.target.getAttribute('data-category-id');
                var documentPermission = e.target.getAttribute('data-permission');

                // Isi form edit dengan data dokumen
                document.getElementById('editDocumentId').value = documentId;
                document.getElementById('editJudul').value = documentTitle;
                document.getElementById('editKeterangan').value = documentDescription;
                document.getElementById('editCategoryId').value = documentCategoryId;
                document.getElementById('editPermission').value = documentPermission;

                // Tampilkan modal edit
                $('#editDocumentModal').modal('show');
            }
        });

        // Event listener untuk tombol Download
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('downloadDocument')) {
                var documentPath = e.target.getAttribute('data-path');
                var documentName = e.target.getAttribute('data-name');

                var downloadButton = document.getElementById('downloadDocumentButton');
                downloadButton.setAttribute('href', '<?= site_url('downloads/') ?>' + documentPath);

                document.getElementById('downloadDocumentName').textContent = documentName;

                // Menambahkan event listener untuk menutup modal saat tombol unduh diklik
                downloadButton.addEventListener('click', function() {
                    $('#downloadDocumentModal').modal('hide');
                }, {
                    once: true
                });
            }
        });

        // Event listener untuk tombol Delete
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('deleteDocument')) {
                var documentId = e.target.getAttribute('data-id');
                var documentName = e.target.getAttribute('data-name');

                // Implementasi logika untuk menghapus dokumen berdasarkan documentId
                document.getElementById('deleteDocumentButton').setAttribute('href', '<?= site_url('delete-document/') ?>' + documentId);

                document.getElementById('deleteDocumentName').textContent = documentName;
                $('#confirmDeleteModal').modal('show');
            }
        });

        // Listener untuk membuka modal share dokumen
        document.querySelector('.shareDocument').addEventListener('click', function(e) {
            var documentId = parseInt(e.target.getAttribute('data-id'));
            var documentName = e.target.getAttribute('data-title');
            var documentDescription = e.target.getAttribute('data-description');
            var documentCategoryId = e.target.getAttribute('data-category-id');
            var documentPath = e.target.getAttribute('data-path');
            var documentPermission = parseInt(e.target.getAttribute('data-permission'));

            // Set data ke modal
            document.getElementById('shareDocumentId').value = documentId;
            document.getElementById('shareDocumentName').value = documentName;
            document.getElementById('shareDocumentDownloadUrl').value = "<?= base_url('downloads') ?>/" + documentPath;
            document.getElementById('shareDocumentPreviewUrl').value = "<?= base_url('preview') ?>/" + documentId;
            document.getElementById('shareDocumentDescription').value = documentDescription;
            document.getElementById('shareDocumentCategoryId').value = documentCategoryId;
            document.getElementById('shareDocumentJudul').value = documentName;

            // Set the selected option based on the document permission
            var permissionSelect = document.getElementById('shareDocumentPermission');
            for (var i = 0; i < permissionSelect.options.length; i++) {
                if (parseInt(permissionSelect.options[i].value) === documentPermission) {
                    permissionSelect.selectedIndex = i;
                    break;
                }
            }

            // Tampilkan modal
            $('#shareDocumentModal').modal('show');
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('analyzeDocument')) {
                var documentId = e.target.getAttribute('data-id');

                // Display loading message
                document.getElementById('analysisResult').innerHTML = "Analyzing document, please wait...";

                // Call the API to analyze the document
                fetch('<?= base_url('analyze-document') ?>/' + documentId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('API response data:', data); // Log the response data
                        if (data.analysis) {
                            document.getElementById('analysisResult').innerHTML = data.analysis;
                        } else if (data.error) {
                            document.getElementById('analysisResult').innerHTML = data.error;
                        } else {
                            document.getElementById('analysisResult').innerHTML = "An unexpected error occurred.";
                        }
                    })
                    .catch(error => {
                        console.error('Error analyzing document:', error);
                        document.getElementById('analysisResult').innerHTML = "An error occurred while analyzing the document.";
                    });

                // Show the modal
                $('#analyzeDocumentModal').modal('show');
            }
        });
    </script>

</body>

</html>