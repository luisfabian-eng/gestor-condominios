<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            // Esta línea crea la relación con la tabla condominiums
           $table->foreignId('condominium_id')->constrained('condominiums')->onDelete('cascade');
            
            $table->string('number'); // Ej: Depto 402, Casa 5, Estacionamiento 12
            $table->string('type')->default('Departamento'); // Departamento, Casa, Bodega, etc.
            $table->decimal('prorata', 5, 2)->default(0); // Porcentaje de prorrateo (Ej: 1.25)
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};