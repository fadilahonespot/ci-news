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

        .context-menu {
            z-index: 1000;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            border-radius: 5px;
            padding: 0;
            width: 150px;
        }

        .context-menu .list-group-item {
            cursor: pointer;
        }

        .context-menu .list-group-item:hover {
            background-color: #f5f5f5;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .card-hover {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 1.25em;
            font-weight: bold;
            color: #000;
            /* Ubah warna teks judul menjadi hitam */
        }

        .card-text {
            color: #6c757d;
        }

        .card-body {
            padding: 20px;
        }

        .card-body a {
            text-decoration: none;
            color: inherit;
        }

        .card-body a:hover {
            text-decoration: none;
            color: inherit;
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
                        <form action="<?= base_url('save-category') ?>" method="post">
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
                        <form action="<?= base_url('update-category') ?>" method="post">
                            <input type="hidden" name="id" id="editCategoryId">
                            <div class="form-group">
                                <label for="editNama">Nama Kategori</label>
                                <input type="text" class="form-control" id="editNama" name="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="editDeskripsi">Deskripsi</label>
                                <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
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
                    <div class="card position-relative card-hover" id="categoryCard<?= $category['id'] ?>">
                        <a href="<?= base_url('category/' . $category['id']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $category['nama'] ?></h5>
                                <p class="card-text"><?= $category['deskripsi'] ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Menu klik kanan -->
        <div id="contextMenu" class="context-menu" style="display: none; position: absolute;">
            <ul class="list-group">
                <li class="list-group-item editCategory">Edit</li>
                <li class="list-group-item deleteCategory" data-toggle="modal" data-target="#confirmDeleteModal">Delete</li>
            </ul>
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
                $('#deleteCategoryButton').attr('href', '<?= base_url('delete-category/') ?>' + categoryId);
            });
        });

        // Menambahkan event listener untuk menangani klik kanan pada setiap card
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault(); // Mencegah konteks menu standar muncul
            var card = e.target.closest('.card'); // Mendapatkan card yang diklik
            if (card) {
                var categoryId = card.id.replace('categoryCard', ''); // Mendapatkan id kategori dari id card
                var categoryName = card.querySelector('.card-title').innerText; // Mendapatkan nama kategori
                var categoryDescription = card.querySelector('.card-text').innerText; // Mendapatkan deskripsi kategori

                // Mendapatkan referensi ke menu konteks
                var menu = document.getElementById('contextMenu');

                // Menyimpan data kategori di dalam atribut data
                var editCategory = menu.querySelector('.editCategory');
                editCategory.setAttribute('data-id', categoryId);
                editCategory.setAttribute('data-nama', categoryName);
                editCategory.setAttribute('data-deskripsi', categoryDescription);

                var deleteCategory = menu.querySelector('.deleteCategory');
                deleteCategory.setAttribute('data-id', categoryId);

                // Menampilkan menu di posisi klik
                menu.style.left = e.pageX + 'px';
                menu.style.top = e.pageY + 'px';
                menu.style.display = 'block';

                // Menghapus menu saat pengguna mengklik di luar menu
                document.addEventListener('click', function closeMenu(event) {
                    if (!menu.contains(event.target)) {
                        menu.style.display = 'none';
                        document.removeEventListener('click', closeMenu);
                    }
                });
            }
        });

        // Event listener untuk tombol Edit
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('editCategory')) {
                var categoryId = e.target.getAttribute('data-id');
                var categoryName = e.target.getAttribute('data-nama');
                var categoryDescription = e.target.getAttribute('data-deskripsi');

                // Isi form edit dengan data kategori
                document.getElementById('editDocumentId').value = categoryId;
                document.getElementById('editJudul').value = categoryName;
                document.getElementById('editKeterangan').value = categoryDescription;

                // Tampilkan modal edit
                $('#editDocumentModal').modal('show');
            }
        });

        // Event listener untuk tombol Delete
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('deleteCategory')) {
                var categoryId = e.target.getAttribute('data-id');
                // Implementasi logika untuk menghapus kategori berdasarkan categoryId
                console.log('Delete category ID:', categoryId);
            }
        });
    </script>

</body>

</html>