<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LineAnyItem extends Model
{
    use HasFactory;

    protected $table = 'line_any_item';
    

    protected $fillable = [
        'id',
        'type',
        'origin',
        'target',
        'origin_id',
        'target_id',
        'description',
        'updated_by_id',
        'created_by_id',
        'store_id',
        'provider_id',
        'worker_id',
        'sales_id',
        'box_id',
        'category_id',
        'orders_id',
    ];

    public function updatedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function store() : BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function provider() : BelongsTo
    {
        return $this->belongsTo(Providers::class, 'provider_id');
    }

    public function worker() : BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function sales() : BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    public function box() : BelongsTo
    {
        return $this->belongsTo(Box::class, 'box_id');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orders() : BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'orders_id');
    }
}
