<?php

use App\Models\AuditModel;

if (! function_exists('rupiah')) {
    function rupiah($n): string
    {
        $n = (float) $n;

        return ($n < 0 ? '-' : '') . 'Rp ' . number_format(abs($n), 0, ',', '.');
    }
}

if (! function_exists('tgl')) {
    function tgl(?string $d): string
    {
        if (! $d) {
            return '-';
        }
        $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $t     = strtotime($d);

        return date('j', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
    }
}

if (! function_exists('nama_bulan')) {
    function nama_bulan(int $m): string
    {
        return ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$m] ?? '';
    }
}

if (! function_exists('audit')) {
    /** Catat aksi penting ke tabel audit_logs. */
    function audit(string $action, string $table, ?int $recordId = null, ?string $detail = null): void
    {
        (new AuditModel())->insert([
            'user_id'    => session('user_id'),
            'action'     => $action,
            'table_name' => $table,
            'record_id'  => $recordId,
            'detail'     => $detail ? mb_substr($detail, 0, 255) : null,
            'ip_address' => service('request')->getIPAddress(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

if (! function_exists('can')) {
    /** Cek apakah role user saat ini ada di daftar. */
    function can(string ...$roles): bool
    {
        return in_array(session('role'), $roles, true);
    }
}
