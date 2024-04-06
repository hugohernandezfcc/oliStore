<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class EventBox extends Model
{
    use HasFactory;

    protected $table = 'event_box';

    protected $fillable = [
        'name',
        'type',
        'description',
        'amount',
        'remaining_amount',
        'counting_date',
        'parent_box_id',
        'created_by_id',
        'edited_by_id',
        'item_box_fund_id'
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

    public function parentBox(): belongsTo
    {
        return $this->belongsTo(BoxFund::class, 'parent_box_id');
    }

    public function itemBoxFund(): belongsTo
    {
        return $this->belongsTo(ItemBoxFund::class, 'item_box_fund_id');
    }
}



