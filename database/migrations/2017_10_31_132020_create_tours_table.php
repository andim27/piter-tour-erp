<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ext_q_id');//---quotation id from rfq (external id)---
            $table->string('tour_name');
            $table->string('client_name');
            $table->string('people');
            $table->string('cities_str');
            $table->unsignedInteger('nights');
            $table->string('sales_user_name');
            $table->string('booking_user_name');
            $table->string('comment');
            $table->string('currency_type_str');
            $table->float('currency_kurs',10,2);
            $table->integer('price');
            $table->date('work_date'); //--input by hand from UI
            $table->date('date_from');
            $table->date('date_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tours');
    }
}
