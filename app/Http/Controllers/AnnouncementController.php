<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Condominium;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $announcements = Announcement::with('condominium')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        $condominiums = Condominium::orderBy('name')->get();
        return view('announcements.create', compact('condominiums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'priority'       => 'required|in:Baja,Normal,Alta,Urgente',
            'condominium_id' => 'nullable|exists:condominiums,id',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480', // Hasta 20MB
        ]);

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $archivo = $request->file('attachment');
            $ext = $archivo->getClientOriginalExtension();
            $tituloSlug = Str::slug($request->title, '_');
            $fecha = Carbon::now()->format('dmY_His');
            $nombreArchivo = "circular_{$tituloSlug}_{$fecha}.{$ext}";

            // Subir usando public_direct
            $archivo->storeAs('', $nombreArchivo, 'public_direct');
            $attachmentPath = 'uploads/receipts/' . $nombreArchivo;
        }

        Announcement::create([
            'condominium_id'  => $request->condominium_id,
            'title'           => $request->title,
            'content'         => $request->content,
            'priority'        => $request->priority,
            'attachment_path' => $attachmentPath,
            'is_active'       => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('announcements.index')
            ->with('status', '¡Aviso publicado exitosamente!');
    }

    public function edit(string $id)
    {
        $announcement = Announcement::findOrFail($id);
        $condominiums = Condominium::orderBy('name')->get();

        return view('announcements.edit', compact('announcement', 'condominiums'));
    }

    public function update(Request $request, string $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'priority'       => 'required|in:Baja,Normal,Alta,Urgente',
            'condominium_id' => 'nullable|exists:condominiums,id',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);

        $attachmentPath = $announcement->attachment_path;

        if ($request->hasFile('attachment')) {
            // Eliminar anterior si existe
            if ($announcement->attachment_path && file_exists(public_path($announcement->attachment_path))) {
                @unlink(public_path($announcement->attachment_path));
            }

            $archivo = $request->file('attachment');
            $ext = $archivo->getClientOriginalExtension();
            $tituloSlug = Str::slug($request->title, '_');
            $fecha = Carbon::now()->format('dmY_His');
            $nombreArchivo = "circular_{$tituloSlug}_{$fecha}.{$ext}";

            $archivo->storeAs('', $nombreArchivo, 'public_direct');
            $attachmentPath = 'uploads/receipts/' . $nombreArchivo;
        }

        $announcement->update([
            'condominium_id'  => $request->condominium_id,
            'title'           => $request->title,
            'content'         => $request->content,
            'priority'        => $request->priority,
            'attachment_path' => $attachmentPath,
            'is_active'       => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('announcements.index')
            ->with('status', '¡Aviso actualizado correctamente!');
    }

    public function destroy(string $id)
    {
        $announcement = Announcement::findOrFail($id);

        if ($announcement->attachment_path && file_exists(public_path($announcement->attachment_path))) {
            @unlink(public_path($announcement->attachment_path));
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('status', '¡Aviso eliminado del sistema!');
    }
}