<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceBookEntry extends Model
{
    use HasFactory;

    protected $table = 'pricebookentry';

    protected $fillable = [
        'productb2b_id',
        'pricebook_id',
        'created_by_id',
        'edited_by_id',
        'price',
        'cost',
        'profit',
        'is_active',
        'description',
        'name',
    ];

    protected $casts = [
        'price'     => 'float',
        'cost'      => 'float',
        'profit'    => 'float',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function productb2b()
    {
        return $this->belongsTo(Productb2b::class, 'productb2b_id');
    }

    public function pricebook()
    {
        return $this->belongsTo(PriceBook::class, 'pricebook_id');
    }

    public function created_by_id()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function edited_by_id()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }
}