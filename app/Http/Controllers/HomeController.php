<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Condominium;
use App\Models\Unit;
use App\Models\CommonExpense;
use App\Models\Ticket;

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

            // Buscamos sus gastos comunes
            $expenses = collect(); // Colección vacía por defecto
            if ($resident && $resident->unit_id) {
                // Si tiene departamento, buscamos sus cobros ordenados por fecha de vencimiento
                $expenses = CommonExpense::where('unit_id', $resident->unit_id)
                    ->orderBy('due_date', 'desc')
                    ->get();
            }

            // Buscamos ÚNICAMENTE los tickets/requerimientos creados por este usuario
            $tickets = Ticket::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            // Enviamos $resident, $expenses y $tickets a la vista del portal
            return view('portal', compact('resident', 'expenses', 'tickets'));
        }

        // 2. SI ES ADMIN, SIGUE CON LA LÓGICA NORMAL DEL PANEL
        $totalCondominiums = Condominium::count();
        $totalUnits = Unit::count();

        $pendingAmount = CommonExpense::where('status', 'Pendiente')->sum('amount');
        $paidAmount = CommonExpense::where('status', 'Pagado')->sum('amount');

        // BUSCAMOS LOS ÚLTIMOS 5 TICKETS CREADOS PARA EL DASHBOARD ADMIN
        $tickets = Ticket::orderBy('created_at', 'desc')->take(5)->get();

        // Agregamos 'tickets' al compact para enviarlo a la vista home
        return view('home', compact('totalCondominiums', 'totalUnits', 'pendingAmount', 'paidAmount', 'tickets'));
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'urgency'  => 'required|in:Baja,Media,Alta',
        ]);

        Ticket::create([
            'user_id'  => auth()->id(),
            'title'    => $request->title,
            'location' => $request->location,
            'urgency'  => $request->urgency,
            'status'   => 'Abierto',
        ]);

        return redirect()->route('home')->with('status', '¡Tu requerimiento fue enviado correctamente!');
    }
}
