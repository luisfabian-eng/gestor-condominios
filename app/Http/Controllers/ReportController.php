<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Resident;
use App\Models\Condominium;
use App\Models\CommonExpense;
use App\Models\Ticket;

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

    // Descarga directa de todas las incidencias (usado desde el botón de la vista de tickets)
    public function downloadTicketsPdf()
    {
        $tickets = Ticket::with('user')->latest()->get();

        $pdf = PDF::loadView('reports.tickets-pdf', [
            'tickets' => $tickets,
            'title'   => 'Reporte General de Incidencias y Mantenimiento'
        ])->setPaper('a4', 'landscape');

        return $pdf->download('reporte-incidencias-' . date('Ymd_His') . '.pdf');
    }

    // Formulario de reportes de tickets desde el menú
    public function ticketsReportForm()
    {
        return view('reports.tickets-form');
    }

    // Generar PDF de tickets con filtros (estado / rango de fechas)
    public function generateFilteredTicketsPdf(Request $request)
    {
        $query = Ticket::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->latest()->get();

        $pdf = PDF::loadView('reports.tickets-pdf', [
            'tickets' => $tickets,
            'title'   => 'Reporte Filtrado de Incidencias'
        ])->setPaper('a4', 'landscape');

        return $pdf->download('reporte-incidencias-filtrado-' . date('Ymd_His') . '.pdf');
    }

    public function payments(Request $request)
    {
        // 1. Obtener lista de años disponibles para el filtro
        $availableYears = CommonExpense::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Periodo seleccionado (por defecto el actual)
        $selectedYear = $request->get('year', date('Y'));
        $selectedMonth = $request->get('month', 'Agosto');

        // 2. Consulta filtrada por año y mes con sus relaciones
        $expenses = CommonExpense::with(['unit.condominium', 'unit.resident'])
            ->where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get();

        // 3. Separación de colecciones: Pagados vs Pendientes (No Pagados)
        $paidExpenses    = $expenses->where('status', 'Pagado');
        $pendingExpenses = $expenses->where('status', 'Pendiente');

        // Totalizadores
        $totalPaidAmount    = $paidExpenses->sum('amount');
        $totalPendingAmount = $pendingExpenses->sum('amount');

        return view('reports.payments', compact(
            'availableYears',
            'selectedYear',
            'selectedMonth',
            'paidExpenses',
            'pendingExpenses',
            'totalPaidAmount',
            'totalPendingAmount'
        ));
    }

    public function exportPdf(Request $request)
    {
        $selectedYear = $request->get('year', date('Y'));
        $selectedMonth = $request->get('month', 'Agosto');

        $expenses = CommonExpense::with(['unit.condominium', 'unit.resident'])
            ->where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get();

        $paidExpenses    = $expenses->where('status', 'Pagado');
        $pendingExpenses = $expenses->where('status', 'Pendiente');

        $totalPaidAmount    = $paidExpenses->sum('amount');
        $totalPendingAmount = $pendingExpenses->sum('amount');

        // Carga una vista Blade optimizada exclusivamente para el PDF
        $pdf = Pdf::loadView('reports.pdf_payments', compact(
            'selectedYear',
            'selectedMonth',
            'paidExpenses',
            'pendingExpenses',
            'totalPaidAmount',
            'totalPendingAmount'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("Reporte_Pagos_{$selectedMonth}_{$selectedYear}.pdf");
    }
}
