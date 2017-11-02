<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tour_id');
            $table->date('service_date')->nullable();
            $table->time('service_time_from')->nullable();
            $table->time('service_time_to')->nullable();
            $table->unsignedInteger('day_index')->default(1);
            $table->unsignedInteger('day_service_index')->default(1);
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->string('service_name')->nullable();
            $table->boolean('is_supplement')->default(false);//--needed supplement(guid||transpost|etc)
            $table->boolean('is_transpost')->default(false);//--need transport
            $table->decimal('service_price', 15, 2)->nullable();
            $table->unsignedInteger('service_hours')->nullable();
            $table->decimal('service_sum', 15, 2)->nullable();
            $table->unsignedInteger('service_state')->default(1);//1-ask 2-confirm 3-deny 4-booking
            $table->date('service_state_date')->nullable();
            $table->date('service_pay_date')->nullable();

            $table->json('options')->nullable();
            $table->string('comment')->nullable();
            $table->string('qty')->nullable();

            $table->timestamps();

            $table->foreign('tour_id')->references('id')->on('tours');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_programs');
    }
}
