<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxCut extends Model
{
    use HasFactory;

    protected $table = 'box_cut';
    public const RELATED_LIST_COLUMNS = [
        ['prop' => 'cash_calculated',    'label' => 'Calculado',         'width' => '120', 'type' => 'currency'],
        ['prop' => 'cash_diff',          'label' => 'Diferencia',        'width' => '120', 'type' => 'currency'],
        ['prop' => 'cash_withdrawal',    'label' => 'Retiro',            'width' => '120', 'type' => 'currency'],
        ['prop' => 'name',               'label' => 'Concepto',            'width' => '150', 'type' => 'text'],
        ['prop' => 'box_id',             'label' => 'Caja',              'type' => 'text']
    ];
    public const MODAL_FORM_FIELDS = [
        ['prop' => 'cash_box',           'label' => 'Caja',              'type' => 'text'],
        ['prop' => 'cash_calculated',    'label' => 'Calculado',         'type' => 'text'],
        ['prop' => 'cash_diff',          'label' => 'Diferencia',        'type' => 'text'],
        ['prop' => 'cash_withdrawal',    'label' => 'Retiro',            'type' => 'text'],
        ['prop' => 'name',               'label' => 'Nombre',            'type' => 'text'],
        ['prop' => 'seller_id',          'label' => 'Vendedor',          'type' => 'text'],
        ['prop' => 'created_by_id',      'label' => 'Creado por',        'type' => 'text'],
        ['prop' => 'store_id',           'label' => 'Tienda',            'type' => 'text'],
        ['prop' => 'box_id',             'label' => 'Caja',              'type' => 'text'],
        ['prop' => 'withdrawal_expenses', 'label' => 'Gastos de retiro', 'type' => 'text'],
        ['prop' => 'amount_released',    'label' => 'Cantidad liberada', 'type' => 'text'],
        ['prop' => 'card_box',           'label' => 'Caja tarjeta',      'type' => 'text'],
        ['prop' => 'card_calculated',    'label' => 'Calculado tarjeta', 'type' => 'text'],
        ['prop' => 'card_diff',          'label' => 'Diferencia tarjeta', 'type' => 'text'],
        ['prop' => 'card_withdrawal',    'label' => 'Retiro tarjeta', 'type' => 'text'],
        ['prop' => 'preliminary_box',    'label' => 'Caja preliminar', 'type' => 'text'],
        ['prop' => 'calculated',         'label' => 'Calculado', 'type' => 'text'],
        ['prop' => 'diff',               'label' => 'Diferencia', 'type' => 'text'],
        ['prop' => 'withdrawal',         'label' => 'Retiro', 'type' => 'text']

    ];
    protected $fillable = [
        'cash_box',
        'cash_calculated',
        'cash_diff',
        'cash_withdrawal',
        'name',
        'seller_id',
        'created_by_id',
        'edited_by_id',
        'store_id',
        'box_id',
        'withdrawal_expenses',
        'amount_released',
        'card_box',
        'card_calculated',
        'card_diff',
        'card_withdrawal',
        'preliminary_box',
        'calculated',
        'diff',
        'withdrawal'
    ];


    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
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

    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }
}
