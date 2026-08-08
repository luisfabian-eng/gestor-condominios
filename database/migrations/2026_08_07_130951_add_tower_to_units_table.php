<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTowerToUnitsTable extends Migration
{
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            // Agregamos la columna 'tower' justo después de 'number'
            $table->string('tower')->nullable()->after('number');
        });
    }

    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('tower');
        });
    }
}