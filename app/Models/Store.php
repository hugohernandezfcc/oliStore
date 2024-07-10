<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;
 
    protected $table = 'stores';
    public const RELATED_LIST_COLUMNS = [
        ['prop' => 'name',          'label' => 'Nombre',  'width' => '200', 'type' => 'text'],
        ['prop' => 'street',        'label' => 'Calle',   'width' => '200', 'type' => 'text'],
        ['prop' => 'phone',         'label' => 'Télefono','width' => '120', 'type' => 'text'],
        ['prop' => 'workin_hours',  'label' => 'Horario', 'width' => '120', 'type' => 'text'],
    ];
    public const MODAL_FORM_FIELDS = [
        ['prop' => 'name',                      'label' => 'Nombre',                         'type' => 'text'],
        ['prop' => 'street',                    'label' => 'Calle',                          'type' => 'text'],
        ['prop' => 'postal_code',               'label' => 'Código Postal',                  'type' => 'text'],
        ['prop' => 'state',                     'label' => 'Estado',                         'type' => 'text'],
        ['prop' => 'town',                      'label' => 'Municipio',                      'type' => 'text'],
        ['prop' => 'country',                   'label' => 'País',                           'type' => 'text'],
        ['prop' => 'external_number',           'label' => 'Número Exterior',                'type' => 'text'],
        ['prop' => 'workin_hours',              'label' => 'Horario',                        'type' => 'text'],
        ['prop' => 'phone',                     'label' => 'Télefono',                       'type' => 'text'],
        ['prop' => 'email',                     'label' => 'Correo Electrónico',             'type' => 'text'],
        ['prop' => 'website',                   'label' => 'Sitio Web',                      'type' => 'text'],
        ['prop' => 'no_providers',              'label' => 'Número de Proveedores',          'type' => 'text'],
        ['prop' => 'no_servicio_cfe',           'label' => 'Número de Servicio CFE',         'type' => 'text'],
        ['prop' => 'no_servicio_agua',          'label' => 'Número de Servicio de Agua',     'type' => 'text'],
        ['prop' => 'no_servicio_internet',      'label' => 'Número de Servicio de Internet', 'type' => 'text'],
        ['prop' => 'fecha_pago_cfe',            'label' => 'Fecha de Pago CFE',              'type' => 'date'],
        ['prop' => 'fecha_pago_agua',           'label' => 'Fecha de Pago de Agua',          'type' => 'date'],
        ['prop' => 'fecha_pago_internet',       'label' => 'Fecha de Pago de Internet',      'type' => 'date']
    ];

    protected $fillable = [
        'name',
        'street',
        'postal_code',
        'state',
        'town',
        'country',
        'external_number',
        'workin_hours',
        'created_by_id',
        'phone',
        'email',
        'website',
        'no_providers',
        'owner_id',
        'no_servicio_cfe',
        'no_servicio_agua',
        'no_servicio_internet',
        'fecha_pago_cfe',
        'fecha_pago_agua',
        'fecha_pago_internet',
        'updated_by_id'
    ];

    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function updatedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function lineAnyItems() : HasMany
    {
        return $this->hasMany(LineAnyItem::class);
    }

    public function boxes() : HasMany
    {
        return $this->hasMany(Box::class)->orderBy('created_at', 'desc');
    }
}
