<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MapsMarker extends Model
{
    protected $table = 'maps_marker';
    protected $fillable = ['markerable_type', 'markerable_id', 'latitude', 'longitude', 'label', 'tipe', 'status'];

    public function markerable()
    {
        return $this->morphTo();
    }
}