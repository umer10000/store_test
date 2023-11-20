<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([

            'name' => 'john wick',
            'email' => 'admin@admin.com',
            'role_id' => 1,
            'password' => hash::make('testtest')
        ]);
    }
}
