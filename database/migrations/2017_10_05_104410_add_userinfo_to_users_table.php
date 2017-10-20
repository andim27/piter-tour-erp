<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserinfoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // add fields from Bitrix Table-List
            $table->string('userfio')->nullable();
            $table->string('tel')->nullable();
            $table->string('tel_work')->nullable();
            $table->string('skype')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('info')->nullable();
            $table->tinyInteger('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('userfio');
            $table->dropColumn('tel');
            $table->dropColumn('tel_work');
            $table->dropColumn('skype');
            $table->dropColumn('position');
            $table->dropColumn('department');
            $table->dropColumn('info');
            $table->dropColumn('active');
        });
    }
}
