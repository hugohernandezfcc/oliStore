<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;

    protected $table = 'box';

    protected $fillable = [
        'box_date',
        'name',
        'status',
        'founds_box',
        'amount',
        'description',
        'seller_id',
        'created_by_id',
        'edited_by_id',
        'store_id',
    ];
    
    protected $casts = [

        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];
    
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

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
        return $this->belongsTo(Store::class, 'store_id');
    }
}
