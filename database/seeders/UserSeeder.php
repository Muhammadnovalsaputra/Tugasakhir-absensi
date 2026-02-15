<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Bapak Pimpinan',
            'email'    => 'pimpinan@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'Pimpinan',
        ]);

        $roles = ['Admin', 'Finance', 'KetuaTeknisi', 'Teknisi', 'Sekretaris', 'Marketing'];
        foreach ($roles as $role) {
            User::create([
                'name'     => "User $role",
                'email'    => strtolower($role) . "@gmail.com",
                'password' => Hash::make('password'),
                'role'     => $role,
            ]);
        }
    }
}
