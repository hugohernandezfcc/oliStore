<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Liabilities extends Model
{
    use HasFactory;

    protected $table = 'liabilities';
    protected $fillable = [
        'name',
        'description',
        'created_by_id',
        'updated_by_id',
        'financial_id',
        'percentage',
    ];
    
    protected $casts = [

        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function financial()
    {
        return $this->belongsTo(Financial::class, 'financial_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

}



