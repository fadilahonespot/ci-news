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
        <div class="row">
            <div class="col-md-6 mb-5">
                <canvas id="myChart"></canvas>
            </div>
            <div class="col-md-6">
                <?php if ($user) : ?>
                    <h2>Welcome back, <?= $user['name'] ?>!</h2>
                <?php endif; ?>
                <h2>Welcome to My Docs</h2>
                <p>Total Users: <?= $totalUsers ?></p>
                <p>Total Categories: <?= $totalCategories ?></p>
                <p>Total Documents: <?= $totalDocuments ?></p>
                <p>Total Size: <?= $totalSize ?> MB</p>
            </div>
            <div class="col-md-12">
                <h2>Last Documents</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th>Path</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lastDocuments as $document) : ?>
                            <tr>
                                <td><?= $document['id'] ?></td>
                                <td><?= $document['judul'] ?></td>
                                <td><?= $document['keterangan'] ?></td>
                                <td><?= $document['path'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Data untuk grafik
        var data = {
            labels: ['Users', 'Categories', 'Documents'],
            datasets: [{
                label: 'Total',
                data: [<?= $totalUsers ?>, <?= $totalCategories ?>, <?= $totalDocuments ?>],
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
    </script>

</body>

</html>