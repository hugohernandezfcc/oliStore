<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketItems extends Model
{
    use HasFactory;
    protected $table = 'ticket_items';

    protected $fillable = [
        'created_at',
        'created_by_id',
        'edited_by_id',
        'product_id',
        'provider_id',
        'store_id',
        'updated_at',
        'ticket_id',
        'bar_code',
        'product_name',
        'quantity',
        'cost_customer',
        'stock_id'
    ];

    public function stockId()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function ticketId()
    {
        return $this->belongsTo(tickets::class, 'ticket_id');
    }

    public function productId()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function storeId()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }


    public function providerId()
    {
        return $this->belongsTo(Providers::class, 'provider_id');
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
