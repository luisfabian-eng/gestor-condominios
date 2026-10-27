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
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta principal
Route::get('/', function () {
    return redirect('home');
});

// Autenticación
Auth::routes();

// Portal y rutas de residentes
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/portal/tickets', [HomeController::class, 'storeTicket'])->name('portal.tickets.store');
Route::post('/portal/gastos/{id}/pagar-manual', [HomeController::class, 'uploadPayment'])->name('portal.expenses.pay_manual');

// RUTAS PROTEGIDAS PARA ADMINISTRADORES
Route::middleware(['auth', \App\Http\Middleware\CheckAdmin::class])->group(function () {
    Route::resource('condominiums', CondominiumController::class);
    Route::resource('units', UnitController::class);
    Route::resource('residents', ResidentController::class);

    // Gastos Comunes y acciones de aprobación / rechazo
    Route::resource('common_expenses', CommonExpenseController::class);
    Route::patch('common_expenses/{id}/pay', [CommonExpenseController::class, 'markAsPaid'])->name('common_expenses.pay');
    Route::post('common_expenses/{id}/reject', [CommonExpenseController::class, 'rejectPayment'])->name('common_expenses.reject'); // <-- RUTA REGISTRADA AQUÍ
    Route::post('/common-expenses/bulk', [CommonExpenseController::class, 'storeBulk'])->name('common_expenses.store_bulk');

    // Usuarios
    Route::resource('users', UserController::class);

    // Tickets
    Route::resource('tickets', TicketController::class);

    // Reportes
    Route::get('/reportes', [ReportController::class, 'menu'])->name('reports.menu');
    Route::get('/reportes/unidades/pdf', [ReportController::class, 'downloadUnitsPdf'])->name('reports.units.pdf');
    Route::get('/reports/payments', [ReportController::class, 'payments'])->name('reports.payments');
    Route::get('/reports/payments/pdf', [ReportController::class, 'exportPdf'])->name('reports.payments.pdf');
    Route::get('/reportes/generador', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reportes/generar-pdf', [ReportController::class, 'generateCustomPdf'])->name('reports.generate.pdf');
    Route::get('/reportes/residentes-anual', [ReportController::class, 'residentAnnualForm'])->name('reports.resident.annual.form');
    Route::post('/reportes/residentes-anual/pdf', [ReportController::class, 'generateResidentAnnualPdf'])->name('reports.resident.annual.pdf');
    Route::get('/reportes/tickets', [ReportController::class, 'ticketsReportForm'])->name('reports.tickets.form');
    Route::get('/reportes/tickets/pdf', [ReportController::class, 'downloadTicketsPdf'])->name('reports.tickets.pdf');
    Route::post('/reportes/tickets/filtrado/pdf', [ReportController::class, 'generateFilteredTicketsPdf'])->name('reports.tickets.filtered.pdf');
});