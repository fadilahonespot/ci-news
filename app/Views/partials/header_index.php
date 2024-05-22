<header class="bg-dark text-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">My Docs</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
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
                                <a class="dropdown-item" href="<?= base_url('/home') ?>">Dasboard</a>
                                <a class="dropdown-item" href="<?= base_url('/profile') ?>">Profile</a>
                                <a class="dropdown-item" href="<?= base_url('/logout') ?>">Logout</a>
                            </div>
                        </li>
                    <?php } else { ?>
                        <!-- Jika user belum login -->
                        <li class="nav-item">
                            <a class="nav-link btn btn-login text-white ml-2" href="<?= base_url('/loginForm') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-register text-white ml-2" href="<?= base_url('/user-form') ?>">Register</a>
                        </li>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </nav>
</header>