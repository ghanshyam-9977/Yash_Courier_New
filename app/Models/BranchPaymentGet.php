<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchPaymentGet extends Model
{
    use HasFactory;

    protected $table = 'branch_payment_requests';

    protected $fillable = [
        'from_branch_id',
        'to_branch_id',
        'amount',
        'description',
        'quantity',
        'transport_type',
        'vehicle_no',

    ];


    public function fromBranch()
{
    return $this->belongsTo(Branch::class, 'from_branch_id');
}

public function toBranch()
{
    return $this->belongsTo(Branch::class, 'to_branch_id');
}

}


