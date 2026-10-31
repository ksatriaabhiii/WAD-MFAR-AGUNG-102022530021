<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'master_voucher';

    protected $primaryKey = 'id_voucher';

    public $timestamps = false;

    protected $fillable = [
        'nama_voucher',
        'potongan_harga',
        'minimal_belanja',
        'status_voucher'
    ];
}