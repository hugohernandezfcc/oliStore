<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Box extends Model
{
    use HasFactory;

    protected $table = 'box';
    public const RELATED_LIST_COLUMNS = [
        ['prop' => 'status',        'label' => 'Estatus',       'width' => '100', 'type' => 'text'],
        ['prop' => 'founds_box',    'label' => 'Fondos',        'width' => '110', 'type' => 'currency'],
        ['prop' => 'amount',        'label' => 'Cantidad',      'width' => '110', 'type' => 'currency'],
        ['prop' => 'description',   'label' => 'Descripción',   'width' => '400', 'type' => 'text'],
        ['prop' => 'box_date',      'label' => 'Fecha',         'width' => '110', 'type' => 'text'],
        ['prop' => 'name',          'label' => 'Nombre',        'width' => '150', 'type' => 'text']
    ];
    public const MODAL_FORM_FIELDS = [
        ['prop' => 'box_date',       'label' => 'Fecha',              'type' => 'text'],
        ['prop' => 'name',           'label' => 'Nombre',             'type' => 'text'],
        ['prop' => 'status',         'label' => 'Estatus',            'type' => 'text'],
        ['prop' => 'founds_box',     'label' => 'Fondos de caja',     'type' => 'text'],
        ['prop' => 'amount',         'label' => 'Cantidad',           'type' => 'text'],
        ['prop' => 'description',    'label' => 'Descripción',        'type' => 'text'],
        ['prop' => 'seller_id',      'label' => 'Vendedor',           'type' => 'text'],
        ['prop' => 'created_by_id',  'label' => 'Creado por',         'type' => 'text'],
        ['prop' => 'edited_by_id',   'label' => 'Editado por',        'type' => 'text'],
        ['prop' => 'store_id',       'label' => 'Tienda',             'type' => 'text']
    ];
    protected $fillable = [
        'name',
        'box_date',
        'description',
        'status',
        'founds_box',
        'amount',
        'seller_id',
        'created_by_id',
        'edited_by_id',
        'store_id',
    ];
    
    protected $casts = [

        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];
    
    public function seller(): belongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function createdBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    public function store(): belongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function boxCut(): HasMany
    {
        return $this->hasMany(BoxCut::class);
    }
}
