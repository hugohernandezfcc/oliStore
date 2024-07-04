<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DNS1D;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    //Add extra attribute


    //Make it available in the json response
    protected $appends = ['bar_code'];
    public const RELATED_LIST_COLUMNS = [
        ['prop' => 'id',    'label' => 'id',              'width' => '50', 'type' => 'text'],
        ['prop' => 'name',    'label' => 'Nombre',              'width' => '120', 'type' => 'text'],
        ['prop' => 'folio',   'label' => 'Código de barras',    'width' => '160', 'type' => 'text'],
        ['prop' => 'Description', 'label' => 'Descripción',     'width' => '120', 'type' => 'text'],
        ['prop' => 'unit_measure', 'label' => 'Unidad de medida', 'width' => '150', 'type' => 'text'],
        ['prop' => 'price_list', 'label' => 'Precio de lista',  'width' => '120', 'type' => 'text'],
        ['prop' => 'price_customer', 'label' => 'Precio al cliente', 'width' => '120', 'type' => 'text'],
        ['prop' => 'profit_percentage', 'label' => 'Porcentaje de ganancia', 'width' => '120', 'type' => 'text'],
        ['prop' => 'expiry_date', 'label' => 'Fecha de caducidad', 'width' => '120', 'type' => 'text'],
        ['prop' => 'created_by_id', 'label' => 'Creado por', 'width' => '120', 'type' => 'text'],
        ['prop' => 'edited_by_id', 'label' => 'Editado por', 'width' => '120', 'type' => 'text'],
        ['prop' => 'take_portion', 'label' => 'Tomar porción', 'width' => '120', 'type' => 'text'],
        ['prop' => 'express_creation', 'label' => 'Creación express', 'width' => '120', 'type' => 'text'],
        ['prop' => 'visible_product', 'label' => 'Producto visible', 'width' => '120', 'type' => 'text'],
        
    ];
    public const MODAL_FORM_FIELDS = [
        ['prop' => 'id',              'label' => 'id',                'type' => 'text'],
        ['prop' => 'name',            'label' => 'Nombre',            'type' => 'text'],
        ['prop' => 'folio',           'label' => 'Código de barras',  'type' => 'text'],
        ['prop' => 'Description',     'label' => 'Descripción',       'type' => 'text'],
        ['prop' => 'unit_measure',    'label' => 'Unidad de medida',  'type' => 'text'],
        ['prop' => 'price_list',      'label' => 'Precio de lista',   'type' => 'text'],
        ['prop' => 'price_customer',  'label' => 'Precio al cliente', 'type' => 'text'],
        ['prop' => 'profit_percentage','label' => 'Porcentaje de ganancia', 'type' => 'text'],
        ['prop' => 'expiry_date',     'label' => 'Fecha de caducidad', 'type' => 'date'],
        ['prop' => 'created_by_id',   'label' => 'Creado por',        'type' => 'text'],
        ['prop' => 'edited_by_id',    'label' => 'Editado por',       'type' => 'text'],
        ['prop' => 'take_portion',    'label' => 'Tomar porción',     'type' => 'text'],
        ['prop' => 'express_creation','label' => 'Creación express',  'type' => 'text'],
        ['prop' => 'visible_product', 'label' => 'Producto visible',  'type' => 'text'],
    ];

    protected $fillable = [
        'name',
        'folio',
        'Description',
        'unit_measure',
        'price_list',
        'price_customer',
        'profit_percentage',
        'expiry_date',
        'created_by_id',
        'edited_by_id',
        'take_portion',
        'express_creation',
        'visible_product',
        'category_id',
        'sub_category_id',
        'expiry_date_range',
        'is_public',
        'is_private',
        'public_description',
        'public_name',
        'image'
    ];
    
    protected $casts = [
        'expiry_date'  => 'date:Y-m-d',
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }



    public function ProductLineItems(): HasMany
    {
        return $this->hasMany(ProductLineItem::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    //implement the attribute
    public function getBarCodeAttribute()
    {

        try {
            return DNS1D::getBarcodeHTML($this->folio, 'EAN13');
        } catch (\Throwable $th) {
            return $this->folio;
        }
    }
}