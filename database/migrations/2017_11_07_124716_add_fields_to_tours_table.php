<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
        //
            $table->string('dossier');

        });

        Schema::table('tour_programs', function (Blueprint $table) {
            //

            $table->string('service_type');
            $table->decimal('q_price', 15, 2)->nullable();//quota ->from quotation
            $table->unsignedInteger('q_hours')->nullable();           //quota ->from quotation
            $table->decimal('q_sum', 15, 2)->nullable();  //quota ->from quotation
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
            $table->dropColumn('dossier');
            $table->dropColumn('service_type');
            $table->dropColumn('q_price');
            $table->dropColumn('q_hours');
            $table->dropColumn('q_sum');
        });
        Schema::table('tours', function (Blueprint $table) {
            //
            $table->dropColumn('dossier');
        });
    }
}
