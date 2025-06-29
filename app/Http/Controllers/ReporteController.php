<?php

namespace App\Http\Controllers;

use App\Models\RegistrosEntradaModel;
use App\Models\RegistrosSalidaModel;
use Illuminate\Http\Request;
use App\Models\ProductosModel;
use MongoDB\BSON\UTCDateTime;
class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = ProductosModel::all(['_id', 'nombre']);
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Convertir fechaInicio
        $fechaInicio = new UTCDateTime((new \DateTime($request['fechaInicio']))->getTimestamp() * 1000);
        $fechaFin = new UTCDateTime((new \DateTime($request['fechaFin']))->getTimestamp() * 1000);

        $registros = null;
        if ($request['tipo'] == 'Ingreso de inventario') {
            $registros = RegistrosEntradaModel::select('_id', 'entrada', 'updated_at')
                ->where('id', $request['producto'])
                ->where('updated_at', '>=', $fechaInicio)
                ->where('updated_at', '<=', $fechaFin)
                ->get();
        }
        if ($request['tipo'] == 'Salida de inventario') {
            $registros = RegistrosSalidaModel::select('_id', 'entrada', 'updated_at')
                ->where('id_producto', $request['producto'])
                ->where('updated_at', '>=', $fechaInicio)
                ->where('updated_at', '<=', $fechaFin)
                ->get();
        }
        return response()->json($registros);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
