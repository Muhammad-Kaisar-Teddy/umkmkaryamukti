<?php
session_start();
include 'produk_db.php';

if (!isset($_SESSION['session_username'])) {
    header("Location: LebungFarm/login.php");
    exit();
}

// Tambah produk
$error = '';
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $link = $_POST['link'];
    $rating = $_POST['rating'];
    $nama_pemilik = $_POST['nama_pemilik'];
    $no_telepon = $_POST['no_telepon'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = $_FILES['gambar']['name'];
        $targetDir = "images/";
    
        // Pastikan folder images/ ada
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
    
        $targetFilePath = $targetDir . basename($gambar);
    
        if (is_uploaded_file($_FILES['gambar']['tmp_name'])) {
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFilePath)) {
                tambahProduk($judul, $deskripsi, $gambar, $link, $rating, $nama_pemilik, $no_telepon);
                header("Location: adm_dsh_KatalogProduk.php");
                exit();
            } else {
                $error = "Gagal memindahkan file ke folder tujuan.";
            }
        } else {
            $error = "File tidak diunggah dengan benar.";
        }
    } else {
        $error = "Tidak ada gambar yang diupload atau ada kesalahan.";
    }
    
}

// Hapus produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    hapusProduk($id);
    header("Location: adm_dsh_KatalogProduk.php");
    exit();
}

// Edit produk
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $link = $_POST['link'];
    $rating = $_POST['rating'];
    $nama_pemilik = $_POST['nama_pemilik'];
    $no_telepon = $_POST['no_telepon'];
    $gambar = $_FILES['gambar']['name'] ? $_FILES['gambar']['name'] : $_POST['gambar_lama'];

    if ($_FILES['gambar']['error'] == 0) {
        $targetDir = "images/";
        $targetFilePath = $targetDir . basename($gambar);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFilePath);
    }

    editProduk($id, $judul, $deskripsi, $gambar, $link, $rating, $nama_pemilik, $no_telepon);
    header("Location: adm_dsh_KatalogProduk.php");
    exit();
}

$produkList = tampilkanProduk();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <link href="styles.css" rel="stylesheet">
    <style>
        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            transition: all 0.3s;
        }
        .sidebar.show-sidebar {
            transform: translateX(0);
        }
        /* Additional styles for sidebar, header, and content */
    </style>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show-sidebar');
        }
    </script>
</head>
<body>
    <header class="header" style="display: flex;">
        <img src="assets/logo_lamtim.png" alt="Logo Desa" class="logo" width="50" />
        <img src="assets/logo_kkn.png" alt="Logo KKN" class="logo" width="50" />
        <div class="title-wrap text-left">
            <p class="judul" style="color: #980c28">Website UMKM</p>
            <p class="subjudul" style="color:gray;">Desa Karyamukti</p>
            <p class="subjudul" style="color:gray;">Produk / Layanan</p>
        </div>
        <button class="burger-menu" onclick="toggleSidebar()" style="color: black;">&#9776;</button>
    </header>

    <?php include "sidebar.html"?>

    <div class="content" id="top">
    <h1 class="text-center" style="font-weight: bold;">Katalog Produk</h1>
    <h2>Tambah Produk</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label>Judul:</label>
            <input type="text" name="judul" class="form-control" placeholder="Keripik Tempe" required>
        </div>
        <div class="form-group">
            <label>Deskripsi:</label>
            <textarea name="deskripsi" class="form-control" rows="5" placeholder="Keripik Tempe adalah ...." required></textarea>
        </div>
        <div class="form-group">
            <label>Gambar:</label>
            <input type="file" name="gambar" class="form-control" placeholder="Upload Gambar Produk" required>
        </div>
        <div class="form-group">
            <label>Link:</label>
            <input type="text" name="link" class="form-control" placeholder="https//shopee.com" required>
        </div>
        <div class="form-group">
            <label>Rating:</label>
            <input type="number" name="rating" class="form-control" min="1" max="5" placeholder="1 - 5" required>
        </div>
        <div class="form-group">
            <label>Nama Pemilik:</label>
            <input type="text" name="nama_pemilik" class="form-control" placeholder="Budi Setiawan" required>
        </div>
        <div class="form-group">
            <label>No Telepon:</label>
            <input type="text" name="no_telepon" class="form-control" placeholder="085712345678" required>
        </div>
        <button type="submit" name="tambah" class="btn btn-primary">Tambah Produk</button>
    </form>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
    <?php endif; ?>

    <h2 class="mt-5">Data Produk</h2>
    <table class="table table-striped mt-3 tabel_katalog">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Nama Pemilik</th>
                <th>Gambar</th>
                <th>Link</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produkList as $produk): ?>
            <tr>
                <td><?php echo $produk['id']; ?></td>
                <td><?php echo $produk['judul']; ?></td>
                <td><?php echo $produk['nama_pemilik']; ?></td>
                <td><img src="images/<?php echo $produk['gambar']; ?>" alt="Gambar Produk"></td>
                <td><a href="<?php echo $produk['link']; ?>" target="_blank">Link</a></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal-<?php echo $produk['id']; ?>">Edit</button>
                    <a href="?hapus=<?php echo $produk['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <?php foreach ($produkList as $produk): ?>
    <div class="modal fade" id="editModal-<?php echo $produk['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="editModalLabel">Edit Produk</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?php echo $produk['id']; ?>">
                        <div class="form-group">
                            <label>Judul:</label>
                            <input type="text" name="judul" value="<?php echo $produk['judul']; ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi:</label>
                            <textarea name="deskripsi" class="form-control" rows="5" required><?php echo $produk['deskripsi']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Gambar:</label>
                            <input type="file" name="gambar" class="form-control">
                            <input type="hidden" name="gambar_lama" value="<?php echo $produk['gambar']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Link:</label>
                            <input type="text" name="link" value="<?php echo $produk['link']; ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Rating:</label>
                            <input type="number" name="rating" value="<?php echo $produk['rating']; ?>" class="form-control" min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Pemilik:</label>
                            <input type="text" name="nama_pemilik" value="<?php echo $produk['nama_pemilik']; ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>No Telepon:</label>
                            <input type="text" name="no_telepon" value="<?php echo $produk['no_telepon']; ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</body>
</html>
