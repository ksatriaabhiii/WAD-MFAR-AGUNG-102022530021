<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    // Menampilkan halaman utama voucher
    public function index()
    {
        $vouchers = Voucher::orderBy('id_voucher', 'desc')->get();

        return view('index', compact('vouchers'));
    }

    // Menampilkan halaman admin voucher
    public function admin(Request $request)
    {
        $vouchers = Voucher::orderBy('id_voucher', 'desc')->get();

        $editVoucher = null;

        if ($request->has('edit')) {
            $editVoucher = Voucher::findOrFail($request->edit);
        }

        return view('data_voucher', compact('vouchers', 'editVoucher'));
    }

    // Menambahkan voucher
    public function store(Request $request)
    {
        $validated = $request->validate([
           //nama voucher harus diisi, berupa string, dan maksimal 100 karakter
           'nama_voucher' => 'required|string|max:100',
            'potongan_harga' => 'required|integer|min:0',
            'minimal_belanja' => 'required|integer|min:0',
            'status_voucher' => 'required|in:Tersedia,Sudah Klaim'
        ]);

        Voucher::create($validated);

        return redirect()->route('voucher.admin')
            ->with('success', 'Voucher berhasil ditambahkan.');
    }

    // Mengedit voucher
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_voucher' => 'required|string|max:100',
            'potongan_harga' => 'required|integer|min:0',
            'minimal_belanja' => 'required|integer|min:0',
            'status_voucher' => 'required|in:Tersedia,Sudah Klaim'
        ]);

        $voucher = Voucher::findOrFail($id);

        $voucher->update($validated);

        return redirect()->route('voucher.admin')
            ->with('success', 'Voucher berhasil diperbarui.');
    }

    // Menghapus voucher
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);

        $voucher->delete();

        return redirect()->route('voucher.admin')
            ->with('success', 'Voucher berhasil dihapus.');
    }
}
