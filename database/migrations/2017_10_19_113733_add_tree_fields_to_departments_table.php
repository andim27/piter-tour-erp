<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTreeFieldsToDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            //
            //$table->integer('parent')->unsigned();
            $table->integer('head_user_id')->foreign('head_user_id')->references('id')->on('users');
            //$table->integer('head_user_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            //
            $table->dropColumn('parent');
            $table->dropColumn('head_user_id');
            $table->dropColumn('head_user_name');
        });
    }
}
