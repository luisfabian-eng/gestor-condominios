<?php

namespace App\Http\Controllers;

use App\Models\Condominium;
use Illuminate\Http\Request;

class CondominiumController extends Controller
{
    // 1. Muestra la lista de todos los condominios
    public function index()
    {
        $condominiums = Condominium::all();
        return view('condominiums.index', compact('condominiums'));
    }

    // 2. Muestra el formulario para crear un nuevo condominio
    public function create()
    {
        return view('condominiums.create');
    }

    // 3. Recibe los datos del formulario y los guarda en la base de datos
    public function store(Request $request)
    {
        // Validamos que los datos vengan correctamente
        $request->validate([
            'name' => 'required|string|max:255',
            'rut' => 'nullable|string|max:12|unique:condominiums', // Ideal para el formato chileno
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        // Guardamos en la base de datos
        Condominium::create($request->all());

        // Redirigimos a la lista con un mensaje de éxito
        return redirect()->route('condominiums.index')
                         ->with('status', '¡Comunidad registrada con éxito!');
    }

    public function show(string $id) {}

    // 4. Muestra el formulario para editar
    public function edit(string $id)
    {
        // Buscamos la comunidad por su ID
        $condominium = Condominium::findOrFail($id);
        return view('condominiums.edit', compact('condominium'));
    }

    // 5. Actualiza los datos en la base de datos
    public function update(Request $request, string $id)
    {
        // Validamos los datos (mantenemos las reglas similares a la creación)
        $request->validate([
            'name' => 'required|string|max:255',
            'rut' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        // Buscamos la comunidad y actualizamos sus datos
        $condominium = Condominium::findOrFail($id);
        $condominium->update($request->all());

        return redirect()->route('condominiums.index')
                         ->with('status', '¡Comunidad actualizada con éxito!');
    }

    // 6. Elimina el registro
    public function destroy(string $id)
    {
        // Buscamos y eliminamos la comunidad de la base de datos
        $condominium = Condominium::findOrFail($id);
        $condominium->delete();

        return redirect()->route('condominiums.index')
                         ->with('status', '¡Comunidad eliminada correctamente!');
    }
}