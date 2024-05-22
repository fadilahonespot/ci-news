<!-- app/Views/upload_form.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Document</title>
</head>

<body>
    <?php include('partials/notification.php'); ?>
    <?php include('partials/navbar.php'); ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2>Upload Document</h2>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                    Upload Document
                </button>

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
                                <form id="uploadForm" action="<?= base_url('upload') ?>" method="post" enctype="multipart/form-data">
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
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select class="form-control" id="categoryId" name="categoryId">
                                            <?php foreach ($categories as $category) : ?>
                                                <option value="<?= $category['id'] ?>"><?= $category['nama'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="uploadButton" disabled>Upload</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <hr>
                <!-- Daftar dokumen -->
                <h2>List Documents</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th>Category</th>
                            <th>Dibuat</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document) : ?>
                            <tr>
                                <td><?= $document['judul'] ?></td>
                                <td><?= $document['keterangan'] ?></td>
                                <td><?= $document['category_id'] ?></td>
                                <td><?= $document['created_at'] ?></td>
                                <td><a href="<?= base_url('downloads/' . $document['path']) ?>" class="btn btn-primary">Download</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Pagination -->
                <?= $pager->links() ?>
            </div>
        </div>
    </div>

    <script>
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
    </script>

</body>

</html>