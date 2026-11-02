<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        // [hari lalu, tipe, akun, kategori, jumlah, keterangan]
        $rows = [
            [75, 'masuk', 2, 1, 12500000, 'Penjualan paket layanan bulan lalu'],
            [70, 'keluar', 2, 4, 6000000, 'Honor staf'],
            [66, 'keluar', 1, 6, 350000, 'Beli kertas dan tinta printer'],
            [60, 'masuk', 3, 2, 3200000, 'Servis jaringan klien'],
            [55, 'keluar', 3, 5, 1450000, 'Tagihan listrik dan internet'],
            [48, 'masuk', 2, 1, 9800000, 'Penjualan perangkat'],
            [44, 'keluar', 1, 8, 275000, 'Konsumsi rapat'],
            [40, 'keluar', 2, 4, 6000000, 'Honor staf'],
            [33, 'masuk', 3, 3, 500000, 'Bunga bank'],
            [28, 'keluar', 1, 7, 180000, 'Bensin kunjungan klien'],
            [21, 'masuk', 2, 2, 4100000, 'Jasa instalasi'],
            [18, 'keluar', 3, 5, 1520000, 'Tagihan listrik dan internet'],
            [12, 'keluar', 1, 6, 420000, 'Perlengkapan kantor'],
            [9,  'masuk', 2, 1, 11300000, 'Penjualan bulan berjalan'],
            [6,  'keluar', 2, 4, 6000000, 'Honor staf'],
            [3,  'keluar', 1, 8, 190000, 'Konsumsi tamu'],
            [1,  'masuk', 3, 2, 2750000, 'Jasa maintenance'],
        ];
        foreach ($rows as [$ago, $type, $acc, $cat, $amount, $desc]) {
            $this->db->table('transactions')->insert([
                'trx_date' => date('Y-m-d', strtotime("-{$ago} days")),
                'type' => $type, 'account_id' => $acc, 'category_id' => $cat,
                'amount' => $amount, 'description' => $desc, 'created_by' => 2,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }
}
