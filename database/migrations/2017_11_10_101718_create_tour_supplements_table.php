<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourSupplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_supplements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tour_id');
            $table->string('service_type');
            $table->date('service_date')->nullable();
            $table->time('service_time_from')->nullable();
            $table->time('service_time_to')->nullable();
            $table->unsignedInteger('day_index')->default(1);
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->decimal('service_price', 15, 2)->nullable();
            $table->unsignedInteger('service_hours')->nullable();
            $table->decimal('service_sum', 15, 2)->nullable();
            $table->string('currency_type_str')->nullable();
            $table->timestamps();

            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_supplements');
    }
}
