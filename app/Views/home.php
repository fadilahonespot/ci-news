<!-- app/Views/home.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include('partials/notification.php'); ?>
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
                                <input type="hidden" id="home" name="home" value="true">
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

        <div class="row">
            <div class="col-md-6 mb-5">
                <canvas id="myChart"></canvas>
            </div>
            <div class="col-md-6">
                <h2 class="mb-4">My Docs Dasboard</h2>
                <?php if ($user) : ?>
                    <h5 class="mb-4">Welcome back, <?= $user['name'] ?>!</h5>
                <?php endif; ?>
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <p class="text-muted mb-1">Total Categories</p>
                        <h4><?= $totalCategories ?></h4>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Total Documents</p>
                        <h4><?= $totalDocuments ?></h4>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Total Size</p>
                        <h4><?= $totalSize ?> MB</h4>
                    </div>
                </div>
                <!-- Modern Upload Document Button -->
                <div class="text-center">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">Upload Document</button>
                </div>
            </div>


            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Upload Button -->
                    <div>
                        <h2>List Documents</h2>
                    </div>
                    <!-- Search form -->
                    <form class="form-inline">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Tipe</th>
                            <th>Ukuran</th>
                            <th>Dibuat</th>
                            <th>Kategori</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lastDocuments as $document) : ?>
                            <tr>
                                <td><a href="<?= site_url('preview/' . $document['id']) ?>"><?= $document['judul'] ?></a></td>
                                <td><?= $document['keterangan'] ?></td>
                                <td><?= pathinfo($document['path'], PATHINFO_EXTENSION) ?></td>
                                <td><?= $document['size'] ?> </td>
                                <td><?= $document['created_at'] ?></td>
                                <td><a href="<?= site_url('category/' . $document['category_id']) ?>"><?= $document['category_name'] ?></a></td>
                                <td><a href="<?= base_url('downloads/' . $document['path']) ?>" class="btn btn-primary">Download</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($currentPage > 1) : ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $currentPage - 1 ?>">Previous</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages) : ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $currentPage + 1 ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script>
        // Data untuk grafik
        var data = {
            labels: ['Size', 'Categories', 'Documents'],
            datasets: [{
                label: 'Total',
                data: [<?= $totalSize ?>, <?= $totalCategories ?>, <?= $totalDocuments ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Konfigurasi untuk grafik
        var options = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        // Membuat grafik menggunakan Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });

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
    </script>

</body>

</html>