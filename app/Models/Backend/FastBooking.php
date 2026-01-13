<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastBooking extends Model
{
    use HasFactory;

    protected $table = 'fast_bookings';

    protected $fillable = [
        'booking_no',
        'from_branch_id',
        'to_branch_id',
        'network_id',
        'payment_type',
        'slip_no',
        'total_pcs',
        'total_weight',
        'total_amount',
        'cod_amount',
        'remark',
    ];

    // Relation: ek booking ke multiple items ho sakte hain
    public function items()
    {
        return $this->hasMany(FastBookingItem::class, 'fast_booking_id');
    }


    public function sourceHub()
    {
        return $this->belongsTo(Hub::class, 'from_branch_id');
    }

    public function destinationHub()
    {
        return $this->belongsTo(Hub::class, 'to_branch_id');
    }
}
