<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrsEntry extends Model
{
    use HasFactory;

    protected $table = 'drs_entries';

    protected $fillable = [
        'drs_no',
        'area_name',
        'drs_date',
        'drs_time',
        'delivery_boy_id',
        'pincode',
        'total_shipments',
    ];

    protected $casts = [
        'drs_date' => 'date',
        'drs_time' => 'datetime:H:i',
    ];

    // 1 DRS -> Multiple Shipments
    public function shipments()
    {
        return $this->hasMany(DrsShipment::class, 'drs_entry_id');
    }

    // ✅ ADD THIS METHOD
    public function deliveryMan()
    {
        return $this->hasOne(
            DeliveryMan::class,
            'user_id',          // delivery_man.user_id
            'delivery_boy_id'   // drs_entries.delivery_boy_id
        );
    }

    // Assuming DrsEntry belongs to a Branch
    public function branch()
    {
        return $this->belongsTo(Hub::class);
    }
}
