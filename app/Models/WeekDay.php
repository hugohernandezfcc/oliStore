<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekDay extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'week_days';

    // Define the fillable properties
    protected $fillable = [
        'updated_by_id',
        'created_by_id',
        'name',
        'description',
    ];

    /**
     * Get the user who updated the week day.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    /**
     * Get the user who created the week day.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}