<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';
    protected $fillable = [
        'code',
        'status',
        'created_by',
        'updated_by',
        'name',
        'description',
        'sub_status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'canceled_at',
        'canceled_by',
        'completed_at',
        'completed_by'
    ];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
        'approved_at' => 'datetime:Y-m-d H:i',
        'rejected_at' => 'datetime:Y-m-d H:i',
        'canceled_at' => 'datetime:Y-m-d H:i',
        'completed_at' => 'datetime:Y-m-d H:i'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function canceledBy()
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    
}
