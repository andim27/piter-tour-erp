<?php

use Illuminate\Database\Seeder;
use App\Bitrix;
class BitrixesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $bitrix=new Bitrix();
        $bitrix->app_name=config('bitrix.app.app_name');
        $bitrix->client_id=config('bitrix.app.client_id');
        $bitrix->client_secret=config('bitrix.app.client_secret');
        $bitrix->redirect_uri=config('bitrix.app.redirect_uri');
        $bitrix->member_id=config('bitrix.app.member_id');
        $bitrix->scope=config('bitrix.app.scope');
        $bitrix->save();
    }
}
