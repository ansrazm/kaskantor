<?php

namespace App\Controllers\Api;

use App\Models\CategoryModel;
use App\Models\TransactionModel;
use CodeIgniter\RESTful\ResourceController;

/**
 * REST API transaksi.
 * GET    /api/transactions          daftar (?page=, ?type=, ?from=, ?to=)
 * GET    /api/transactions/{id}     detail
 * POST   /api/transactions          buat   (admin, kasir)
 * PUT    /api/transactions/{id}     ubah   (admin, kasir)
 * DELETE /api/transactions/{id}     hapus  (admin, kasir)
 */
class TransactionApi extends ResourceController
{
    protected $modelName = TransactionModel::class;
    protected $format    = 'json';
    protected $helpers   = ['kas'];

    private function rules(): array
    {
        return [
            'trx_date'    => 'required|valid_date[Y-m-d]',
            'type'        => 'required|in_list[masuk,keluar]',
            'account_id'  => 'required|is_natural_no_zero|is_not_unique[accounts.id]',
            'category_id' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
            'amount'      => 'required|numeric|greater_than[0]',
            'description' => 'permit_empty|max_length[255]',
        ];
    }

    private function writeDenied()
    {
        return can('admin', 'kasir') ? null : $this->failForbidden('Akses ditolak');
    }

    public function index()
    {
        $filters = $this->request->getGet(['from', 'to', 'type', 'category_id', 'account_id']);
        $rows    = $this->model->withRelations($filters)->paginate(10);

        return $this->respond([
            'data' => $rows,
            'meta' => ['page' => $this->model->pager->getCurrentPage(), 'pages' => $this->model->pager->getPageCount(), 'total' => $this->model->pager->getTotal()],
        ]);
    }

    public function show($id = null)
    {
        $row = $this->model->withRelations()->where('transactions.id', (int) $id)->first();

        return $row ? $this->respond(['data' => $row]) : $this->failNotFound('Transaksi tidak ditemukan');
    }

    public function create()
    {
        if ($deny = $this->writeDenied()) {
            return $deny;
        }
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        if (! $this->validateData($data, $this->rules())) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $cat = (new CategoryModel())->find((int) $data['category_id']);
        if (! $cat || $cat['type'] !== $data['type']) {
            return $this->failValidationErrors(['category_id' => 'Kategori tidak cocok dengan jenis transaksi.']);
        }
        $insert = array_intersect_key($data, array_flip(['trx_date', 'type', 'account_id', 'category_id', 'amount', 'description']));
        $insert['created_by'] = (int) session('user_id');
        $id = $this->model->insert($insert, true);
        audit('create', 'transactions', (int) $id, 'via API');

        return $this->respondCreated(['data' => $this->model->find($id)]);
    }

    public function update($id = null)
    {
        if ($deny = $this->writeDenied()) {
            return $deny;
        }
        if (! $this->model->find((int) $id)) {
            return $this->failNotFound('Transaksi tidak ditemukan');
        }
        $data = $this->request->getJSON(true) ?? [];
        if (! $this->validateData($data, $this->rules())) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $cat = (new CategoryModel())->find((int) $data['category_id']);
        if (! $cat || $cat['type'] !== $data['type']) {
            return $this->failValidationErrors(['category_id' => 'Kategori tidak cocok dengan jenis transaksi.']);
        }
        $this->model->update((int) $id, array_intersect_key($data, array_flip(['trx_date', 'type', 'account_id', 'category_id', 'amount', 'description'])));
        audit('update', 'transactions', (int) $id, 'via API');

        return $this->respond(['data' => $this->model->find((int) $id)]);
    }

    public function delete($id = null)
    {
        if ($deny = $this->writeDenied()) {
            return $deny;
        }
        if (! $this->model->find((int) $id)) {
            return $this->failNotFound('Transaksi tidak ditemukan');
        }
        $this->model->delete((int) $id);
        audit('delete', 'transactions', (int) $id, 'via API');

        return $this->respondDeleted(['id' => (int) $id]);
    }
}
