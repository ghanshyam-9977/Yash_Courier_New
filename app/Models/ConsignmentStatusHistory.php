<?php

namespace App\Models;

use App\Models\Backend\Hub;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'consignment_status_history';

    protected $fillable = [
        'tracking_number',
        'status',
        'remarks',
        'location',
        'branch_id',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function branch()
    {
        return $this->belongsTo(Hub::class, 'branch_id', 'id');
    }
}
