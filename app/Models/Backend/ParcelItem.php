<?php

namespace  App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\Parcel;

class ParcelItem extends Model
{
    use HasFactory;

     protected $fillable = ['parcel_id','address', 'unit','quantity','hub_name','hub_id','barcode','customer_name',"phone",'address','amount','transport_type'];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }
}
