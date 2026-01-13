<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchPaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_type',
        'item_type',
        'tracking_number',
        'vehicle_no',
        'from_branch_id',
        'to_branch_id',
        'amount',
        'description',
        'quantity',
        'unit',
        'transport_type',
        'is_cod',
        'cod_amount',
        'cod_payment_mode',
        'cod_remarks',
        'city',
        'state',
        'cgst',
        'sgst',
        'igst',
        'total_with_gst',
        'include_gst',
        'manifest_no'

    ];

    public function fromBranch()
    {
        return $this->belongsTo(BranchPaymentRequest::class, 'from_branch_id');
    }

    // public function toBranch()
    // {
    //     return $this->belongsTo(Branch::class, 'to_branch_id');
    // }
    public function toBranch()
    {
        return $this->belongsTo(BranchPaymentRequest::class, 'to_branch_id');
    }
}
