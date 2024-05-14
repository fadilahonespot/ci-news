<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <?php include('partials/notification.php'); ?>
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
        }

        .profile-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .profile-card h2 {
            margin-bottom: 20px;
            font-weight: 700;
            color: #333;
        }

        .profile-card p {
            margin-bottom: 10px;
            color: #555;
        }

        .profile-card strong {
            color: #000;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>

<body>
    <?php include('partials/navbar.php'); ?>

    <div class="container">
        <!-- Modal Edit Profile -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= site_url('/update') ?>" method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $user['alamat'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="no_hp">No HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $user['no_hp'] ?>">
                            </div>
                            <input type="hidden" id="id" name="id" value="<?= $user['id'] ?>">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h2>User Profile</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-picture mx-auto">
                        <img src="<?= base_url('icon/profile.svg') ?>" alt="Profile Picture">
                    </div>
                </div>
                <div class="col-md-8">
                    <p><strong>Name:</strong> <?= $user['name'] ?></p>
                    <p><strong>Email:</strong> <?= $user['email'] ?></p>
                    <p><strong>Alamat:</strong> <?= $user['alamat'] ?></p>
                    <p><strong>No HP:</strong> <?= $user['no_hp'] ?></p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#editProfileModal">Edit Profile</a>
                </div>
                <div class="col-md-6">
                    <a href="<?= site_url('/change-password') ?>" class="btn btn-danger btn-block">Ubah Password</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>