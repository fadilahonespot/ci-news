<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Perubahan CSS */
    .toast {
        width: 350px;
        position: fixed;
        bottom: 20px;
        right: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Tambahkan shadow */
        z-index: 1000;
    }

    .toast-header {
        color: #000;
        /* Warna teks header */
        border-bottom: none;
        border-radius: 0.35rem 0.35rem 0 0;
        background-color: #fff;
        /* Warna hijau muda untuk sukses */
    }

    .toast.bg-success {
        background-color: #6ed3cf;
        /* Warna hijau muda untuk sukses */
    }

    .toast.bg-danger {
        background-color: #f7688d;
        /* Warna merah muda untuk error */
    }

    .toast-body {
        padding: 1rem;
        color: #fff;
        /* Warna teks body */
    }
</style>

<!-- Pesan sukses -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="toast bg-success" role="alert" aria-live="assertive" aria-atomic="true" data-delay="7000">
        <div class="toast-header">
            <img src="<?= base_url('icon/success.svg') ?>" alt="notif success" style="width: 20px; height: 20px; margin-right: 10px;">
            <strong class="mr-auto">Sukses!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <?= session()->getFlashdata('success') ?>
        </div>
    </div>
<?php endif; ?>

<!-- Pesan kesalahan validasi -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="toast bg-danger" role="alert" aria-live="assertive" aria-atomic="true" data-delay="7000" style="position: fixed; bottom: 20px; right: 20px;">
        <div class="toast-header">
            <img src="<?= base_url('icon/error.svg') ?>" alt="notif error" style="width: 20px; height: 20px; margin-right: 10px;">
            <strong class="mr-auto">Kesalahan!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <?= session()->getFlashdata('error') ?>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="toast bg-danger" role="alert" aria-live="assertive" aria-atomic="true" data-delay="7000" style="position: fixed; bottom: 20px; right: 20px;">
        <div class="toast-header">
            <img src="<?= base_url('icon/error.svg') ?>" alt="notif error" style="width: 20px; height: 20px; margin-right: 10px;">
            <strong class="mr-auto">Kesalahan!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi toast
        $('.toast').toast('show');
    });
</script>