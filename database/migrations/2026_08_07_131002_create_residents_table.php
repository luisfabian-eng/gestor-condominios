<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentsTable extends Migration
{
    public function up()
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            // Vinculamos al residente con una unidad (departamento/casa) específica
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            
            $table->string('name');
            $table->string('rut')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('residents');
    }
}