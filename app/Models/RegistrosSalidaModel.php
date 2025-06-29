<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model as MongoModel;
class RegistrosSalidaModel extends MongoModel
{
    protected $connection = 'mongodb';
    protected $collection = 'registrosalida';
    protected $fillable = ['id', 'id_producto', 'salida', 'fecha'];
}
