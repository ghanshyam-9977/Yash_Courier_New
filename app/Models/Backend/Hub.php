<?php

namespace App\Models\Backend;

use App\Models\Backend\HubRateSlab;
use App\Models\Backend\HubServiceArea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Hub extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'name',
        'phone',
        'state',
        'city',
        'address',
        'contact_person',
        'pincode',
        'hub_lat',
        'hub_long',
        'item_type',
        'transport_type',
        'weight_unit',
        'rate_type',
        'gst_withdrawn',
        'cgst',
        'sgst',
        'igst',
        'status',
        'opening_balance',
        'current_balance'
    ];


    // Get all row. Descending order using scope.'
    public function scopeOrderByDesc($query, $data)
    {
        $query->orderBy($data, 'desc');
    }

    /**
     * Activity Log
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1); // Assuming 'status' field with 1=active
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Hub')
            ->logOnly(['name', 'phone', 'address'])
            ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}");
    }

    public function getMyStatusAttribute()
    {
        return trans('status.' . $this->status);
    }

    public function parcels()
    {
        return $this->hasMany(Parcel::class, 'hub_id', 'id');
    }

    public function serviceAreas()
    {
        return $this->hasMany(HubServiceArea::class, 'hub_id');
    }

    public function rateSlabs()
    {
        return $this->hasMany(HubRateSlab::class, 'hub_id');
    }
}
