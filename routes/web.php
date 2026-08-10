<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CondominiumController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\CommonExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta principal (la que se ve al entrar a localhost:8000/)
Route::get('/', function () {
    return redirect('home');
});

// Rutas de autenticación generadas por el sistema (login, registro)
Auth::routes();

// Ruta del dashboard al iniciar sesión (El HomeController decide a dónde enviarte)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// RUTAS PROTEGIDAS SOLO PARA ADMINISTRADORES
// Aquí agregamos el guardia "CheckAdmin" para bloquear a los residentes curiosos
Route::middleware(['auth', \App\Http\Middleware\CheckAdmin::class])->group(function () {
    Route::resource('condominiums', CondominiumController::class);
    Route::resource('units', UnitController::class);
    Route::resource('residents', ResidentController::class);
    Route::resource('common_expenses', CommonExpenseController::class);
    Route::patch('common_expenses/{id}/pay', [CommonExpenseController::class, 'markAsPaid'])->name('common_expenses.pay');
    Route::get('/reportes/unidades/pdf', [ReportController::class, 'downloadUnitsPdf'])->name('reports.units.pdf');
    Route::resource('tickets', TicketController::class);

    // Menú centralizado de reportes
    Route::get('/reportes', [ReportController::class, 'menu'])->name('reports.menu');

    // Generador de reportes existente
    Route::get('/reportes/generador', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reportes/generar-pdf', [ReportController::class, 'generateCustomPdf'])->name('reports.generate.pdf');

    // Reporte Anual por Residente
    Route::get('/reportes/residentes-anual', [ReportController::class, 'residentAnnualForm'])->name('reports.resident.annual.form');
    Route::post('/reportes/residentes-anual/pdf', [ReportController::class, 'generateResidentAnnualPdf'])->name('reports.resident.annual.pdf');

    // Descarga directa del reporte de tickets
    Route::get('/reportes/tickets/pdf', [ReportController::class, 'downloadTicketsPdf'])->name('reports.tickets.pdf');

    // Formulario de filtro de tickets desde el menú (por estado y fecha)
    Route::get('/reportes/tickets', [ReportController::class, 'ticketsReportForm'])->name('reports.tickets.form');
    Route::post('/reportes/tickets/filtrado/pdf', [ReportController::class, 'generateFilteredTicketsPdf'])->name('reports.tickets.filtered.pdf');
});
