<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade as PDF;

class ReportController extends Controller
{
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
