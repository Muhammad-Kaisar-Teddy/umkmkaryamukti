<?php
  include 'produk_db.php'; // Menghubungkan dengan database produk

  // Mendapatkan daftar produk dari database
  $produkList = tampilkanProduk();

  // Mendapatkan daftar slider dari database
  $sliderList1 = []; // untuk slider group 1
  $sliderList2 = []; // untuk slider group 2

  // Mendapatkan daftar ulasan produk dari database
  $ulasanList = [];
  $ulasanResult = $koneksi->query("SELECT * FROM ulasan_produk");
  if ($ulasanResult) {
      while ($row = $ulasanResult->fetch_assoc()) {
          $ulasanList[] = $row;
      }
  }

?>
<!DOCTYPE html>
<html lang="en">
<title>Halaman Produk/Layanan</title>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk/Layanan Desa Karyamukti</title>
    <link rel="stylesheet" href="produk.css">
    <link href="footer.css" rel="stylesheet">
    <link href="header.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sanchez&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Salsa&display=swap" rel="stylesheet" />
    <style>
      @media (max-width:915px) {}

      .carousel-item .row {
        justify-content: center;
      }

      .carousel-item .col-md-4 {
        display: flex;
        justify-content: center;
      }

      body {
        overflow-x: hidden;
      }

      .slider-review {
        display: flex;
        width: 100%;
      }

      .container-xl{
        background-color: #f7e7ce;
        padding: 40px 0;
      }
      .card-review {
        background-color: #d3b38c;
        border: 1px solid #000;
        border-radius: 10px;
        padding: 20px;
        width: 265px;
        margin: 10px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
        overflow-x: hidden;
      }

      .orange {
        background-color: #d97a3c;
      }

      .card-review p {
        color: #000;
        font-size: 14px;
      }

      .card-review .name {
        font-weight: bold;
        font-size: 18px;
        color: #000;
      }

      .card-review .product {
        font-weight: bold;
        font-size: 18px;
        color: #fff;
        text-align: right;
      }

      .card-review .status {
        font-size: 14px;
        color: #000;
      }

      .card-review .stars {
        color: #ffcc00;
        text-align: right;
      }

      .card-review hr {
        border: 0;
        border-top: 1px solid #000;
        margin: 10px 0;
      }
    </style>
  </head>
  <body style="overflow-x: hidden;">
    <header class="header-front">
      <!-- Logo Desa -->
      <img src="assets/logo_lamtim.png" alt="Logo Desa" class="logo" width="50" />
      <!-- Logo UMKM -->
      <img src="assets/logo_kkn.png" alt="Logo KKN" class="logo" width="50" />
      <!-- Teks Container -->
      <div class="text-container">
        <h1>Website UMKM</h1>
        <p>Desa Karyamukti</p>
      </div>
      <!-- Separator antara teks dan menu -->
      <div class="separator"></div>
      <!-- Hamburger Icon -->
      <div class="hamburger" onclick="toggleMenu()" aria-label="Toggle navigation menu">☰</div>
      <!-- Navigation Menu -->
      <nav class="menu">
        <a href="beranda.php" class="beranda">BERANDA</a>
        <a href="tentangkami.php" class="tentang-kami">TENTANG KAMI</a>
        <a href="produk.php" class="produk-layanan">PRODUK / LAYANAN</a>
        <a href="kontak_kami.php" class="kontak-kami">KONTAK KAMI</a>
      </nav>
    </header>
    <section class="catalog-title">
      <h2>KATALOG PRODUK UMKM <br />DESA LEBUNG SARI </h2>
      <hr />
      <!-- Baris pertama -->
      <div class="product-group">
    <?php foreach (array_chunk($produkList, 2) as $produkRow): ?>
        <div class="product-row">
            <?php foreach ($produkRow as $produk): ?>
                <div class="product-item">
                    <div class="product-container">
                        <div class="image-container">
                            <img src="images/<?php echo $produk['gambar']; ?>" alt="<?php echo $produk['judul']; ?>" class="product-image" />
                            <div class="overlay"><?php echo $produk['judul']; ?></div>
                            <div class="rating-box">
                                <img src="assets/Star.png" alt="star" class="star-icon" />
                                <span class="rating"><?php echo $produk['rating']; ?></span>
                            </div>
                        </div>
                        <div class="description-box">
                            <p><strong><i>DESKRIPSI PRODUK</i></strong>: <?php echo $produk['deskripsi']; ?></p>
                        </div>
                    </div>
                    <div class="profile-box">
                        <div class="profile-details">
                            <p><img src="assets/Whatsapp.png" alt="WhatsApp Icon" class="icon" /> <?php echo $produk['no_telepon']; ?> <span><b>(<?php echo $produk['nama_pemilik']; ?>)</b></span></p>
                            <p><img src="assets/Link.png" alt="Link Icon" class="icon" /><a href="<?php echo $produk['link']; ?>"><?php echo $produk['link']; ?></a></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

      <section class="review-title">
        <h2>ULASAN PELANGGAN</h2>
        <hr />
      </section>
    </section>
    <!-- Carousel Slider Review tanpa Button -->
    <!-- Carousel Slider Review dengan 3 Card per Slide -->
    <div class="container-xl mt-5">
    <?php if (empty($ulasanList)): ?>
        <p class="text-center">Belum ada ulasan</p>
    <?php else: ?>
        <div id="sliderReviewCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="false">
            <div class="carousel-inner">
                <?php
                // Membagi ulasan menjadi kelompok tiga per slide
                $ulasanChunks = array_chunk($ulasanList, 3);
                $isActive = true; // Untuk menetapkan slide pertama sebagai aktif
                ?>
                <?php foreach ($ulasanChunks as $chunk): ?>
                    <div class="carousel-item <?php echo $isActive ? 'active' : ''; ?>">
                        <div class="row justify-content-center">
                            <?php foreach ($chunk as $ulasan): ?>
                                <div class="col-md-4">
                                    <div class="card-review orange">
                                        <p><?php echo $ulasan['ulasan']; ?></p>
                                        <hr>
                                        <div class="name"><?php echo $ulasan['nama_pengulas']; ?></div>
                                        <div class="status">Status: <?php echo $ulasan['status']; ?></div>
                                        <div class="product"><?php echo $ulasan['nama_produk']; ?></div>
                                        <div class="stars">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $ulasan['rating'] ? '★' : '☆';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php $isActive = false; // Hanya slide pertama yang aktif ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

    <!-- Bootstrap CSS & JS links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
      const hamburger = document.querySelector('.hamburger');
      const menu = document.querySelector('.menu');
      hamburger.addEventListener('click', () => {
        menu.classList.toggle('open');
        hamburger.classList.toggle('active');
      });
    </script>
    <script src="script.js"></script>
    <script src="slider.js"></script>
    <!--Footer Section -->
    <footer class="footer-front">
      <div class="footer-content">
        <div class="footer-left">
          <h2>UMKM Berdaya,<br />Desa Berjaya</h2>
          <p style="color: white;">34382<br />Desa Karyamukti, Kecamatan Sekampung, Kabupaten Lampung Timur, Lampung</p>
          <p style="color: white;">Big Thanks to Tubes-KSI-Lebungfarm Team and KKN 2025 ITERA Group 97</p>
        </div>
        <div class="footer-right">
          <h2>Quick Links</h2>
          <ul>
            <li><a href="beranda.php">❯ Beranda</a></li>
            <li><a href="tentangkami.php">❯ Tentang Kami</a></li>
            <li><a href="produk.php">❯ Produk / Layanan</a></li>
            <li><a href="kontak_kami.php">❯ Kontak Kami</a></li>
          </ul>
        </div>
      </div>
    </footer>
    <div class="footer-bottom">
      <p>&copy; 2025 UMKM Karyamukti | All rights reserved</p>
      <div class="login-container">
        <a href="index.php">
          <button class="login-button">
            <img src="assets/login.png" alt="Login Icon" class="login-icon" /> Login </button>
        </a>
      </div>
    </div>
  </body>
</html>