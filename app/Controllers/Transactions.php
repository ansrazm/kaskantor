<?php

namespace App\Controllers;

use App\Models\AccountModel;
use App\Models\CategoryModel;
use App\Models\TransactionModel;

class Transactions extends BaseController
{
    private TransactionModel $trx;

    public function __construct()
    {
        $this->trx = new TransactionModel();
    }

    private function rules(): array
    {
        return [
            'trx_date'    => 'required|valid_date[Y-m-d]',
            'type'        => 'required|in_list[masuk,keluar]',
            'account_id'  => 'required|is_natural_no_zero|is_not_unique[accounts.id]',
            'category_id' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
            'amount'      => 'required|numeric|greater_than[0]|less_than[1000000000000]',
            'description' => 'permit_empty|max_length[255]',
        ];
    }

    private function messages(): array
    {
        return [
            'trx_date'    => ['required' => 'Tanggal wajib diisi.', 'valid_date' => 'Format tanggal tidak valid.'],
            'type'        => ['required' => 'Pilih jenis transaksi.', 'in_list' => 'Jenis transaksi tidak valid.'],
            'account_id'  => ['required' => 'Pilih akun kas/bank.', 'is_not_unique' => 'Akun tidak ditemukan.'],
            'category_id' => ['required' => 'Pilih kategori.', 'is_not_unique' => 'Kategori tidak ditemukan.'],
            'amount'      => ['required' => 'Jumlah wajib diisi.', 'numeric' => 'Jumlah harus berupa angka.', 'greater_than' => 'Jumlah harus lebih dari 0.'],
            'description' => ['max_length' => 'Keterangan maksimal 255 karakter.'],
        ];
    }

    private function formData(array $extra = []): array
    {
        return $extra + [
            'accounts'   => (new AccountModel())->active(),
            'categories' => (new CategoryModel())->orderBy('type')->orderBy('name')->findAll(),
        ];
    }

    /** Kategori harus sesuai jenis transaksi (masuk/keluar). */
    private function categoryMatches(int $categoryId, string $type): bool
    {
        $cat = (new CategoryModel())->find($categoryId);

        return $cat && $cat['type'] === $type;
    }

    public function index()
    {
        $filters = [
            'from'        => $this->request->getGet('from'),
            'to'          => $this->request->getGet('to'),
            'type'        => $this->request->getGet('type'),
            'category_id' => $this->request->getGet('category_id'),
            'account_id'  => $this->request->getGet('account_id'),
        ];

        $rows = $this->trx->withRelations($filters)->paginate(10);

        return view('transactions/index', $this->formData([
            'rows'    => $rows,
            'pager'   => $this->trx->pager,
            'filters' => $filters,
        ]));
    }

    public function create()
    {
        return view('transactions/form', $this->formData([
            'row'    => ['trx_date' => date('Y-m-d'), 'type' => 'masuk'],
            'action' => site_url('transactions'),
            'title'  => 'Catat transaksi',
        ]));
    }

    public function store()
    {
        if (! $this->validate($this->rules(), $this->messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['trx_date', 'type', 'account_id', 'category_id', 'amount', 'description']);
        if (! $this->categoryMatches((int) $data['category_id'], $data['type'])) {
            return redirect()->back()->withInput()->with('errors', ['category_id' => 'Kategori tidak cocok dengan jenis transaksi.']);
        }
        $data['created_by'] = (int) session('user_id');

        $id = $this->trx->insert($data, true);
        audit('create', 'transactions', (int) $id, "{$data['type']} " . rupiah($data['amount']));

        return redirect()->to('/transactions')->with('success', 'Transaksi dicatat.');
    }

    public function edit(int $id)
    {
        $row = $this->trx->find($id);
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('transactions/form', $this->formData([
            'row'    => $row,
            'action' => site_url("transactions/{$id}/update"),
            'title'  => 'Ubah transaksi',
        ]));
    }

    public function update(int $id)
    {
        if (! $this->trx->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if (! $this->validate($this->rules(), $this->messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['trx_date', 'type', 'account_id', 'category_id', 'amount', 'description']);
        if (! $this->categoryMatches((int) $data['category_id'], $data['type'])) {
            return redirect()->back()->withInput()->with('errors', ['category_id' => 'Kategori tidak cocok dengan jenis transaksi.']);
        }

        $this->trx->update($id, $data);
        audit('update', 'transactions', $id, "{$data['type']} " . rupiah($data['amount']));

        return redirect()->to('/transactions')->with('success', 'Transaksi diperbarui.');
    }

    public function delete(int $id)
    {
        $row = $this->trx->find($id);
        if ($row) {
            $this->trx->delete($id);
            audit('delete', 'transactions', $id, "{$row['type']} " . rupiah($row['amount']));
        }

        return redirect()->to('/transactions')->with('success', 'Transaksi dihapus.');
    }
}
