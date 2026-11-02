<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        foreach ([
            ['1-100', 'Kas Kecil',          'kas'],
            ['1-110', 'Kas Besar',          'kas'],
            ['1-200', 'Bank BRI Operasional', 'bank'],
        ] as [$code, $name, $type]) {
            $this->db->table('accounts')->insert([
                'code' => $code, 'name' => $name, 'type' => $type, 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        foreach ([
            ['Penjualan', 'masuk'], ['Jasa & Servis', 'masuk'], ['Pendapatan Lain', 'masuk'],
            ['Gaji & Honor', 'keluar'], ['Listrik, Air & Internet', 'keluar'],
            ['ATK & Perlengkapan', 'keluar'], ['Transportasi', 'keluar'], ['Konsumsi', 'keluar'],
        ] as [$name, $type]) {
            $this->db->table('categories')->insert([
                'name' => $name, 'type' => $type, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
}
