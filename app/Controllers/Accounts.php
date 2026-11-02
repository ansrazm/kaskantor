<?php

namespace App\Controllers;

use App\Models\AccountModel;

class Accounts extends BaseController
{
    private AccountModel $accounts;

    public function __construct()
    {
        $this->accounts = new AccountModel();
    }

    private function rules(?int $id = null): array
    {
        return [
            'code' => 'required|max_length[20]|is_unique[accounts.code,id,' . ($id ?? 0) . ']',
            'name' => 'required|max_length[100]',
            'type' => 'required|in_list[kas,bank]',
        ];
    }

    public function index()
    {
        return view('accounts/index', ['rows' => $this->accounts->orderBy('code')->findAll()]);
    }

    public function create()
    {
        return view('accounts/form', ['row' => ['is_active' => 1], 'action' => site_url('accounts'), 'title' => 'Tambah akun']);
    }

    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['code', 'name', 'type']);
        $data['is_active'] = $this->request->getPost('is_active') ? 1 : 0;
        $id = $this->accounts->insert($data, true);
        audit('create', 'accounts', (int) $id, $data['name']);

        return redirect()->to('/accounts')->with('success', 'Akun ditambahkan.');
    }

    public function edit(int $id)
    {
        $row = $this->accounts->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('accounts/form', ['row' => $row, 'action' => site_url("accounts/{$id}/update"), 'title' => 'Ubah akun']);
    }

    public function update(int $id)
    {
        $this->accounts->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! $this->validate($this->rules($id))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost(['code', 'name', 'type']);
        $data['is_active'] = $this->request->getPost('is_active') ? 1 : 0;
        $this->accounts->update($id, $data);
        audit('update', 'accounts', $id, $data['name']);

        return redirect()->to('/accounts')->with('success', 'Akun diperbarui.');
    }

    public function delete(int $id)
    {
        $used = db_connect()->table('transactions')->where('account_id', $id)->countAllResults();
        if ($used > 0) {
            return redirect()->to('/accounts')->with('error', 'Akun sudah dipakai transaksi. Nonaktifkan saja lewat menu ubah.');
        }
        $this->accounts->delete($id);
        audit('delete', 'accounts', $id);

        return redirect()->to('/accounts')->with('success', 'Akun dihapus.');
    }
}
