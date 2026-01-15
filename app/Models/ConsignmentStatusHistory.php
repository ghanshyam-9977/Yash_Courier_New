<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'consignment_status_histories';

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
}
