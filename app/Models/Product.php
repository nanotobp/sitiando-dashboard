<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nombre',
        'sku',
        'precio',
        'stock',
        'imagen',
        'activo'
    ];
}
