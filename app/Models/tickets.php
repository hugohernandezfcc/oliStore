<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tickets extends Model
{
    use HasFactory;
    protected $table = 'tickets';

    protected $fillable = [
        'noTicket',
        'who_issued_ticket',
        'provider',
        'provider_id',
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

    public function provider()  
    {
        return $this->belongsTo(Providers::class, 'provider_id');
    }
}



