<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBitrixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitrixes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_name');
            $table->string('client_id');
            $table->string('client_secret');
            $table->string('redirect_uri');
            $table->string('member_id');
            $table->string('scope');
            $table->string('code');
            $table->string('access_token');
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
        Schema::dropIfExists('bitrixes');
    }
}
