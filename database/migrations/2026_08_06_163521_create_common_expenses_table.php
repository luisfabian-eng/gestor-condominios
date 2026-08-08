<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('common_expenses', function (Blueprint $table) {
            $table->id();
            
            // Relacionamos este gasto con una unidad específica
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            
            $table->string('month'); // Ej: Enero, Febrero
            $table->integer('year'); // Ej: 2026
            $table->decimal('amount', 10, 0); // Monto a pagar (sin decimales, ideal para pesos chilenos)
            $table->string('status')->default('Pendiente'); // Estados: Pendiente, Pagado, Vencido
            $table->date('due_date'); // Fecha tope para pagar
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('common_expenses');
    }
};