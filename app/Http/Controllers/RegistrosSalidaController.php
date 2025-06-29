<?php

namespace App\Http\Controllers;

use App\Models\ProductosModel;
use Illuminate\Http\Request;
use App\Models\RegistrosSalidaModel;
class RegistrosSalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //RegistrosSalidaModel::create(request()->all());
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
     * Update the specified resource in storage.
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
        RegistrosSalidaModel::create([
            'id' => $id,
            'id_producto' => $id,
            'salida' => $request['valorAntiguo'],
            'fecha_entrada' => $request['fecha']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
