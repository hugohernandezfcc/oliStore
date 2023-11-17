<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $fillable = [
        'representative',
        'description',
        'phone',
        'email',
        'whatsapp',
        'company',
        'visit_day',
        'created_by_id',
        'edited_by_id',
        'store_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}