<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model as MongoModel;

class UsuariosModel extends MongoModel
{
    protected $connection = 'mongodb';
    protected $collection = 'usuarios';
    protected $fillable = ['id', 'nombre', 'apellido', 'contrasenia', 'estado', 'email'];
}
