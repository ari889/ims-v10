<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => 1,
            'name' => 'Arijit Banarjee',
            'email' => 'arijitbanarjee889@gmail.com',
            'mobile_no' => '01733163337',
            'gender' => 1,
            'password' => 'asdfg1234'
        ]);
    }
}
