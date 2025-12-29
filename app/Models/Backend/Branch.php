<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone'
    ];

    public function paymentRequestsFrom()
    {
        return $this->hasMany(BranchPaymentRequest::class, 'from_branch_id');
    }

    public function paymentRequestsTo()
    {
        return $this->hasMany(BranchPaymentRequest::class, 'to_branch_id');
    }
}
