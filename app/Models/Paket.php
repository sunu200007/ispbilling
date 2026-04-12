<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'paket';
    protected $fillable = ['nama_paket', 'harga', 'kecepatan_download', 'kecepatan_upload', 'status'];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }

    public function ipPool()
    {
        return $this->hasMany(IpPool::class);
    }
}