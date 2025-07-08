<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Inisialisasi Faker untuk data palsu bahasa Indonesia
        $faker = \Faker\Factory::create('id_ID');

        // Looping untuk membuat 10 data user palsu
        for ($i = 0; $i < 10; $i++) {
            $data = [
                'username' => $faker->userName,
                'email' => $faker->email,
                'password' => password_hash('1234567', PASSWORD_DEFAULT),
                'role' => $faker->randomElement(['admin', 'guest']),
                // Menambahkan kolom foto_profil dengan gambar default
                'foto_profil' => 'default.jpg', 
                'created_at' => date("Y-m-d H:i:s"),
            ];
            
            // Memasukkan data ke dalam tabel 'user'
            $this->db->table('user')->insert($data);
        }
    }
}
