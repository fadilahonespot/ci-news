<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">My Docs</a>
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
                <li class="nav-item dropdown">
                    <?php
                    $session = session();
                    $userSession = $session->get('user');
                    ?>
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $userSession['name'] ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= site_url('/profile') ?>">Profile</a>
                        <a class="dropdown-item" href="<?= site_url('/logout') ?>">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>