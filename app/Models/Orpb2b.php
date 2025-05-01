<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orpb2b extends Model
{
    use HasFactory;

    protected $table = 'orpb2b';

    protected $fillable = [
        'salesorder_id',
        'productb2b_id',
        'created_by_id',
        'edited_by_id',
        'requested',
        'verified',
        'loaded',
        'deliveried',
        'delivery_date',
        'name',
        'quantity',
        'image',
        'unit_price',
        'subtotal_price'
    ];

    protected $casts = [
        'requested'  => 'boolean',
        'verified'   => 'boolean',
        'loaded'     => 'boolean',
        'deliveried' => 'boolean',
    ];

    // Relationships
    public function salesorder()
    {
        return $this->belongsTo(SalesOrder::class, 'salesorder_id');
    }

    public function productb2b()
    {
        return $this->belongsTo(ProductB2B::class, 'productb2b_id');
    }

    public function createdById()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedById()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }
}