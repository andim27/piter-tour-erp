<?php

use Illuminate\Database\Seeder;

class HeadToUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users_id_arr=[5,6,10,16,18,27,29];
        DB::table('users')
            ->whereIn('id', $users_id_arr)
            ->update(['head' => 1]);
    }
}
