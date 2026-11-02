<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table         = 'transactions';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['trx_date', 'type', 'account_id', 'category_id', 'amount', 'description', 'created_by'];
    protected $useTimestamps = true;

    /** Query dasar dengan join akun, kategori, dan pembuat. */
    public function withRelations(array $f = []): static
    {
        $this->select('transactions.*, accounts.name AS account_name, categories.name AS category_name, users.name AS user_name')
            ->join('accounts', 'accounts.id = transactions.account_id')
            ->join('categories', 'categories.id = transactions.category_id')
            ->join('users', 'users.id = transactions.created_by');

        if (! empty($f['from'])) {
            $this->where('transactions.trx_date >=', $f['from']);
        }
        if (! empty($f['to'])) {
            $this->where('transactions.trx_date <=', $f['to']);
        }
        if (! empty($f['type']) && in_array($f['type'], ['masuk', 'keluar'], true)) {
            $this->where('transactions.type', $f['type']);
        }
        if (! empty($f['category_id'])) {
            $this->where('transactions.category_id', (int) $f['category_id']);
        }
        if (! empty($f['account_id'])) {
            $this->where('transactions.account_id', (int) $f['account_id']);
        }

        return $this->orderBy('transactions.trx_date', 'DESC')->orderBy('transactions.id', 'DESC');
    }

    /** Total masuk & keluar pada rentang tanggal. */
    public function totals(?string $from = null, ?string $to = null): array
    {
        $b = $this->builder()->select("COALESCE(SUM(CASE WHEN type='masuk' THEN amount END),0) AS masuk, COALESCE(SUM(CASE WHEN type='keluar' THEN amount END),0) AS keluar", false);
        if ($from) {
            $b->where('trx_date >=', $from);
        }
        if ($to) {
            $b->where('trx_date <=', $to);
        }
        $r = $b->get()->getRowArray();

        return ['masuk' => (float) $r['masuk'], 'keluar' => (float) $r['keluar']];
    }

    /** Saldo per akun (semua waktu). */
    public function balancePerAccount(): array
    {
        return $this->db->table('accounts a')
            ->select("a.id, a.code, a.name, COALESCE(SUM(CASE WHEN t.type='masuk' THEN t.amount WHEN t.type='keluar' THEN -t.amount END),0) AS saldo", false)
            ->join('transactions t', 't.account_id = a.id', 'left')
            ->where('a.is_active', 1)
            ->groupBy('a.id, a.code, a.name')
            ->orderBy('a.code')
            ->get()->getResultArray();
    }

    /** Total per kategori pada rentang tanggal. */
    public function perCategory(string $from, string $to): array
    {
        return $this->db->table('transactions t')
            ->select('c.name, t.type, SUM(t.amount) AS total', false)
            ->join('categories c', 'c.id = t.category_id')
            ->where('t.trx_date >=', $from)->where('t.trx_date <=', $to)
            ->groupBy('c.name, t.type')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();
    }
}
