<?php
require_once "koneksi.php";
require_once "tabel_voucher_helper.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
$result = mysqli_query(
    $koneksi,
    "SELECT * FROM master_voucher ORDER BY id_voucher DESC"
);
$vouchers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Klaim Voucher</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main-container">
    <div class="banner">
        <h1>Voucher</h1>
        <p>Klaim voucher spesial dari kami!</p>
    </div>

    <div class="voucher-card">
        <div class="column-header">
            <div class="col-name">Nama Voucher</div>
            <div class="col-discount">Potongan Harga</div>
            <div class="col-action">Status</div>
        </div>

        <?php if (!$vouchers): ?>
            <div class="voucher-row"><p>Belum ada voucher tersedia.</p></div>
        <?php endif; ?>

        <?php foreach ($vouchers as $voucher): ?>
            <div class="voucher-row">
                <div class="col-name">
                    <div class="voucher-title"><?= e($voucher["nama_voucher"]) ?></div>
                    <div class="voucher-min-spend">
                        Minimal belanja Rp <?= number_format((int)$voucher["minimal_belanja"], 0, ",", ".") ?>
                    </div>
                </div>
                <div class="col-discount">
                    Rp <?= number_format((int)$voucher["potongan_harga"], 0, ",", ".") ?>
                </div>
                <div class="col-action">
                    <?php if ($voucher["status_voucher"] === "Tersedia"): ?>
                        <span class="status-available">Tersedia</span>
                    <?php else: ?>
                        <button class="btn-disabled" disabled>Sudah Klaim</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="footer-terms">
            Minimal pembelanjaan untuk setiap voucher berlaku. Voucher hanya dapat digunakan satu kali per akun.
            Syarat dan ketentuan dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.
        </div>
    </div>
    <p style="text-align:center;margin-top:18px"><a href="data_voucher.php">Masuk ke pengelolaan voucher</a></p>
</div>
</body>
</html>
