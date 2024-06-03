<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Financial extends Model
{
    use HasFactory;
    protected $table = 'financial';
    protected $fillable = [
        'name',
        'description',
        'created_by_id',
        'updated_by_id',
        'store_id',
        'current'
    ];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function liabilities(){
        return $this->hasMany(Liabilities::class, 'financial_id');
    }
}


