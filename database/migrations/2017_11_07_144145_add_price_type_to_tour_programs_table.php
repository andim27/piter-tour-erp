<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceTypeToTourProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour_programs', function (Blueprint $table) {
            //
            $table->string('price_type')->nullable();
            $table->string('currency_type_str')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour_programs', function (Blueprint $table) {
            //
            $table->dropColumn('price_type');
            $table->dropColumn('currency_type_str');
        });
    }
}
