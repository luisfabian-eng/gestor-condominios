<?php

namespace App\Http\Controllers;

use App\Models\CommonExpense;
use App\Models\Unit;
use Illuminate\Http\Request;

class CommonExpenseController extends Controller
{
    public function index(Request $request)
    {
        $availableYears = CommonExpense::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $selectedYear = $request->get('year', date('Y'));
        // Por defecto mostrará "Todos" si no se ha seleccionado nada
        $selectedMonth = $request->get('month', 'Todos'); 

        $query = CommonExpense::with(['unit.condominium', 'unit.resident'])
            ->where('year', $selectedYear);

        // Si el mes no es "Todos", aplicamos el filtro del mes
        if ($selectedMonth !== 'Todos') {
            $query->where('month', $selectedMonth);
        }

        $expenses = $query->get();

        $pendingCount  = $expenses->where('status', 'Pendiente')->count();
        $pendingAmount = $expenses->where('status', 'Pendiente')->sum('amount');
        $reviewCount   = $expenses->where('status', 'En revisión')->count();
        $paidCount     = $expenses->where('status', 'Pagado')->count();
        $paidAmount    = $expenses->where('status', 'Pagado')->sum('amount');

        return view('common_expenses.index', compact(
            'expenses', 'availableYears', 'selectedYear', 'selectedMonth',
            'pendingCount', 'pendingAmount', 'reviewCount', 'paidCount', 'paidAmount'
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
            'unit_id'  => 'required|exists:units,id',
            'month'    => 'required|string|max:20',
            'year'     => 'required|integer|min:2020',
            'amount'   => 'required|numeric|min:0',
            'concept'  => 'nullable|string|max:255',
            'due_date' => 'required|date',
        ]);

        CommonExpense::create([
            'unit_id'  => $request->unit_id,
            'month'    => $request->month,
            'year'     => $request->year,
            'amount'   => $request->amount,
            'concept'  => $request->concept ?? 'Gasto Común Ordinario',
            'due_date' => $request->due_date,
            'status'   => 'Pendiente',
        ]);

        return redirect()->route('common_expenses.index')->with('status', '¡Cobro registrado exitosamente!');
    }

    public function show(string $id) {}

    public function markAsPaid(string $id)
    {
        $expense = CommonExpense::findOrFail($id);
        $expense->update(['status' => 'Pagado']);
        return redirect()->back()->with('status', '¡Pago registrado y aprobado correctamente!');
    }

    public function rejectPayment(Request $request, string $id)
    {
        $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        $expense = CommonExpense::findOrFail($id);
        $expense->update([
            'status'         => 'Pendiente',
            'rejection_note' => $request->rejection_note,
        ]);

        return redirect()->back()->with('status', 'El comprobante fue rechazado y la observación se envió al residente.');
    }

    public function edit(string $id)
    {
        $expense = CommonExpense::findOrFail($id);
        $units = Unit::with(['condominium', 'resident'])->get();
        return view('common_expenses.edit', compact('expense', 'units'));
    }

    public function update(Request $request, string $id)
    {
        $expense = CommonExpense::findOrFail($id);

        if ($request->has('payment_action')) {
            $expense->update([
                'status'         => 'Pagado',
                'rejection_note' => null,
            ]);
            return redirect()->back()->with('status', '¡El gasto común fue marcado como PAGADO correctamente!');
        }

        $request->validate([
            'unit_id'  => 'required|exists:units,id',
            'month'    => 'required|string|max:20',
            'year'     => 'required|integer|min:2020',
            'amount'   => 'required|numeric|min:0',
            'concept'  => 'nullable|string|max:255',
            'due_date' => 'required|date',
        ]);

        $expense->update($request->all());
        return redirect()->route('common_expenses.index')->with('status', '¡Cobro actualizado exitosamente!');
    }

    public function destroy(string $id)
    {
        $expense = CommonExpense::findOrFail($id);
        $expense->delete();
        return redirect()->route('common_expenses.index')->with('status', '¡Cobro eliminado correctamente!');
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'month'    => 'required|string',
            'year'     => 'required|integer',
            'amount'   => 'required|numeric|min:0',
            'concept'  => 'nullable|string|max:255',
            'due_date' => 'required|date',
        ]);

        $units = Unit::all();
        foreach ($units as $unit) {
            CommonExpense::create([
                'unit_id'  => $unit->id,
                'month'    => $request->month,
                'year'     => $request->year,
                'amount'   => $request->amount,
                'concept'  => $request->concept ?? 'Gasto Común Ordinario',
                'due_date' => $request->due_date,
                'status'   => 'Pendiente',
            ]);
        }

        return redirect()->route('common_expenses.index')->with('status', '¡Gastos comunes emitidos exitosamente a ' . $units->count() . ' unidades!');
    }

    
}