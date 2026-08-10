<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    // Mostrar todas las incidencias (Historial completo)
    public function index()
    {
        $tickets = Ticket::with('user')->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'urgency' => 'required|in:Baja,Media,Alta',
        ]);

        Ticket::create([
            'user_id'  => auth()->id(), // <-- Asigna el usuario actual
            'title'    => $request->title,
            'location' => $request->location,
            'urgency'  => $request->urgency,
            'status'   => 'Abierto',
        ]);

        return redirect()->route('tickets.index')->with('status', 'Ticket creado correctamente.');
    }

    // Mostrar la pantalla de detalle de un ticket específico
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    // Actualizar el estado del ticket (Acción rápida)
    public function update(Request $request, Ticket $ticket)
    {
        // Validamos que el estado sea correcto
        $request->validate([
            'status' => 'required|in:Abierto,En progreso,Resuelto'
        ]);

        // Actualizamos y guardamos
        $ticket->update([
            'status' => $request->status
        ]);

        // Volvemos a la pantalla anterior
        return back()->with('success', 'Estado del ticket actualizado.');
    }
}
