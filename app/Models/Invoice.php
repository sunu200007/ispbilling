<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $fillable = [
        'no_invoice', 'pelanggan_id', 'jumlah',
        'tanggal_invoice', 'tanggal_jatuh_tempo',
        'status', 'metode_bayar', 'dibayar_at'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}