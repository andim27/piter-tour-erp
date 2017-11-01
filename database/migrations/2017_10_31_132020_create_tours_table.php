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
            $table->string('tour_name')->nullable();
            $table->string('client_name')->nullable();
            $table->string('people')->nullable();
            $table->string('cities_str')->nullable();
            $table->unsignedInteger('nights')->nullable();
            $table->string('sales_user_name')->nullable();
            $table->string('booking_user_name')->nullable();
            $table->string('comment')->nullable();
            $table->string('currency_type_str')->nullable();
            $table->float('currency_kurs',10,2)->nullable();
            $table->integer('price')->nullable();
            $table->date('work_date')->nullable(); //--input by hand from UI
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
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
