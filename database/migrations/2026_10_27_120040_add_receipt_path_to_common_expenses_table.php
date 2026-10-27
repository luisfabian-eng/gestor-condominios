<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('common_expenses', function (Blueprint $table) {
            // Agrega la columna para guardar la ruta del boucher
            $table->string('receipt_path')->nullable()->after('status');
        });
    }
    public function down() {
        Schema::table('common_expenses', function (Blueprint $table) {
            $table->dropColumn('receipt_path');
        });
    }
};