<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IpPool extends Model
{
    protected $table = 'ip_pool';
    protected $fillable = ['paket_id', 'nama_pool', 'network', 'prefix', 'ip_start', 'ip_end', 'kapasitas', 'status'];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }

    public function getTerpakaiAttribute()
    {
        return $this->pelanggan()->where('status', 'aktif')->count();
    }

    public function getSisaAttribute()
    {
        return $this->kapasitas - $this->terpakai;
    }

    public function getIsPenuhAttribute()
    {
        return $this->terpakai >= $this->kapasitas;
    }
}