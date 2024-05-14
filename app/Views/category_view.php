<!-- app/Views/categoryView.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category List</title>
    <?php include('partials/notification.php'); ?>
    <style>
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
        <!-- Modal Tambah Kategori -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form tambah kategori -->
                        <form action="<?= site_url('save-category') ?>" method="post">
                            <div class="form-group">
                                <label for="nama">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Kategori -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form edit kategori -->
                        <form action="<?= site_url('update-category') ?>" method="post">
                            <input type="hidden" name="id" id="editCategoryId">
                            <div class="form-group">
                                <label for="editNama">Nama Kategori</label>
                                <input type="text" class="form-control" id="editNama" name="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="editDeskripsi">Deskripsi</label>
                                <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Delete -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Penghapusan Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus kategori ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="#" id="deleteCategoryButton" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Tambah Kategori -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addCategoryModal">
            Tambah Kategori
        </button>

        <div class="row">
            <?php foreach ($categories as $category) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card position-relative card-hover">
                        <a href="<?= site_url('category/' . $category['id']) ?>" style="text-decoration: none; color: inherit;">
                            <div class="card-body">
                                <h5 class="card-title"><?= $category['nama'] ?></h5>
                                <p class="card-text"><?= $category['deskripsi'] ?></p>
                            </div>
                        </a>
                        <div class="card-footer">
                            <a href="#" class="editCategory" data-id="<?= $category['id'] ?>" data-nama="<?= $category['nama'] ?>" data-deskripsi="<?= $category['deskripsi'] ?>">Edit</a> |
                            <a href="#" class="deleteCategory" data-id="<?= $category['id'] ?>" data-toggle="modal" data-target="#confirmDeleteModal">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.editCategory').click(function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var deskripsi = $(this).data('deskripsi');

                $('#editCategoryId').val(id);
                $('#editNama').val(nama);
                $('#editDeskripsi').val(deskripsi);

                $('#editCategoryModal').modal('show');
            });
        });

        $(document).ready(function() {
            $('.deleteCategory').click(function() {
                var categoryId = $(this).data('id');
                $('#deleteCategoryButton').attr('href', '<?= site_url('delete-category/') ?>' + categoryId);
            });
        });
    </script>

</body>

</html>