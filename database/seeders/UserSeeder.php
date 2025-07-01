<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
        
    //     $user = new User();
    //     $user->name = "Default User";
    //     $user->phone_number = "0772123456";
    //     $user->email="admin@gmail.com";
    //     $user->user_type="Admin";
    //     $user->password = Hash::make('admin123@');
    //     try {
    //         $user->save();
    //     } catch (\Throwable $th) {}
        
    // }

    public function run(): void
    {
        $usersData = [
    
            ['name' => 'Edrine Kalungi', 'email' => 'edrinekalungi@sifcargo.com', 'user_type' => 'MD'],
            ['name' => 'Francis Lule', 'email' => 'francislule@sifcargo.com', 'user_type' => 'Manager'],
            ['name' => 'Admin Sifco', 'email' => 'sifco.admin@gmail.com', 'user_type' => 'Admin'],
        ];
        

        foreach ($usersData as $userData) {
            $user = new User();
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->user_type = $userData['user_type'];
            $user->password = Hash::make('Gru123@');

            try {
                $user->save();
            } catch (\Throwable $th) {
                // Handle exceptions if needed
            }
        }
    }
}
