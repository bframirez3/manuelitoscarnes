<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model as MongoModel;
class RegistrosEntradaModel extends MongoModel
{
    protected $connection = 'mongodb';
    protected $collection = 'registroentrada';
    protected $fillable = ['id', 'id_producto', 'entrada', 'fecha'];
}
