<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class Reports extends BaseController
{
    private function period(): array
    {
        $m = (int) ($this->request->getGet('month') ?: date('n'));
        $y = (int) ($this->request->getGet('year') ?: date('Y'));
        $m = min(12, max(1, $m));
        $y = min(2100, max(2000, $y));
        $from = sprintf('%04d-%02d-01', $y, $m);

        return [$m, $y, $from, date('Y-m-t', strtotime($from))];
    }

    public function monthly()
    {
        [$m, $y, $from, $to] = $this->period();
        $trx = new TransactionModel();

        $opening = $trx->totals(null, date('Y-m-d', strtotime($from . ' -1 day')));
        $period  = $trx->totals($from, $to);
        $openingBalance = $opening['masuk'] - $opening['keluar'];

        return view('reports/monthly', [
            'month' => $m, 'year' => $y,
            'opening' => $openingBalance,
            'period'  => $period,
            'closing' => $openingBalance + $period['masuk'] - $period['keluar'],
            'cats'    => $trx->perCategory($from, $to),
            'rows'    => $trx->withRelations(['from' => $from, 'to' => $to])->orderBy('transactions.trx_date', 'ASC')->findAll(),
        ]);
    }

    public function csv()
    {
        [$m, $y, $from, $to] = $this->period();
        $rows = (new TransactionModel())->withRelations(['from' => $from, 'to' => $to])
            ->orderBy('transactions.trx_date', 'ASC')->findAll();

        $out = fopen('php://temp', 'r+');
        fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
        fputcsv($out, ['Tanggal', 'Jenis', 'Akun', 'Kategori', 'Keterangan', 'Jumlah', 'Dicatat oleh']);
        foreach ($rows as $r) {
            fputcsv($out, [$r['trx_date'], $r['type'], $r['account_name'], $r['category_name'], $r['description'], $r['amount'], $r['user_name']]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', sprintf('attachment; filename="laporan-kas-%04d-%02d.csv"', $y, $m))
            ->setBody($csv);
    }
}
