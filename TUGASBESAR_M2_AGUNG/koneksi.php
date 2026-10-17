<?php
// Koneksi untuk XAMPP lokal.
// Database dibuat otomatis jika belum tersedia.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$password = "";
$db = "db_Shopku";

try {
    $koneksi = mysqli_connect($host, $user, $password);
    mysqli_set_charset($koneksi, "utf8mb4");

    mysqli_query(
        $koneksi,
        "CREATE DATABASE IF NOT EXISTS `$db`
         CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
    );

    mysqli_select_db($koneksi, $db);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit("Koneksi database gagal. Pastikan MySQL XAMPP sudah berjalan dan konfigurasi koneksi benar.");
}
