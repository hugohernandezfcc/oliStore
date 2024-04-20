<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    // ['open', 'in_progres', 'completed', 'cancelled']
    protected $fillable = [
        'name',
        'description',
        'expiry_date',
        'created_by_id',
        'assigned_to_id',
        'edited_by_id',
        'status',
        'record_type'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
    
}



