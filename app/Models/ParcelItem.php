<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\Parcel;

class ParcelItem extends Model
{
    use HasFactory;

     protected $fillable = ['barcode',  'hub_id', 'hub_name', 'parcel_id', 'price'];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }

    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }
}
