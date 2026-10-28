<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Condominium;
use App\Models\Unit;
use App\Models\CommonExpense;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Announcement;

class HomeController extends Controller
{
    public function __construct()
    {
        // Protegemos el controlador, pero permitimos las peticiones del portal del residente
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. INTERCEPTAMOS AL RESIDENTE
        if (auth()->user()->role === 'residente') {
            $resident = auth()->user()->resident;

            $expenses = collect();
            $condominiumId = null;

            if ($resident && $resident->unit_id) {
                $expenses = CommonExpense::where('unit_id', $resident->unit_id)
                    ->orderBy('due_date', 'desc')
                    ->get();

                $condominiumId = optional($resident->unit)->condominium_id;
            }

            // Traer avisos activos de su condominio o globales
            $announcements = Announcement::where('is_active', true)
                ->when($condominiumId, function ($query) use ($condominiumId) {
                    $query->where(function ($q) use ($condominiumId) {
                        $q->where('condominium_id', $condominiumId)
                        ->orWhereNull('condominium_id');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $tickets = Ticket::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            return view('portal', compact('resident', 'expenses', 'tickets', 'announcements'));
        }

        // 2. SI ES ADMIN, SIGUE CON LA LÓGICA NORMAL DEL PANEL
        $totalCondominiums = Condominium::count();$totalUnits = Unit::count();

        $pendingAmount = CommonExpense::where('status', 'Pendiente')->sum('amount');$paidAmount = CommonExpense::where('status', 'Pagado')->sum('amount');

        // ÚLTIMOS TICKETS / REQUERIMIENTOS
        $tickets = Ticket::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        // ÚLTIMOS COBROS / GASTOS COMUNES (prioriza 'En revisión' y luego los más recientes)
        $recentExpenses = CommonExpense::with(['unit.resident'])
            ->orderByRaw("CASE WHEN status = 'En revisión' THEN 1 WHEN status = 'Pendiente' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('home', compact(
            'totalCondominiums', 
            'totalUnits', 
            'pendingAmount', 
            'paidAmount', 
            'tickets', 
            'recentExpenses'
        ));
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

    /*public function uploadPayment(Request $request, $id)
    {
        // Validamos que suba un archivo válido (Imagen o PDF, máx 2MB)
        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $expense = CommonExpense::findOrFail($id);
        $resident = auth()->user()->resident;

        // MEDIDA DE SEGURIDAD: Validar que el gasto común pertenezca a la unidad del residente logueado
        if (!$resident || $expense->unit_id !== $resident->unit_id) {
            abort(403, 'No estás autorizado para realizar esta acción.');
        }

        // Guardamos el archivo y actualizamos el estado
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');

            $expense->update([
                'receipt_path' => $path,
                'status'       => 'En revisión' // Cambia el estado para que el admin lo revise
            ]);
        }

        return redirect()->route('home')->with('status', '¡Comprobante enviado! El administrador lo revisará pronto.');
    }*/

    public function uploadPayment(Request $request, $id)
    {
        // Validación (Imagen o PDF, hasta 20MB)
        $request->validate([
            'receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:20480',
        ]);

        // Cargamos el gasto común con la unidad, condominio y residente
        $expense = CommonExpense::with(['unit.condominium', 'unit.resident'])->findOrFail($id);
        $resident = auth()->user()->resident;

        // Validación de seguridad: debe ser el residente de la unidad
        if (!$resident || $expense->unit_id !== $resident->unit_id) {
            abort(403, 'No estás autorizado para realizar esta acción.');
        }

        if ($request->hasFile('receipt')) {
            $archivo = $request->file('receipt');
            $ext = $archivo->getClientOriginalExtension();

            // Datos para armar el nombre estructurado y limpio
            $idGasto = $expense->id;
            $condominio = Str::slug($expense->unit->condominium->name ?? 'condominio', '_');
            $depto = Str::slug($expense->unit->number ?? '000', '_');
            $mes = Str::slug($expense->month ?? 'mes', '_');
            $ano = $expense->year ?? date('Y');
            $fechaActual = Carbon::now()->format('dmY_His');

            // Ejemplo: comprobante_15_condominio_central_dpto_302_agosto_2026_28102026_093015.pdf
            $nombreArchivo = "comprobante_{$idGasto}_{$condominio}_dpto_{$depto}_{$mes}_{$ano}_{$fechaActual}.{$ext}";

            // Subir a la carpeta pública directamente mediante el disco personalizado
            $archivo->storeAs('', $nombreArchivo, 'public_direct');

            // Guardamos el nombre o ruta del archivo y actualizamos el estado
            $expense->receipt_path = $nombreArchivo;
            $expense->status = 'En revisión';
            $expense->save();
        }

        return redirect()->route('home')->with('status', '¡Comprobante enviado! El administrador lo revisará pronto.');
    }
}