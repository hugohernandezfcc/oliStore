<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarCode;


class FieldsController extends Controller
{
    // Display a listing of the resource
    public function lookupProduct()
    {
        $data = [
            'value' => 'value',
            'link' => 'another_value',
        ];
    
        return response()->json($data);
    }

    
}
