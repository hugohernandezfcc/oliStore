<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'street',
        'postal_code',
        'state',
        'town',
        'country',
        'external_number',
        'workin_hours',
        'created_by_id',
        'phone',
        'email',
        'website',
        'no_providers',
        'owner_id'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
