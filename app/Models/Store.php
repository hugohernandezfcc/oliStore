<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;
 
    protected $table = 'stores';


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
        'owner_id',
        'no_servicio_cfe',
        'no_servicio_agua',
        'no_servicio_internet',
        'fecha_pago_cfe',
        'fecha_pago_agua',
        'fecha_pago_internet',
        'updated_by_id'
    ];

    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function updatedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function lineAnyItems() : HasMany
    {
        return $this->hasMany(LineAnyItem::class);
    }
}
