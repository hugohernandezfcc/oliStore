<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricebook extends Model
{
    use HasFactory;

    protected $table = 'pricebook';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'is_default',
        'profit',
        'created_by_id',
        'edited_by_id',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_default' => 'boolean',
        'profit'     => 'float',
    ];

    // Relationships
    public function created_by_id()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function edited_by_id()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }
}