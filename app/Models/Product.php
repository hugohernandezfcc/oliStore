<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DNS1D;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    //Add extra attribute


    //Make it available in the json response
    protected $appends = ['bar_code'];

    protected $fillable = [
        'name',
        'folio',
        'Description',
        'unit_measure',
        'price_list',
        'price_customer',
        'profit_percentage',
        'expiry_date',
        'created_by_id',
        'edited_by_id',
        'take_portion',
        'express_creation',
        'visible_product'
    ];
    
    protected $casts = [
        'expiry_date'  => 'date:Y-m-d',
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    
    public function ProductLineItems(): HasMany
    {
        return $this->hasMany(ProductLineItem::class);
    }

    //implement the attribute
    public function getBarCodeAttribute()
    {

        try {
            return DNS1D::getBarcodeHTML($this->folio, 'EAN13');
        } catch (\Throwable $th) {
            return $this->folio;
        }
    }
}