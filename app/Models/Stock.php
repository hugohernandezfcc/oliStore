<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stocks';
    protected $fillable = [
        'name',
        'folio',
        'description',
        'quantity',
        'investment',
        'profit',
        'provider_id',
        'store_id',
        'product_id',
        'created_by_id',
        'edited_by_id',
        'last_ticket_item_applied_id'
    ];

    public function lastTicketItemApplied()
    {
        return $this->belongsTo(Tickets::class, 'last_ticket_item_applied_id');
    }

    public function provider()
    {
        return $this->belongsTo(Providers::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }
}
