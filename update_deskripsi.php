<?php
// Koneksi ke database
$servername = "sql105.infinityfree.com"; 
$username = "if0_38227943"; 
$password = "peninjawan123";
$dbname = "if0_38227943_produk_beranda"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan data dari form
$kategori = $_POST['kategori'];
$deskripsi = $_POST['deskripsi'];

// Query untuk memperbarui deskripsi
$sql = "UPDATE deskripsi SET deskripsi='$deskripsi' WHERE kategori='$kategori'";

if ($conn->query($sql) === TRUE) {
    header("Location: adm_dsh_ProdukBeranda.php");
    exit();  // Pastikan script tidak lanjut setelah redirect
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
