<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Odc extends Model
{
    protected $table = 'odc';
    protected $fillable = ['nama_odc', 'kode_odc', 'latitude', 'longitude', 'jumlah_port', 'keterangan'];

    public function odp()
    {
        return $this->hasMany(Odp::class);
    }
}