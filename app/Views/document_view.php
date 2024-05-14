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
                        <form id="editDocumentForm" action="<?= site_url('update-document') ?>" method="post">
                            <input type="hidden" id="editDocumentId" name="id">
                            <div class="form-group">
                                <label for="editJudul">Judul</label>
                                <input type="text" class="form-control" id="editJudul" name="judul">
                            </div>
                            <div class="form-group">
                                <label for="editKeterangan">Keterangan</label>
                                <textarea class="form-control" id="editKeterangan" name="keterangan" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
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
                        Apakah Anda yakin ingin menghapus dokument ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="#" id="deleteDocumentButton" class="btn btn-danger">Hapus</a>
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
                    <div class="card card-hover">
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
                                        case 'docx':
                                            $icon = 'docx.svg';
                                            break;
                                        case 'doc':
                                            $icon = 'doc.svg';
                                            break;
                                        case 'pdf':
                                            $icon = 'pdf.svg';
                                            break;
                                            // Add more cases for other file extensions if needed
                                        default:
                                            $icon = 'default.svg';
                                    }
                                    ?>
                                    <img src="<?= base_url('icon/' . $icon) ?>" alt="File Extension Icon" class="img-fluid" style="max-width: 50px;">
                                </div>
                                <div class="col">
                                    <h6 class="card-title"><?= $document['judul'] ?></h6>
                                    <p class="card-text text-muted"><?= $document['keterangan'] ?></p>
                                    <p class="card-text text-muted"><?= $document['created_at'] ?> | <?= pathinfo($document['path'], PATHINFO_EXTENSION) ?> | <?= $document['size'] ?> </p>
                                    <a href="<?= base_url('downloads/' . $document['path']) ?>" class="btn btn-primary mr-2" onclick="event.stopPropagation()">Download</a> |
                                    <a href="#" class="editDocument" data-toggle="modal" data-target="#editDocumentModal" data-id="<?= $document['id'] ?>" data-judul="<?= $document['judul'] ?>" data-keterangan="<?= $document['keterangan'] ?>" onclick="event.stopPropagation()">Edit</a> |
                                    <a href="#" class="deleteDocument" data-id="<?= $document['id'] ?>" data-toggle="modal" data-target="#confirmDeleteModal" onclick="event.stopPropagation()">Delete</a>
                                    <!-- Add more details about the document -->
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            <?php endforeach; ?>
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
                if (fileSize > 5) { // Check if file size exceeds 5MB
                    fileSizeMessage.textContent = 'File size exceeds the limit (5MB)';
                    uploadButton.disabled = true;
                } else {
                    fileSizeMessage.textContent = '';
                    uploadButton.disabled = false;
                }
                // Set the file size value in hidden input
                fileSizeInput.value = fileSize.toFixed(2) + 'MB';
            }
        }

        $(document).ready(function() {
            $('.editDocument').click(function() {
                var id = $(this).data('id');
                var judul = $(this).data('judul');
                var keterangan = $(this).data('keterangan');

                $('#editDocumentId').val(id);
                $('#editJudul').val(judul);
                $('#editKeterangan').val(keterangan);

                $('#editDocumentModal').modal('show');
            });
        });

        $(document).ready(function() {
            $('.deleteDocument').click(function() {
                var documentId = $(this).data('id');
                $('#deleteDocumentButton').attr('href', '<?= site_url('delete-document/') ?>' + documentId);
            });
        });

        function previewDocument(documentId) {
            // Redirect to the preview controller with document ID as parameter
            window.location.href = "<?= site_url('preview/') ?>" + documentId;
        }
    </script>

</body>

</html>