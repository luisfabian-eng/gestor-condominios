<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Resident;
use App\Models\Condominium;
use App\Models\CommonExpense;

class ReportController extends Controller
{
    // Menú principal de reportes
    public function menu()
    {
        return view('reports.menu');
    }

    // Formulario para seleccionar Residente y Año
    public function residentAnnualForm()
    {
        $residents = Resident::with(['unit', 'unit.condominium'])->orderBy('name')->get();
        $years = CommonExpense::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('reports.resident-annual-form', compact('residents', 'years'));
    }

    // Generar PDF del Reporte Anual
    public function generateResidentAnnualPdf(Request $request)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'year'        => 'required|integer',
        ]);

        $resident = Resident::with(['unit.condominium'])->findOrFail($request->resident_id);
        $year = $request->year;

        // Gastos del departamento de ese residente para el año especificado
        $expenses = CommonExpense::where('unit_id', $resident->unit_id)
            ->where('year', $year)
            ->get();

        // Mapeo ordenado de los 12 meses
        $months = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        $monthlyData = [];
        $totalPaid = 0;
        $totalPending = 0;

        foreach ($months as $month) {
            $record = $expenses->firstWhere('month', $month);

            $amount = $record ? $record->amount : 0;
            $status = $record ? $record->status : 'No emitido';
            $paidDate = ($record && $status === 'Pagado') ? $record->updated_at->format('d/m/Y') : '-';

            if ($status === 'Pagado') {
                $totalPaid += $amount;
            } elseif ($status === 'Pendiente') {
                $totalPending += $amount;
            }

            $monthlyData[] = [
                'month'     => $month,
                'amount'    => $amount,
                'status'    => $status,
                'paid_date' => $paidDate,
            ];
        }

        $pdf = PDF::loadView('reports.resident-annual-pdf', compact(
            'resident',
            'year',
            'monthlyData',
            'totalPaid',
            'totalPending'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('reporte-anual-' . $resident->name . '-' . $year . '.pdf');
    }

    public function index()
    {
        $condominiums = Condominium::all();
        return view('reports.index', compact('condominiums'));
    }
    public function generateCustomPdf(Request $request)
    {
        $request->validate([
            'module' => 'required|in:units,residents,condominiums',
            'columns' => 'required|array|min:1',
        ], [
            'columns.required' => 'Debes seleccionar al menos una columna para exportar.'
        ]);

        $module = $request->input('module');
        $selectedColumns = $request->input('columns', []);
        $condominiumId = $request->input('condominium_id');
        $paperOrientation = $request->input('orientation', 'portrait');

        $query = null;
        $title = '';

        if ($module === 'units') {
            $title = 'Reporte Personalizado de Unidades';
            $query = Unit::with('condominium');
            if ($condominiumId) {
                $query->where('condominium_id', $condominiumId);
            }
        } elseif ($module === 'residents') {
            $title = 'Reporte Personalizado de Residentes';
            $query = Resident::with(['unit', 'unit.condominium']);
            if ($condominiumId) {
                $query->whereHas('unit', function ($q) use ($condominiumId) {
                    $q->where('condominium_id', $condominiumId);
                });
            }
        } elseif ($module === 'condominiums') {
            $title = 'Reporte Personalizado de Comunidades';
            $query = Condominium::query();
        }

        $records = $query->get();

        $pdf = PDF::loadView('reports.universal-pdf', compact('records', 'selectedColumns', 'module', 'title'))
            ->setPaper('a4', $paperOrientation);

        return $pdf->download('reporte-' . $module . '-' . date('Ymd_His') . '.pdf');
    }
    public function downloadUnitsPdf()
    {
        // Obtiene todas las unidades junto a su comunidad asignada
        $units = Unit::with('condominium')->get();

        // Carga la vista Blade y la prepara en formato A4 vertical
        $pdf = PDF::loadView('reports.units-pdf', compact('units'))
            ->setPaper('a4', 'portrait');

        // Descarga directa del archivo
        return $pdf->download('reporte-unidades-' . date('Ymd_His') . '.pdf');
    }
}
