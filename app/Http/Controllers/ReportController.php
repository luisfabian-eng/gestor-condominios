<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Resident;
use App\Models\Condominium;

class ReportController extends Controller
{

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
