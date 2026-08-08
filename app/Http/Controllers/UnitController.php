<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Condominium;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('condominium')->get();
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $condominiums = Condominium::all();
        return view('units.create', compact('condominiums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'condominium_id' => 'required|exists:condominiums,id',
            'number' => 'required|string|max:255',
            'type' => 'required|string',
            'prorata' => 'required|numeric|min:0|max:100',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')
                         ->with('status', '¡Unidad registrada con éxito!');
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        $unit = Unit::findOrFail($id);
        $condominiums = Condominium::all();
        return view('units.edit', compact('unit', 'condominiums'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'condominium_id' => 'required|exists:condominiums,id',
            'number' => 'required|string|max:255',
            'type' => 'required|string',
            'prorata' => 'required|numeric|min:0|max:100',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update($request->all());

        return redirect()->route('units.index')
                         ->with('status', '¡Unidad actualizada con éxito!');
    }

    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('units.index')
                         ->with('status', '¡Unidad eliminada correctamente!');
    }
}