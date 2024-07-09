<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Providers extends Model
{
    use HasFactory;
    protected $table = 'providers';
    public const RELATED_LIST_COLUMNS = [
        ['prop' => 'company',       'label' => 'Compañia'],
        ['prop' => 'visit_day',     'label' => 'Días de visita'],
        ['prop' => 'whatsapp',      'label' => 'WhatsApp'],
        ['prop' => 'representative','label' => 'Representante'],
        ['prop' => 'whatsapp',      'label' => 'Whatsapp'],
        ];
    public const MODAL_FORM_FIELDS = [
        ['prop' => 'id',              'label' => 'Id',                'type' => 'text'],
        ['prop' => 'representative', 'label' => 'Representante',      'type' => 'text'],
        ['prop' => 'description',    'label' => 'Descripción',        'type' => 'text'],
        ['prop' => 'phone',          'label' => 'Teléfono',           'type' => 'text'],
        ['prop' => 'email',          'label' => 'Correo Electrónico', 'type' => 'text'],
        ['prop' => 'whatsapp',       'label' => 'WhatsApp',           'type' => 'text'],
        ['prop' => 'company',        'label' => 'Compañia',           'type' => 'text'],
        ['prop' => 'visit_day',      'label' => 'Días de visita',     'type' => 'text'],
    ];

    protected $fillable = [
        'representative',
        'description',
        'phone',
        'email',
        'whatsapp',
        'company',
        'visit_day',
        'created_by_id',
        'edited_by_id',
        'store_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function lineAnyItems() : HasMany
    {
        return $this->hasMany(LineAnyItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}