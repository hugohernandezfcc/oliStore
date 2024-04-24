<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'prices';
    
    protected $fillable = [
        'product_id',
        'description',
        'price_list',
        'price_customer',
        'active',
        'revenue',
        'porcentage_revenue',
        'bulk_sale',
        'created_by_id',
        'edited_by_id',
    ];
    protected $casts = [

        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
