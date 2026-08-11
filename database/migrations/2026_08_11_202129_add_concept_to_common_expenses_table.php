<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('common_expenses', function (Blueprint $table) {
            $table->string('concept')->nullable()->default('Gasto Común Ordinario')->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('common_expenses', function (Blueprint $table) {
            $table->dropColumn('concept');
        });
    }
};
