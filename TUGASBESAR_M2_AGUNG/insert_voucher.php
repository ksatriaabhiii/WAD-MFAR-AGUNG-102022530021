<?php
require_once "koneksi.php";
require_once "tabel_voucher_helper.php";

// File ini hanya untuk memasukkan contoh data awal satu kali.
// Buka melalui http://localhost/voucher-shopku/insert_voucher.php
$cek = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM master_voucher");
$data = mysqli_fetch_assoc($cek);

if ((int)$data["total"] > 0) {
    exit("Data voucher sudah tersedia. Tidak ada data contoh yang ditambahkan. <a href='data_voucher.php'>Kembali</a>");
}

$vouchers = [
    ["Voucher Akhir Tahun", 100000, 200000, "Tersedia"],
    ["Voucher Hari Raya", 100000, 150000, "Tersedia"],
    ["Voucher Tahun Baru", 150000, 150000, "Sudah Klaim"]
];

$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO master_voucher
     (nama_voucher, potongan_harga, minimal_belanja, status_voucher)
     VALUES (?, ?, ?, ?)"
);

foreach ($vouchers as $v) {
    mysqli_stmt_bind_param($stmt, "siis", $v[0], $v[1], $v[2], $v[3]);
    mysqli_stmt_execute($stmt);
}
mysqli_stmt_close($stmt);

echo "<!doctype html><html lang='id'><meta charset='utf-8'><title>Data Contoh</title>";
echo "<body style='font-family:Arial;padding:30px'><h2>Data contoh berhasil ditambahkan.</h2>";
echo "<a href='data_voucher.php'>Buka pengelolaan voucher</a></body></html>";
