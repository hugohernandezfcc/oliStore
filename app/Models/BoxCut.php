<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxCut extends Model
{
    use HasFactory;

    protected $table = 'box_cut';

    protected $fillable = [
        'cash_box',
        'cash_calculated',
        'cash_diff',
        'cash_withdrawal',
        'name',
        'seller_id',
        'created_by_id',
        'edited_by_id',
        'store_id',
        'box_id',
        'withdrawal_expenses',
        'amount_released',
        'card_box',
        'card_calculated',
        'card_diff',
        'card_withdrawal',
        'preliminary_box',
        'calculated',
        'diff',
        'withdrawal'
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

    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }
}
