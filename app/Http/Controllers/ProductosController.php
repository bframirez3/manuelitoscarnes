<?php

namespace App\Http\Controllers;

use App\Models\ProductosModel;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = ProductosModel::all();
        return response()->json($productos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Metodo para crear el producto
     */
    public function store(Request $request)
    {
        ProductosModel::create(request()->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * metodo para modificar el producto
     */
    public function update(Request $request, string $id)
    {

        ProductosModel::where('_id', $id)
            ->update(
                [
                    'nombre' => $request['nombre'],
                    'detalle' => $request['detalle'],
                    'tipo' => $request['tipo'],
                    'estado' => $request['estado'],
                    'cantidad' => $request['cantidad'],
                    'fecha' => $request['fecha'],
                    'acciones' => $request['acciones']
                ]
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ProductosModel::find($id)->delete();
    }
}
