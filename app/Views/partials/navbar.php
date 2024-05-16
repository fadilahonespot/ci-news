<style>
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
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('/') ?>">My Docs</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/home') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/user-view') ?>">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/category') ?>">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('/uploadForm') ?>">Document</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php
                $session = session();
                $userSession = $session->get('user');
                if ($userSession !== null) { ?>
                    <!-- Jika user telah login -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $userSession['name'] ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?= site_url('/home') ?>">Dasboard</a>
                            <a class="dropdown-item" href="<?= site_url('/profile') ?>">Profile</a>
                            <a class="dropdown-item" href="<?= site_url('/logout') ?>">Logout</a>
                        </div>
                    </li>
                <?php } else { ?>
                    <!-- Jika user belum login -->
                    <li class="nav-item">
                        <a class="nav-link btn btn-login text-white ml-2" href="<?= site_url('/loginForm') ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-register text-white ml-2" href="<?= site_url('/user-form') ?>">Register</a>
                    </li>
                <?php } ?>
            </ul>

        </div>
    </div>
</nav>