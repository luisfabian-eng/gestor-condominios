<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('condominiums', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ejemplo: Edificio Los Alpes
            $table->string('rut')->unique()->nullable(); // RUT de la comunidad para temas legales
            $table->string('address'); // Dirección física
            $table->string('city')->nullable(); // Ciudad
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('condominiums');
    }
};