<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
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
}