<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Voucher ShopKu</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .admin-wrap {
            max-width: 1100px;
            width: 100%;
            margin: auto;
        }

        .admin-panel {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            margin: 18px 0;
            box-shadow: 0 8px 22px #1f293710;
        }

        .admin-panel h2 {
            margin-bottom: 15px;
        }

        .admin-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .admin-form input,
        .admin-form select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font: inherit;
        }

        .admin-form button {
            grid-column: 1/-1;
            justify-self: start;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        .admin-table th,
        .admin-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .admin-table th {
            background: #f3f4f6;
        }

        .table-scroll {
            overflow-x: auto;
        }

        .admin-actions {
            display: flex;
            gap: 7px;
            align-items: center;
        }

        .admin-actions form {
            display: inline;
        }

        .admin-btn {
            border: 0;
            border-radius: 7px;
            padding: 8px 11px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
        }

        .admin-edit {
            background: #fef3c7;
            color: #92400e;
        }

        .admin-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .admin-cancel {
            background: #e5e7eb;
            color: #374151;
        }

        .notice {
            padding: 12px 15px;
            border-radius: 8px;
            margin: 15px 0;
            background: #dcfce7;
            color: #166534;
        }

        .notice.error {
            background: #fee2e2;
            color: #991b1b;
        }

        @media(max-width:600px) {
            .admin-form {
                grid-template-columns: 1fr;
            }

            .admin-form button {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<div class="main-container admin-wrap">

    <div class="banner">
        <h1>Kelola Voucher ShopKu</h1>
        <p>Tambah, edit, dan hapus voucher dari database.</p>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="notice">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan kesalahan validasi --}}
    @if ($errors->any())
        <div class="notice error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form tambah dan edit voucher --}}
    <section class="admin-panel">

        <h2>
            {{ $editVoucher ? 'Edit Voucher' : 'Tambah Voucher Baru' }}
        </h2>

        <form
            method="POST"
            action="{{ $editVoucher
                ? route('voucher.update', $editVoucher->id_voucher)
                : route('voucher.store') }}"
            class="admin-form"
        >

            @csrf

            @if ($editVoucher)
                @method('PUT')
            @endif

            <input
                type="text"
                name="nama_voucher"
                maxlength="100"
                placeholder="Nama Voucher"
                required
                value="{{ old('nama_voucher', $editVoucher->nama_voucher ?? '') }}"
            >

            <input
                type="number"
                name="potongan_harga"
                placeholder="Potongan (Rp)"
                min="0"
                required
                value="{{ old('potongan_harga', $editVoucher->potongan_harga ?? '') }}"
            >

            <input
                type="number"
                name="minimal_belanja"
                placeholder="Minimal Belanja (Rp)"
                min="0"
                required
                value="{{ old('minimal_belanja', $editVoucher->minimal_belanja ?? '') }}"
            >

            <select name="status_voucher" required>
                <option
                    value="Tersedia"
                    {{ old('status_voucher', $editVoucher->status_voucher ?? 'Tersedia') === 'Tersedia' ? 'selected' : '' }}
                >
                    Tersedia
                </option>

                <option
                    value="Sudah Klaim"
                    {{ old('status_voucher', $editVoucher->status_voucher ?? '') === 'Sudah Klaim' ? 'selected' : '' }}
                >
                    Sudah Klaim
                </option>
            </select>

            <button class="btn-klaim" type="submit">
                {{ $editVoucher ? 'Simpan Perubahan' : 'Tambah Voucher' }}
            </button>

            @if ($editVoucher)
                <a
                    class="admin-btn admin-cancel"
                    href="{{ route('voucher.admin') }}"
                >
                    Batal Edit
                </a>
            @endif

        </form>

    </section>

    {{-- Tabel daftar voucher --}}
    <section class="admin-panel">

        <h2>Daftar Voucher ({{ $vouchers->count() }})</h2>

        <div class="table-scroll">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Voucher</th>
                        <th>Potongan</th>
                        <th>Minimal Belanja</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($vouchers as $row)

                        <tr>
                            <td>{{ $row->id_voucher }}</td>

                            <td>{{ $row->nama_voucher }}</td>

                            <td>
                                Rp {{ number_format($row->potongan_harga, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($row->minimal_belanja, 0, ',', '.') }}
                            </td>

                            <td>{{ $row->status_voucher }}</td>

                            <td>
                                <div class="admin-actions">

                                    <a
                                        class="admin-btn admin-edit"
                                        href="{{ route('voucher.admin', ['edit' => $row->id_voucher]) }}"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('voucher.destroy', $row->id_voucher) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus voucher ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="admin-btn admin-delete"
                                            type="submit"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                Belum ada data voucher. Tambahkan voucher melalui formulir di atas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <p style="margin-top:18px">
            <a href="{{ route('voucher.index') }}">
                Lihat halaman klaim voucher
            </a>
        </p>

    </section>

</div>

</body>
</html>
