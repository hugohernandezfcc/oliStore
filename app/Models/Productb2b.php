<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productb2b extends Model
{
    use HasFactory;

    protected $table = 'productb2b';

    protected $fillable = [
        'name',
        'folio',
        'description',
        'unit_measure',
        'expiration_range',
        'created_by_id',
        'edited_by_id',
        'sold_out',
        'is_public',
        'is_private',
        'public_description',
        'public_name',
        'image',
        'promo',
        'bulkSale',
        'drinks',
        'snacks',
        'groceries',
        'cleaning',
        'underFox',
        'package',
        'bundle'
    ];

    protected $casts = [
        'sold_out' => 'boolean',
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];
    

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    public function pricebookEntries()
    {
        return $this->hasMany(PriceBookEntry::class, 'productb2b_id');
    }
}
