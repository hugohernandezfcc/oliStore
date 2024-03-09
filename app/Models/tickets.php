<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class tickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'noTicket',
        'who_issued_ticket',
        'provider',
        'total',
        'date_time_issued',
        'created_by_id',
        'edited_by_id'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    public function ticketItems(): HasMany
    {
        return $this->hasMany(ticketItems::class, 'ticket_id');
    }
}



