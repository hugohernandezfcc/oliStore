<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'account';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'created_by',
        'updated_by',
    ];

    // Relationship named as foreign key
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'account_id');
    }
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'account_id');
    }
  
}