<?php
require_once "koneksi.php";

$sql = "CREATE TABLE IF NOT EXISTS master_voucher (
    id_voucher INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_voucher VARCHAR(100) NOT NULL,
    potongan_harga BIGINT UNSIGNED NOT NULL DEFAULT 0,
    minimal_belanja BIGINT UNSIGNED NOT NULL DEFAULT 0,
    status_voucher ENUM('Tersedia', 'Sudah Klaim') NOT NULL DEFAULT 'Tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

mysqli_query($koneksi, $sql);

echo "<!doctype html><html lang='id'><meta charset='utf-8'><title>Setup Voucher</title>";
echo "<body style='font-family:Arial;padding:30px'>";
echo "<h2>Tabel master_voucher siap digunakan.</h2>";
echo "<p>Database: <b>db_shopku</b></p>";
echo "<p><a href='data_voucher.php'>Buka pengelolaan voucher</a> · ";
echo "<a href='index.php'>Buka halaman voucher</a></p></body></html>";
