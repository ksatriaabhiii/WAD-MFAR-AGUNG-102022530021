<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Halaman Klaim Voucher</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

        @forelse ($vouchers as $voucher)

            <div class="voucher-row">

                <div class="col-name">
                    <div class="voucher-title">
                        {{ $voucher->nama_voucher }}
                    </div>

                    <div class="voucher-min-spend">
                        Minimal belanja Rp
                        {{ number_format($voucher->minimal_belanja, 0, ',', '.') }}
                    </div>
                </div>

                <div class="col-discount">
                    Rp {{ number_format($voucher->potongan_harga, 0, ',', '.') }}
                </div>

                <div class="col-action">
                    @if ($voucher->status_voucher === 'Tersedia')

                        <span class="status-available">Tersedia</span>

                    @else

                        <button class="btn-disabled" disabled>
                            Sudah Klaim
                        </button>

                    @endif
                </div>

            </div>

        @empty

            <div class="voucher-row">
                <p>Belum ada voucher tersedia.</p>
            </div>

        @endforelse

        <div class="footer-terms">
            Minimal pembelanjaan untuk setiap voucher berlaku.
            Voucher hanya dapat digunakan satu kali per akun.
            Syarat dan ketentuan dapat berubah sewaktu-waktu
            tanpa pemberitahuan sebelumnya.
        </div>

    </div>

    <p style="text-align:center;margin-top:18px">
        <a href="{{ route('voucher.admin') }}">
            Masuk ke pengelolaan voucher
        </a>
    </p>

</div>
</body>
</html>
