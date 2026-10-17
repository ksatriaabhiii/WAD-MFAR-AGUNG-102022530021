<?php
require_once "koneksi.php";
mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS master_voucher (
    id_voucher INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_voucher VARCHAR(100) NOT NULL,
    potongan_harga BIGINT UNSIGNED NOT NULL DEFAULT 0,
    minimal_belanja BIGINT UNSIGNED NOT NULL DEFAULT 0,
    status_voucher ENUM('Tersedia', 'Sudah Klaim') NOT NULL DEFAULT 'Tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");
