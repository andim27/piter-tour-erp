<?php

use Illuminate\Database\Seeder;
use App\User;
class BitrixIdUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'users';
        //$this->filename = base_path().'/database/seeds/csvs/users_1.csv';
        $this->filename = app()->basePath().'/database/seeds/csvs/bitrix_users_id.csv';
        $this->filename_no = app()->basePath().'/database/seeds/csvs/bitrix_users_id_no.csv';

    }
    public function run()
    {
        //

        $f=fopen($this->filename,"r");
        $f_no=fopen($this->filename_no,"w+");
        $line=fgetcsv($f);
        while(! feof($f))
        {
            $line=fgetcsv($f);
            $this->command->info('Read bitrix id...'.$line[0]);
            $bitrix_user_id=$line[0];
            $bitrix_user_email=$line[1];
            if ($bitrix_user_id) {
                $olta_user =User::where(['email'=>$bitrix_user_email])->first();
                if ( $olta_user) {
                    $olta_user->bitrix_id=$bitrix_user_id;
                    $olta_user->save();
                } else {
                    $this->command->info('NO olta user with email:'. $bitrix_user_email);
                    fwrite($f_no,$bitrix_user_id.','.$bitrix_user_email."\n\n");
                }

                //$this->command->info('Saved bitrix id...'.$line[0]);
            }
        }

        fclose($f);
        fclose($f_no);


    }
}
