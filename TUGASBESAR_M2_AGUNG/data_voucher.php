<?php
require_once "koneksi.php";
require_once "tabel_voucher_helper.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$allowedStatuses = ["Tersedia", "Sudah Klaim"];
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "add" || $action === "update") {
        $nama = trim($_POST["nama_voucher"] ?? "");
        $potonganRaw = $_POST["potongan_harga"] ?? "";
        $minimalRaw = $_POST["minimal_belanja"] ?? "";
        $status = $_POST["status_voucher"] ?? "";

        $id = filter_var($_POST["id_voucher"] ?? null, FILTER_VALIDATE_INT);
        $potongan = filter_var($potonganRaw, FILTER_VALIDATE_INT);
        $minimal = filter_var($minimalRaw, FILTER_VALIDATE_INT);

        if ($nama === "" || mb_strlen($nama) > 100) {
            $error = "Nama voucher wajib diisi (maksimal 100 karakter).";
        } elseif ($potongan === false || $potongan < 0 || $minimal === false || $minimal < 0) {
            $error = "Potongan dan minimal belanja harus berupa angka nol atau lebih.";
        } elseif (!in_array($status, $allowedStatuses, true)) {
            $error = "Status voucher tidak valid.";
        } elseif ($action === "update" && (!$id || $id < 1)) {
            $error = "ID voucher tidak valid.";
        } else {
            if ($action === "add") {
                $stmt = mysqli_prepare($koneksi, "INSERT INTO master_voucher (nama_voucher, potongan_harga, minimal_belanja, status_voucher) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "siis", $nama, $potongan, $minimal, $status);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("Location: data_voucher.php?added=1");
                exit;
            } else {
                $stmt = mysqli_prepare($koneksi, "UPDATE master_voucher SET nama_voucher=?, potongan_harga=?, minimal_belanja=?, status_voucher=? WHERE id_voucher=?");
                mysqli_stmt_bind_param($stmt, "siisi", $nama, $potongan, $minimal, $status, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("Location: data_voucher.php?updated=1");
                exit;
            }
        }
    } elseif ($action === "delete") {
        $id = filter_var($_POST["id_voucher"] ?? null, FILTER_VALIDATE_INT);
        if ($id && $id > 0) {
            $stmt = mysqli_prepare($koneksi, "DELETE FROM master_voucher WHERE id_voucher=?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("Location: data_voucher.php?deleted=1");
            exit;
        }
        $error = "ID voucher tidak valid.";
    }
}

$editId = filter_input(INPUT_GET, "edit", FILTER_VALIDATE_INT);
$editVoucher = null;
if ($editId && $editId > 0) {
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM master_voucher WHERE id_voucher=?");
    mysqli_stmt_bind_param($stmt, "i", $editId);
    mysqli_stmt_execute($stmt);
    $editVoucher = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if (!$editVoucher) {
        $error = "Voucher yang ingin diedit tidak ditemukan.";
    }
}

$result = mysqli_query($koneksi, "SELECT * FROM master_voucher ORDER BY id_voucher DESC");
$vouchers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Voucher ShopKu</title>
<link rel="stylesheet" href="style.css">
<style>
.admin-wrap{max-width:1100px;width:100%;margin:auto}
.admin-panel{background:#fff;border-radius:14px;padding:22px;margin:18px 0;box-shadow:0 8px 22px #1f293710}
.admin-panel h2{margin-bottom:15px}
.admin-form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.admin-form input,.admin-form select{width:100%;padding:11px;border:1px solid #d1d5db;border-radius:8px;font:inherit}
.admin-form button{grid-column:1/-1;justify-self:start}
.admin-table{width:100%;border-collapse:collapse;min-width:700px}
.admin-table th,.admin-table td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:left}
.admin-table th{background:#f3f4f6}
.table-scroll{overflow-x:auto}
.admin-actions{display:flex;gap:7px;align-items:center}
.admin-actions form{display:inline}
.admin-btn{border:0;border-radius:7px;padding:8px 11px;text-decoration:none;cursor:pointer;font-weight:600;font-size:13px}
.admin-edit{background:#fef3c7;color:#92400e}
.admin-delete{background:#fee2e2;color:#991b1b}
.admin-save{background:#059669;color:white}
.admin-cancel{background:#e5e7eb;color:#374151}
.notice{padding:12px 15px;border-radius:8px;margin:15px 0;background:#dcfce7;color:#166534}
.notice.error{background:#fee2e2;color:#991b1b}
@media(max-width:600px){.admin-form{grid-template-columns:1fr}.admin-form button{grid-column:auto}}
</style>
</head>
<body>
<div class="main-container admin-wrap">
    <div class="banner">
        <h1>Kelola Voucher ShopKu</h1>
        <p>Tambah, edit, dan hapus voucher dari database.</p>
    </div>

    <?php if ($error !== ""): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
    <?php if (isset($_GET["added"])): ?><div class="notice">Voucher baru berhasil ditambahkan.</div><?php endif; ?>
    <?php if (isset($_GET["updated"])): ?><div class="notice">Voucher berhasil diperbarui.</div><?php endif; ?>
    <?php if (isset($_GET["deleted"])): ?><div class="notice">Voucher berhasil dihapus.</div><?php endif; ?>

    <section class="admin-panel">
        <h2><?= $editVoucher ? "Edit Voucher" : "Tambah Voucher Baru" ?></h2>
        <form method="post" action="data_voucher.php" class="admin-form">
            <input type="hidden" name="action" value="<?= $editVoucher ? "update" : "add" ?>">
            <?php if ($editVoucher): ?>
                <input type="hidden" name="id_voucher" value="<?= (int)$editVoucher["id_voucher"] ?>">
            <?php endif; ?>
            <input name="nama_voucher" maxlength="100" placeholder="Nama Voucher" required value="<?= e($editVoucher["nama_voucher"] ?? "") ?>">
            <input type="number" name="potongan_harga" placeholder="Potongan (Rp)" min="0" required value="<?= e($editVoucher["potongan_harga"] ?? "") ?>">
            <input type="number" name="minimal_belanja" placeholder="Minimal Belanja (Rp)" min="0" required value="<?= e($editVoucher["minimal_belanja"] ?? "") ?>">
            <select name="status_voucher" required>
                <option value="Tersedia" <?= ($editVoucher["status_voucher"] ?? "Tersedia") === "Tersedia" ? "selected" : "" ?>>Tersedia</option>
                <option value="Sudah Klaim" <?= ($editVoucher["status_voucher"] ?? "") === "Sudah Klaim" ? "selected" : "" ?>>Sudah Klaim</option>
            </select>
            <button class="btn-klaim" type="submit"><?= $editVoucher ? "Simpan Perubahan" : "Tambah Voucher" ?></button>
            <?php if ($editVoucher): ?><a class="admin-btn admin-cancel" href="data_voucher.php">Batal Edit</a><?php endif; ?>
        </form>
    </section>

    <section class="admin-panel">
        <h2>Daftar Voucher (<?= count($vouchers) ?>)</h2>
        <div class="table-scroll">
        <table class="admin-table">
            <thead><tr><th>ID</th><th>Nama Voucher</th><th>Potongan</th><th>Minimal Belanja</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if (!$vouchers): ?>
                <tr><td colspan="6">Belum ada data voucher. Tambahkan voucher melalui formulir di atas.</td></tr>
            <?php endif; ?>
            <?php foreach ($vouchers as $row): ?>
                <tr>
                    <td><?= (int)$row["id_voucher"] ?></td>
                    <td><?= e($row["nama_voucher"]) ?></td>
                    <td>Rp <?= number_format((int)$row["potongan_harga"], 0, ",", ".") ?></td>
                    <td>Rp <?= number_format((int)$row["minimal_belanja"], 0, ",", ".") ?></td>
                    <td><?= e($row["status_voucher"]) ?></td>
                    <td><div class="admin-actions">
                        <a class="admin-btn admin-edit" href="data_voucher.php?edit=<?= (int)$row["id_voucher"] ?>">Edit</a>
                        <form method="post" action="data_voucher.php" onsubmit="return confirm('Yakin ingin menghapus voucher ini?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id_voucher" value="<?= (int)$row["id_voucher"] ?>">
                            <button class="admin-btn admin-delete" type="submit">Hapus</button>
                        </form>
                    </div></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <p style="margin-top:18px"><a href="index.php">Lihat halaman klaim voucher</a></p>
    </section>
</div>
</body>
</html>
