<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'phone' => '998995554466',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'super@admin.com',
            'login' => 'superadmin',
            'password' => Hash::make('01234567'),
            'gender' => GenderEnum::MALE,
            'status' => UserStatusEnum::ACTIVE,
        ]);
    }
}
