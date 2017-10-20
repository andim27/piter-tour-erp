<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OltatrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Fill User, Role and Permission tables');
        $this->truncateLaratrustTables();

        $config = config('oltatrust_seeder.role_structure');
        $userPermission = config('oltatrust_seeder.permission_structure');
        $mapPermission = collect(config('oltatrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \App\Role::create([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);
            $permissions = [];

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = \App\Permission::firstOrCreate([
                        'name' => $permissionValue . '-' . $module,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ])->id;

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            $this->command->info("Creating '{$key}' user");

            $users = \App\User::all();
            foreach ($users as $user) {
                //---Whom and what to assigne---
                if ($role->name=='admin') {
                    if (($user->department_id==5)) {//--Разработка ПО
                        $user->attachRole($role);
                    }
                }
                if ($role->name=='sales') {
                    if (($user->department_id==10)) {//--Sales
                        $user->attachRole($role);
                    }
                }
                if ($role->name=='marketing') {
                    if (($user->department_id==1)) {//--Marketing
                        $user->attachRole($role);
                    }
                }
                if ($role->name=='reservation') {
                    if (($user->department_id==6)) {//--Reservation
                        $user->attachRole($role);
                    }
                }

                //$user->attachRole($role);
            }

            // Create default user for each role
//            $user = \App\User::create([
//                'name' => ucwords(str_replace('_', ' ', $key)),
//                'email' => $key.'@app.com',
//                'password' => bcrypt('password')
//            ]);


        }

        // Creating user with permissions
        if (!empty($userPermission)) {

            foreach ($userPermission as $key => $modules) {

                foreach ($modules as $module => $value) {

                    // Create default user for each permission set
//                    $user = \App\User::create([
//                        'name' => ucwords(str_replace('_', ' ', $key)),
//                        'email' => $key.'@app.com',
//                        'password' => bcrypt('password'),
//                        'remember_token' => str_random(10),
//                    ]);
                    $user=\App\User::where(['id'=>36])->first();//--it's me
                    $permissions = [];

                    foreach (explode(',', $value) as $p => $perm) {

                        $permissionValue = $mapPermission->get($perm);

                        $permissions[] = \App\Permission::firstOrCreate([
                            'name' => $permissionValue . '-' . $module,
                            'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        ])->id;

                        $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                    }
                }

                // Attach all permissions to the user
                $user->permissions()->sync($permissions);
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
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();
        //\App\User::truncate();
        \App\Role::truncate();
        \App\Permission::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
