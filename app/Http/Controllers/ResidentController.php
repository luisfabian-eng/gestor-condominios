<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // <-- AGREGAMOS ESTA HERRAMIENTA DE LARAVEL

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::with('unit.condominium')->get();
        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        $units = Unit::with('condominium')->get();
        return view('residents.create', compact('units'));
    }

    public function store(Request $request)
    {
        // 1. Validamos los datos (El correo sigue siendo obligatorio)
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'rut' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ]);

        // 2. Creamos el perfil del residente
        $resident = Resident::create($request->all());

        // 3. GENERACIÓN DE CONTRASEÑA SEGURA AUTOMÁTICA
        // Genera un texto aleatorio de 10 caracteres (letras mayúsculas, minúsculas y números)
        $securePassword = Str::random(10);

        // 4. Creamos la cuenta de usuario con la clave encriptada
        User::create([
            'name' => $resident->name,
            'email' => $resident->email,
            'password' => Hash::make($securePassword),
            'role' => 'residente',
            'resident_id' => $resident->id,
        ]);

        // 5. Devolvemos la clave en el mensaje de éxito para que el administrador la copie
        return redirect()->route('residents.index')
            ->with('status', '¡Cuenta creada! La contraseña temporal del residente es: ' . $securePassword);
    }

    public function edit(string $id)
    {
        $resident = Resident::findOrFail($id);
        $units = Unit::with('condominium')->get();
        return view('residents.edit', compact('resident', 'units'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'rut' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $resident = Resident::findOrFail($id);
        $resident->update($request->all());

        return redirect()->route('residents.index')
            ->with('status', '¡Datos del residente actualizados!');
    }

    public function destroy(string $id)
    {
        $resident = Resident::findOrFail($id);

        User::where('resident_id', $resident->id)->delete();
        $resident->delete();

        return redirect()->route('residents.index')
            ->with('status', '¡Residente y cuenta eliminados correctamente!');
    }
}
