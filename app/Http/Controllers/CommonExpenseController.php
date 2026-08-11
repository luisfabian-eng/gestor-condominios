<?php

namespace App\Http\Controllers;

use App\Models\CommonExpense;
use App\Models\Unit;
use Illuminate\Http\Request;

class CommonExpenseController extends Controller
{
    public function index(Request $request)
    {
        // 1. Años disponibles para el filtro
        $availableYears = CommonExpense::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Periodo seleccionado (por defecto el actual)
        $selectedYear = $request->get('year', date('Y'));
        $selectedMonth = $request->get('month', 'Agosto');

        // 2. Consulta con filtro de Año y Mes
        $query = CommonExpense::with(['unit.condominium', 'unit.resident'])
            ->where('year', $selectedYear)
            ->where('month', $selectedMonth);

        $expenses = $query->get();

        // 3. Conteos y Totales para el resumen
        $pendingCount  = $expenses->where('status', 'Pendiente')->count();
        $pendingAmount = $expenses->where('status', 'Pendiente')->sum('amount');

        $paidCount  = $expenses->where('status', 'Pagado')->count();
        $paidAmount = $expenses->where('status', 'Pagado')->sum('amount');

        return view('common_expenses.index', compact(
            'expenses',
            'availableYears',
            'selectedYear',
            'selectedMonth',
            'pendingCount',
            'pendingAmount',
            'paidCount',
            'paidAmount'
        ));
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

    public function storeBulk(Request $request)
    {
        $request->validate([
            'month'    => 'required|string',
            'year'     => 'required|integer',
            'amount'   => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $units = Unit::all();

        foreach ($units as $unit) {
            CommonExpense::create([
                'unit_id'  => $unit->id,
                'month'    => $request->month,
                'year'     => $request->year,
                'amount'   => $request->amount,
                'due_date' => $request->due_date,
                'status'   => 'Pendiente',
            ]);
        }

        return redirect()->route('common_expenses.index')
            ->with('status', '¡Gastos comunes emitidos exitosamente a ' . $units->count() . ' unidades!');
    }
}
