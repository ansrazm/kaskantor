<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function password()
    {
        return view('auth/password');
    }

    public function updatePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]|max_length[72]',
            'confirm_password' => 'required|matches[new_password]',
        ];
        $messages = [
            'new_password'     => ['min_length' => 'Password baru minimal 8 karakter.'],
            'confirm_password' => ['matches' => 'Konfirmasi password tidak sama.'],
        ];
        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $users = new UserModel();
        $user  = $users->find((int) session('user_id'));
        if (! $user || ! password_verify((string) $this->request->getPost('current_password'), $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password saat ini salah.');
        }

        $users->update($user['id'], ['password_hash' => password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT)]);
        audit('password', 'users', (int) $user['id'], 'ganti password');

        return redirect()->to('/dashboard')->with('success', 'Password diganti.');
    }
}
