<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'salesorder';

    protected $fillable = [
        'account_id',
        'status',
        'no_products',
        'total',
        'total_tax',
        'note',
        'total_discount',
        'payment_method',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function salesOrderItems()
    {
        return $this->hasMany(Orpb2b::class, 'salesorder_id');
    }
}