<style>
    .navbar {
        background-color: #343a40;
    }

    .navbar-brand,
    .nav-link {
        color: #fff !important;
    }

    .navbar-brand {
        font-weight: bold;
        font-size: 1.5em;
    }

    .nav-link {
        margin-right: 15px;
    }

    .nav-link:hover {
        color: #adb5bd !important;
    }

    .btn-login,
    .btn-register {
        color: #fff !important;
        margin-left: 10px;
        border-radius: 20px;
        padding: 5px 15px;
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

    .dropdown-menu {
        background-color: #343a40;
    }

    .dropdown-item {
        color: #fff !important;
    }

    .dropdown-item:hover {
        background-color: #495057;
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>">My Docs</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/home') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/category') ?>">Category</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php
                $session = session();
                $userSession = $session->get('user');
                if ($userSession !== null) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $userSession['name'] ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?= base_url('/home') ?>">Dashboard</a>
                            <a class="dropdown-item" href="<?= base_url('/profile') ?>">Profile</a>
                            <a class="dropdown-item" href="<?= base_url('/logout') ?>">Logout</a>
                        </div>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-login" href="<?= base_url('/loginForm') ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-register" href="<?= base_url('/user-form') ?>">Register</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>