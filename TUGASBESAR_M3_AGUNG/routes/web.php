<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherController;

// Halaman utama voucher
Route::get('/', [VoucherController::class, 'index'])
    ->name('voucher.index');

// Halaman pengelolaan voucher
Route::get('/admin/voucher', [VoucherController::class, 'admin'])
    ->name('voucher.admin');

// Menambahkan voucher
Route::post('/admin/voucher', [VoucherController::class, 'store'])
    ->name('voucher.store');

// Mengedit voucher
Route::put('/admin/voucher/{id}', [VoucherController::class, 'update'])
    ->name('voucher.update');

// Menghapus voucher
Route::delete('/admin/voucher/{id}', [VoucherController::class, 'destroy'])
    ->name('voucher.destroy');
