<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \App\Permission;
class DepartmentstrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Set Permission for departments');
        $this->truncateLaratrustTables();

        $config = config('departmentstrust_seeder.role_structure');
        $userPermission = config('departmentstrust_seeder.permission_structure');
        $mapPermission = collect(config('departmentstrust_seeder.permissions_map'));

        // Allow a user to...
//        $createDepartment = new Permission();
//        $createDepartment->name         = 'create-department';
//        $createDepartment->display_name = 'create department'; // optional
//        $createDepartment->description  = 'create department'; // optional
//        $createDepartment->save();

//        $editDepartment = new Permission();
//        $editDepartment->name         = 'edit-department';
//        $editDepartment->display_name = 'Edit department'; // optional
//        $editDepartment->description  = 'edit department'; // optional
//        $editDepartment->save();
//
//        $deleteDepartment = new Permission();
//        $deleteDepartment->name         = 'delete-department';
//        $deleteDepartment->display_name = 'Delete department'; // optional
//        $deleteDepartment->description  = 'delete department'; // optional
//        $deleteDepartment->save();
        $editDepartment = App\Permission::where(['name'=>'create-department'])->first()->id;
        $deleteDepartment = App\Permission::where(['name'=>'delete-department'])->first()->id;

        $users = \App\User::all();
        foreach ($users as $user) {
            //---Whom and what to assigne---
            if ($user->hasRole(['director', 'admin'])) {
                $this->command->info("Attach permission ... ".$user->email);
                //if (($user->department_id==5)) {//--Разработка ПО
                    //$user->attachRole($role);
                    $user->attachPermission($editDepartment);
                    $user->attachPermission($deleteDepartment);
                //}
            }

        }




    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
       //
        //\App\User::truncate();
       //\App\Role::truncate();
        //\App\Permission::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
