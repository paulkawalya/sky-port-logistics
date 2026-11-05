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
            ['name' => 'NAMAKULA ANN ASIRIKE', 'email' => 'accountant@example.com', 'user_type' => 'Accountant'],
            ['name' => 'Edrine Kalungi', 'email' => 'edrinekalungi@sifcargo.com', 'user_type' => 'MD'],
            ['name' => 'Francis Lule', 'email' => 'francislule@sifcargo.com', 'user_type' => 'Manager'],
            ['name' => 'Senabulya Steven', 'email' => 'senabulyasteven@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Annitah Akampurira', 'email' => 'anita.akampurira@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Atuhaire Fortunate', 'email' => 'atuhairefortunate@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Wagalo Keneth', 'email' => 'wagalokeneth@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Rehemah Nabulime', 'email' => 'rehemahnabulime@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Ann Namakula', 'email' => 'accounts@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Musoke Ezerah', 'email' => 'musokeezerah@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Wobugabe Joyce', 'email' => 'wobugabejoyce@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Katungulu Twaha', 'email' => 'katungulutwaha66@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Kasozi Usama', 'email' => 'kasoziousama3@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Saudah Twinomugisha', 'email' => 'saudahtwinomugisha@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Janet Murungi', 'email' => 'janetmurungi@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Ismael Mpiima', 'email' => 'iblick311@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Sumayah Babirye', 'email' => 'sumayahbabirye@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Andrew Mukisa', 'email' => 'andrewlabongo1@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Seruwagi Ahmed', 'email' => 'seruwagiahmed640@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Joshua Ggayi', 'email' => 'joshuaggayi@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Mulwanyi Cryspus', 'email' => 'dokiekiwi@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Ivan Ahimbisibwe', 'email' => 'ahimbisibweivanovic@gmail.com', 'user_type' => 'Accountant'],
            ['name' => 'Johnson Byamanywoha', 'email' => 'byamanywohaj@yahoo.com', 'user_type' => 'Accountant'],
            ['name' => 'Jackie Kayaga', 'email' => 'jackie.kayaga@sifcargo.com', 'user_type' => 'Accountant'],
            ['name' => 'Admin Sifco', 'email' => 'sifco.admin@gmail.com', 'user_type' => 'Admin'],
        ];
        

        foreach ($usersData as $userData) {
            $user = User::where('email', $userData['email'])->first() ?? new User();
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->user_type = $userData['user_type'];
            $user->password = Hash::make('user123@');

            try {
                $user->save();
            } catch (\Throwable $th) {
                // Handle exceptions if needed
            }
        }
    }
}
