<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\Paciente;
use App\Models\User;

class AdmisionistaController extends Controller
{
    /**
     * Mostrar vista principal.
     */
    public function index()
    {
        $admisiones = User::orderBy('id', 'DESC')->get();

        return view('admisionista.index', compact('admisiones'));
    }

    /**
     * Buscar paciente por documento.
     */
    public function buscar(Request $request)
    {
        $request->validate([
            'documento' => 'required|string'
        ]);

        $paciente = User::where('documento', $request->documento)->first();

        if (!$paciente) {
            return redirect()
                ->route('admisionista.index')
                ->with('error', 'Paciente no encontrado.');
        }

        $admisiones = User::orderBy('id', 'DESC')->get();

        return view('admisionista.index', [
            'paciente'   => $paciente,
            'admisiones' => $admisiones
        ]);
    }

    /**
     * Guardar nueva admisión.
     */
    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required|string',
            'nombre'    => 'required|string',
            'fecha'     => 'required|date',
            'estado'    => 'nullable|string',
            'acciones'  => 'nullable|string',
            'motivo'    => 'required|string'
        ]);

        User::create([
            'documento' => $request->documento,
            'nombre'    => $request->nombre,
            'fecha'     => $request->fecha,
            'estado'    => $request->estado,
            'acciones'  => $request->acciones,
            'motivo'    => $request->motivo
        ]);

        return redirect()
            ->route('admisionista.index')
            ->with('success', 'Admisión registrada correctamente.');
    }

    /**
     * Eliminar admisión.
     */
    public function destroy($id)
    {
        $adm = User::findOrFail($id);
        $adm->delete();

        return redirect()
            ->route('admisionista.index')
            ->with('success', 'Registro eliminado correctamente.');
    }

    /**
     * Mostrar vista de ingresos.
     */
    public function ingresos()
    {
        return view('admisionista.ingresos');
    }

    /**
     * Mostrar vista de pacientes.
     */
    public function pacientes()
    {
        return view('admisionista.pacientes');
    }

    /**
     * Mostrar vista de reportes.
     */
    public function reportes()
    {
        return view('admisionista.reportes');
    }
}