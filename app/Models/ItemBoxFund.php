<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class ItemBoxFund extends Model
{
    use HasFactory;

    protected $table = 'item_box_fund';

    protected $fillable = [
        'name',
        'type',
        'order',
        'result',
        'event_box_id',
        'box_fund_id',
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

    public function parentBox(): belongsTo
    {
        return $this->belongsTo(BoxFund::class, 'parent_box_id');
    }

}



