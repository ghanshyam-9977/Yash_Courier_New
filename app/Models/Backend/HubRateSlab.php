<?php

namespace App\Models\Backend;

use App\Models\Backend\Hub;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HubRateSlab extends Model
{
    use HasFactory;

    protected $fillable = [
        'hub_id',
        'min_weight',
        'max_weight',
        'rate',
        'unit'
    ];


    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }
}
