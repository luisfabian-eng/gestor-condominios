<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAndResidentIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregamos el rol (por defecto todos los actuales serán admin)
            $table->string('role')->default('admin')->after('email');
            
            // Conectamos la cuenta de usuario con el perfil del residente (si aplica)
            $table->foreignId('resident_id')->nullable()->after('role')->constrained('residents')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['resident_id']);
            $table->dropColumn('resident_id');
            $table->dropColumn('role');
        });
    }
}