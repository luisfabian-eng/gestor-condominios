<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRejectionNoteToCommonExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('common_expenses', function (Blueprint $table) {
            $table->text('rejection_note')->nullable()->after('receipt_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('common_expenses', function (Blueprint $table) {
            $table->dropColumn('rejection_note');
        });
    }
}