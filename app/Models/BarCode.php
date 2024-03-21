<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DNS1D;

class BarCode extends Model
{
    use HasFactory;
    protected $appends = ['bar_code'];
    protected $fillable = [
        'product_id',
        'bar_code'
    ];

    protected $casts = [

        'created_at'  => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    public function getBarCodeAttribute()
    {

        try {
            return DNS1D::getBarcodeHTML($this->folio, 'EAN13');
        } catch (\Throwable $th) {
            return $this->folio;
        }
    }
}
