<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $fillable = [
        'nama', 'username', 'password', 'no_hp', 'alamat',
        'paket_id', 'odp_id', 'latitude', 'longitude',
        'status', 'tanggal_aktif', 'tanggal_jatuh_tempo'
    ];

    protected $hidden = ['password'];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function ipPool()
    {
        return $this->belongsTo(IpPool::class);
    }

    public function odp()
    {
        return $this->belongsTo(Odp::class);
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }
}