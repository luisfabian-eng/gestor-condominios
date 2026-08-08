<?php

namespace App\Http\Controllers;

use App\Models\CommonExpense;
use App\Models\Unit;
use Illuminate\Http\Request;

class CommonExpenseController extends Controller
{
    public function index()
    {
        // AQUÍ ESTÁ LA MAGIA: Ahora cargamos también al 'resident' de cada unidad
        $expenses = CommonExpense::with(['unit.condominium', 'unit.resident'])->latest()->get();
        return view('common_expenses.index', compact('expenses'));
    }

    public function create()
    {
        $units = Unit::with(['condominium', 'resident'])->get();
        return view('common_expenses.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'month' => 'required|string|max:20',
            'year' => 'required|integer|min:2020',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        CommonExpense::create($request->all());

        return redirect()->route('common_expenses.index')
                         ->with('status', '¡Cobro registrado exitosamente!');
    }

    public function show(string $id) {}

    public function markAsPaid(string $id)
    {
        $expense = CommonExpense::findOrFail($id);
        $expense->update(['status' => 'Pagado']);
        return redirect()->back()->with('status', '¡Pago registrado correctamente!');
    }

    public function edit(string $id)
    {
        $expense = CommonExpense::findOrFail($id);
        $units = Unit::with(['condominium', 'resident'])->get();
        return view('common_expenses.edit', compact('expense', 'units'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'month' => 'required|string|max:20',
            'year' => 'required|integer|min:2020',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $expense = CommonExpense::findOrFail($id);
        $expense->update($request->all());

        return redirect()->route('common_expenses.index')
                         ->with('status', '¡Cobro actualizado exitosamente!');
    }

    public function destroy(string $id)
    {
        $expense = CommonExpense::findOrFail($id);
        $expense->delete();

        return redirect()->route('common_expenses.index')
                         ->with('status', '¡Cobro eliminado correctamente!');
    }
}