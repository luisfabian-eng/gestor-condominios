<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Condominium;
use App\Models\Unit;
use App\Models\CommonExpense;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. INTERCEPTAMOS AL RESIDENTE
        if (auth()->user()->role === 'residente') {
            $resident = auth()->user()->resident; 
            
            // AQUÍ ESTÁ LA SOLUCIÓN AL ERROR: Buscamos sus gastos comunes
            $expenses = collect(); // Creamos una colección vacía por defecto
            if ($resident && $resident->unit_id) {
                // Si tiene departamento, buscamos sus cobros ordenados por fecha
                $expenses = CommonExpense::where('unit_id', $resident->unit_id)
                                         ->orderBy('due_date', 'desc')
                                         ->get();
            }

            // Le enviamos la variable $expenses a la vista
            return view('portal', compact('resident', 'expenses'));
        }

        // 2. SI ES ADMIN, SIGUE CON LA LÓGICA NORMAL DEL PANEL
        $totalCondominiums = Condominium::count();
        $totalUnits = Unit::count();
        
        $pendingAmount = CommonExpense::where('status', 'Pendiente')->sum('amount');
        $paidAmount = CommonExpense::where('status', 'Pagado')->sum('amount');

        return view('home', compact('totalCondominiums', 'totalUnits', 'pendingAmount', 'paidAmount'));
    }
}