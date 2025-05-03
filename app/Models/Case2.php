<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Case2 extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'productb2b_id',
        'created_by_id',
        'edited_by_id',
        'account_id',
        'salesorder_id',
        'orpb2b_id',
        'subject',
        'description',
        'status',
        'priority',
        'type',
        'resolution',
    ];

    // Relationships

    public function productb2b()
    {
        return $this->belongsTo(ProductB2B::class, 'productb2b_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function salesorder()
    {
        return $this->belongsTo(SalesOrder::class, 'salesorder_id');
    }

    public function orpb2b()
    {
        return $this->belongsTo(OrpB2B::class, 'orpb2b_id');
    }
}