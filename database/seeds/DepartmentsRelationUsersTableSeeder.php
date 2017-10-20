<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Department;
class DepartmentsRelationUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $cnt_empty=0;
        $cnt_done=0;
        $users =User::all();
        foreach ($users as $user) {
            $department_id =App\Department::where(['name'=>$user->department])->first()->id;
            if (!empty($department_id)) {
                $this->command->info( $user->name." Department_id:".$department_id);
                $user->department_id=$department_id;
                $user->save();
                $cnt_done++;
            } else {
                $this->command->info( $user->name." Department_id: EMPTY");
                $cnt_empty++;
            }

        }
        $this->command->info( " Department_id: EMPTY department:".$cnt_empty."\n"."Done:".$cnt_done);
    }
}
