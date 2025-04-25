<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccRoute extends Model
{
    use HasFactory;

    protected $table = 'acc_route';

    protected $fillable = [
        'account_id',
        'route_id',
        'latitude',
        'longitude',
        'order',
        'comments',
        'created_by_id',
        'edited_by_id',
    ];

    // Relationships
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function created_by_id()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function edited_by_id()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }
}