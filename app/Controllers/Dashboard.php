<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $trx = new TransactionModel();

        $from = date('Y-m-01');
        $to   = date('Y-m-t');

        // Tren 6 bulan terakhir
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $first   = date('Y-m-01', strtotime("-{$i} months", strtotime($from)));
            $last    = date('Y-m-t', strtotime($first));
            $t       = $trx->totals($first, $last);
            $trend[] = ['label' => nama_bulan((int) date('n', strtotime($first))), 'masuk' => $t['masuk'], 'keluar' => $t['keluar']];
        }

        $balances = $trx->balancePerAccount();

        return view('dashboard/index', [
            'month'    => $trx->totals($from, $to),
            'balances' => $balances,
            'total'    => array_sum(array_column($balances, 'saldo')),
            'trend'    => $trend,
            'recent'   => $trx->withRelations()->limit(6)->find(),
            'monthLabel' => nama_bulan((int) date('n')) . ' ' . date('Y'),
        ]);
    }
}
