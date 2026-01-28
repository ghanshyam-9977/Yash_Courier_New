<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastBookingItem extends Model
{
    use HasFactory;

    protected $table = 'fast_booking_items';

    protected $fillable = [
        'fast_booking_id',
        'tracking_no',
        'receiver_name',
        'address',
        'pcs',
        'weight',
        'amount',
    ];

    // Relation: har item belong karta hai ek booking ko
    public function fastBooking()
    {
        return $this->belongsTo(FastBooking::class, 'fast_booking_id');
    }

    public function booking()
    {
        return $this->belongsTo(FastBooking::class);
    }
<<<<<<< HEAD

    
=======
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
}
