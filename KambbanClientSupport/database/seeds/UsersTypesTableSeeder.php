<?php

use Illuminate\Database\Seeder;
Use Illuminate\Support\Facades\DB;

class UsersTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_types')->insert([
            'name' => 'admin',
            'created_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('users_types')->insert([
            'name' => 'internal_user',
            'created_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('users_types')->insert([
            'name' => 'external_user',
            'created_at' => date("Y-m-d H:i:s")
        ]);

    }
}
