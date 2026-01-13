<?php

namespace App\Models\Backend;

use App\Models\Backend\Hub;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HubServiceArea extends Model
{
    use HasFactory;

    protected $table = 'hub_service_areas';

    protected $fillable = [
        'hub_id',
        'state',
        'city',
    ];

    // Agar timestamps use ho rahe hain (created_at, updated_at)
    public $timestamps = true;

    /**
     * Hub relation
     */
    public function hub()
    {
        return $this->belongsTo(Hub::class, 'hub_id');
    }
}
