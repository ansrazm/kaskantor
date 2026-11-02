<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    public function index()
    {
        return view('categories/index', ['rows' => (new CategoryModel())->orderBy('type')->orderBy('name')->findAll()]);
    }

    public function store()
    {
        $rules = ['name' => 'required|max_length[100]', 'type' => 'required|in_list[masuk,keluar]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $id = (new CategoryModel())->insert($this->request->getPost(['name', 'type']), true);
        audit('create', 'categories', (int) $id, (string) $this->request->getPost('name'));

        return redirect()->to('/categories')->with('success', 'Kategori ditambahkan.');
    }

    public function delete(int $id)
    {
        if (db_connect()->table('transactions')->where('category_id', $id)->countAllResults() > 0) {
            return redirect()->to('/categories')->with('error', 'Kategori sudah dipakai transaksi, tidak bisa dihapus.');
        }
        (new CategoryModel())->delete($id);
        audit('delete', 'categories', $id);

        return redirect()->to('/categories')->with('success', 'Kategori dihapus.');
    }
}
