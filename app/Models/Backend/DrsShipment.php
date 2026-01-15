<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrsShipment extends Model
{
    use HasFactory;

    protected $table = 'drs_shipments';

    protected $fillable = [
        'drs_entry_id',
        'tracking_no',
        'booking_station',
        'weight',
        'pcs',
        'receiver_name',
        'address',
        'is_tracking_detail',
    ];

    protected $casts = [
        'is_tracking_detail' => 'boolean',
        'weight' => 'decimal:2',
    ];

    // Shipment belongs to one DRS
    public function drs()
    {
        return $this->belongsTo(DrsEntry::class, 'drs_entry_id');
    }

    public function drsEntry()
    {
        return $this->belongsTo(DrsEntry::class, 'drs_entry_id', 'id');
    }
}
