<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model as MongoModel;
class ProductosModel extends MongoModel
{
    protected $connection = 'mongodb';
    protected $collection = 'productos';
    protected $fillable = ['nombre', 'detalle', 'tipo', 'estado', 'cantidad', 'fecha', 'acciones'];
}
