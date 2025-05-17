<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productb2b extends Model
{
    use HasFactory;

    public const WIZARD_COLUMNS = [
        'name' => 'Nombre',
        'folio' => 'Folio',
        'description' => 'Descripción',
        'unit_measure' => 'Unidad de Medida',
        'expiration_range' => 'Rango de Expiración',
        'bimbo' => 'Bimbo',
        'marinela' => 'Marinela',
        'sabritas' => 'Sabritas',
        'barcel' => 'Barcel',
        'cigars' => 'Cigarros',
        'order' => 'Orden',
        'promo' => 'Promo',
        'bulkSale' => 'Bulk Sale',
        'drinks' => 'Bebidas',
        'snacks' => 'Botanas',
        'groceries' => 'Abarrotes',
        'cleaning' => 'Limpieza',
        'underFox' => 'Bajo del Zorro',
        'package' => 'Paquete',
        'bundle' => 'Cantidad por caja',
        ':::cost' => 'Costo',
        ':::price' => 'Precio',
        'image' => 'Imagen'
    ];


    protected $table = 'productb2b';
    protected $fillable = [
        'name',
        'folio',
        'description',
        'unit_measure',
        'expiration_range',
        'created_by_id',
        'edited_by_id',
        'sold_out',
        'is_public',
        'is_private',
        'public_description',
        'public_name',
        'image',
        'promo',
        'bulkSale',
        'drinks',
        'snacks',
        'groceries',
        'cleaning',
        'underFox',
        'package',
        'bundle',
        'cigars',
        'order',
        'bimbo',
        'marinela',
        'sabritas',
        'barcel'
    ];

    protected $casts = [
        'bimbo'     => 'boolean',
        'marinela'  => 'boolean',
        'sabritas'  => 'boolean',
        'barcel'    => 'boolean',
        'sold_out'  => 'boolean',
        'cigars'    => 'boolean',
        'order'     => 'integer',
        'promo'     => 'boolean',
        'bulkSale'  => 'boolean',
        'drinks'    => 'boolean',
        'snacks'    => 'boolean',
        'groceries' => 'boolean',
        'cleaning'  => 'boolean',
        'underFox'  => 'boolean',
        'package'   => 'boolean',
        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];


    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by_id');
    }

    public function pricebookEntries()
    {
        return $this->hasMany(PriceBookEntry::class, 'productb2b_id');
    }
}
