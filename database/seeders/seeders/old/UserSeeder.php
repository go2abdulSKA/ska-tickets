<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * UserSeeder
 *
 * Creates sample users with different roles and department assignments
 *
 * Default Credentials:
 * - Super Admin: admin@ska.com / password
 * - Admin: manager@ska.com / password
 * - User: user@ska.com / password
 *
 * To run:
 * php artisan db:seed --class=UserSeeder
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Disable foreign key checks to safely truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear pivot table between users and departments
        DB::table('user_departments')->truncate();

        // Force delete all users including soft-deleted ones
        User::query()->forceDelete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Fetch roles from DB to assign to users
        $roles = [
            'super_admin' => Role::where('name','super_admin')->first(),
            'admin'       => Role::where('name','admin')->first(),
            'user'        => Role::where('name','user')->first(),
        ];

        // Fetch all departments from DB: key = department name, value = department id
        $departments = Department::pluck('id','department')->toArray();

        // Array of users from your CSV, with role flags and department mapping
$users = [
            ['full_name'=>'Abdul Rasheed','username'=>'arasheed','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Dominic Komakech','username'=>'srm','department'=>'SKA Risk Management','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'TOYOTA','username'=>'toyota','department'=>'Toyota','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Fuels Admin','username'=>'fadmin','department'=>'Fuels','is_admin'=>1,'is_super_admin'=>0],
            ['full_name'=>'bev user','username'=>'bev','department'=>'Vees Lounge','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Selvarajan Veerasamy','username'=>'joshua','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'CA Admin','username'=>'caadmin','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>0],
            ['full_name'=>'Alaisa Kaudha','username'=>'causer','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>0],
            ['full_name'=>'SRM Admin','username'=>'srmadmin','department'=>'SKA Risk Management','is_admin'=>1,'is_super_admin'=>0],
            ['full_name'=>'VL Admin','username'=>'vladmin','department'=>'Vees Lounge','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Robinson Omondi Osew','username'=>'vluser','department'=>'Vees Lounge','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Construction Admin','username'=>'conadmin','department'=>'Construction','is_admin'=>1,'is_super_admin'=>0],
            ['full_name'=>'Edwin Kuria','username'=>'conuser','department'=>'Construction','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Sanjeev Kumar','username'=>'fuser','department'=>'Fuels','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Ravindu Theekshana','username'=>'RaviSKA','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Davut Turan','username'=>'Davut','department'=>'Logistics','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'MOG Admin','username'=>'madmin','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'DFAC','username'=>'dfacuser','department'=>'DFAC','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Mustaf Ali Roble','username'=>'Mustaf','department'=>'Logistics','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Sumaya','username'=>'pxuser','department'=>'PX','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Ram Mawliya','username'=>'ram','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Gururaj Pandith','username'=>'gururaj','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Dipankar Pal','username'=>'dipankar','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Maricol Bacod','username'=>'maricol','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Mohamed Yusuf','username'=>'bdaworkshop','department'=>'Workshop Baidoa','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Mohamed Yusuf','username'=>'bdadfac','department'=>'DFAC Baidoa','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Mohamed Yusuf','username'=>'bdaca','department'=>'Camp & Accom - Baidoa','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Mohamed Yusuf','username'=>'baidoaadmin','department'=>'WFP','is_admin'=>1,'is_super_admin'=>0],
            ['full_name'=>'SKA IT','username'=>'skait','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Jo Anne','username'=>'joanne','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Vijeesh Pothiyedath','username'=>'lsauser','department'=>'Life Support Services','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Paul Cailleau','username'=>'lsaadmin','department'=>'Life Support Services','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Maninder Singh','username'=>'Maninder Singh','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'Rogel Hernandez','username'=>'rogel','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>1],
            ['full_name'=>'PX Admin','username'=>'pxadmin','department'=>'PX','is_admin'=>0,'is_super_admin'=>0],
            ['full_name'=>'Super Administrator','username'=>'sadmin','department'=>'Camp & Accomodation','is_admin'=>0,'is_super_admin'=>1],
            ['full_name'=>'Administrator','username'=>'admin','department'=>'Camp & Accomodation','is_admin'=>1,'is_super_admin'=>0],
            ['full_name'=>'Camp User','username'=>'cuser','department'=>'Camp & Accomodation','is_admin'=>0,'is_super_admin'=>0],
        ];

        // Loop through each user and create them in DB
        foreach($users as $u){
            // Determine user role based on flags
            $role = $roles['user'];           // default role
            if($u['is_super_admin']) $role = $roles['super_admin'];
            elseif($u['is_admin']) $role = $roles['admin'];

            // Create the user
            $user = User::create([
                'name'              => $u['full_name'],          // username column
                'full_name'         => $u['full_name'],         // full name
                'email'             => $u['username'].'@ska.com', // email generated from username
                'password'          => Hash::make('password'),  // default password
                'role_id'           => $role->id,               // assigned role
                'is_active'         => true,                    // active status
                'email_verified_at' => now(),                   // mark email as verified
            ]);

            // Attach user to department if it exists
            if(isset($departments[$u['department']])){
                $user->departments()->attach($departments[$u['department']]);
            }

            // Output to console for tracking
            $this->command->info("✓ Created user {$user->email}");
        }

        // Final message after all users are created
        $this->command->info('✓ Created ' . (User::count()) . ' users total');
        $this->command->info('');
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('Super Admin: arasheed@ska.com / password');
        $this->command->info('Admin: manager@ska.com / password');
        $this->command->info('User: user@ska.com / password');
        $this->command->info('========================');

    }    

}
