<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class BoxFund extends Model
{
    use HasFactory;

    protected $table = 'box_fund';

    protected $fillable = [
        'name',
        'description',
        'amount',
        'counting_date',
        'created_by_id',
        'edited_by_id'
    ];

    protected $casts = [

        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function createdBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

}



