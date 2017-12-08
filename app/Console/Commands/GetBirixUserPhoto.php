<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
class GetBirixUserPhoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitrix:get-users-photo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GetBitrixUserPhoto';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $users=User::all();
        $users->map(function($item,$key) {
            $res=0;
            $extension = \File::extension($item->photo);
            $part_name_arr=explode('/',$item->photo);
            $file_name=$part_name_arr[count( $part_name_arr)-1];
            //$this->info( $part_name_arr[count( $part_name_arr)-1]);
            if (!empty($file_name)) {
                $path = storage_path();
                if (\File::exists($path.'/bitrix/users/avatar/'.$file_name))
                {
                    $file_name=$item->id+'_'.$file_name;
                }
                try {
                    \Storage::put('bitrix/users/avatar/'.$file_name, file_get_contents($item->photo));
                    $res=\DB::update('update users set avatar = ? where id = ?', [$file_name,$item->id]);
                } catch (\Exception $e) {
                    $this->info( 'ERROR:'.$item->photo);
                }


                $this->info( $item->photo.' res='.$res);

                return;
            }

        });
    }
}
