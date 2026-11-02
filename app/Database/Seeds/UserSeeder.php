<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now   = date('Y-m-d H:i:s');
        $users = [
            ['name' => 'Administrator', 'email' => 'admin@kaskantor.test',  'role' => 'admin'],
            ['name' => 'Kasir Kantor',  'email' => 'kasir@kaskantor.test',  'role' => 'kasir'],
            ['name' => 'Pimpinan',      'email' => 'viewer@kaskantor.test', 'role' => 'viewer'],
        ];
        foreach ($users as $u) {
            $this->db->table('users')->insert($u + [
                'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}
