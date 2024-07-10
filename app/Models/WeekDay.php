<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekDay extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'week_days';
    public const RELATED_LIST_COLUMNS = [
        ['prop' => 'id',            'label' => 'id',         'width' => '100',  'type' => 'text'],
        ['prop' => 'name',          'label' => 'Nombre',     'width' => '200', 'type' => 'text'],
        ['prop' => 'description',   'label' => 'Descripción','width' => '200', 'type' => 'text'],
    ];
    public const MODAL_FORM_FIELDS = [
        ['prop' => 'id',              'label' => 'Id',                'type' => 'text'],
        ['prop' => 'name',            'label' => 'Nombre',              'type' => 'text'],
        ['prop' => 'description',     'label' => 'Descripción',        'type' => 'text']
    ];

    // Define the fillable properties
    protected $fillable = [
        'updated_by_id',
        'created_by_id',
        'name',
        'description',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
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