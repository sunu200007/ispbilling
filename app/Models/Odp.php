<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Odp extends Model
{
    protected $table = 'odp';
    protected $fillable = ['nama_odp', 'kode_odp', 'odc_id', 'latitude', 'longitude', 'jumlah_port', 'keterangan'];

    public function odc()
    {
        return $this->belongsTo(Odc::class);
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}