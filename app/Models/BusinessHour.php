<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessHour extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'business_hours';

    // Define the fillable properties
    protected $fillable = [
        'updated_by_id',
        'created_by_id',
        'name',
        'description',
        'start',
        'end',
        'worker_id'
    ];

    /**
     * Get the user who updated the business hour.
     */
    public function updatedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
    

    /**
     * Get the user who created the business hour.
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the worker associated with the business hour.
     */
    public function worker() : BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}