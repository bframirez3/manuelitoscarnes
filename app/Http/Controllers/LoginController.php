<?php

namespace App\Http\Controllers;

use App\Models\UsuariosModel;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\IsEmpty;
use function PHPUnit\Framework\isEmpty;

class LoginController extends Controller
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

        $usuarioActual = UsuariosModel::where('email', $request->email)
            ->get();

        if (!$usuarioActual->isEmpty()) {
            if ($usuarioActual[0]['contrasenia'] == $request->password) {
                return [
                    'estado' => '200',
                    'mensaje' => 'Login exitoso',
                    'datos' => $usuarioActual
                ];
            } else {
                return [
                    "estado" => "400",
                    "mensaje" => "",
                    "mensajeError" => "La contraseña es incorrecta, por favor validar.!",
                    "datos" => []
                ];
            }
        } else {
            return [
                "estado" => "400",
                "mensaje" => "",
                "mensajeError" => "Usuario / contraseña son incorrectas, por favor validar.!",
                "datos" => []
            ];
        }


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
