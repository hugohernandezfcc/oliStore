<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'created_by_id',
        'edited_by_id',
        'take_portion',
        'unit_measure',
        'quantity',
        'final_price'
    ];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function saleId()
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }

    public function productId()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

}
