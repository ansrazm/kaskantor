<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attempt()
    {
        // Batasi percobaan login: 5 kali per menit per IP
        $throttler = service('throttler');
        if ($throttler->check(md5('login-' . $this->request->getIPAddress()), 5, MINUTE) === false) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan. Coba lagi sebentar lagi.');
        }

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = (new UserModel())->findByEmail((string) $this->request->getPost('email'));
        if (! $user || ! password_verify((string) $this->request->getPost('password'), $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        // Cegah session fixation
        session()->regenerate(true);
        session()->set([
            'user_id' => (int) $user['id'],
            'name'    => $user['name'],
            'role'    => $user['role'],
        ]);
        audit('login', 'users', (int) $user['id']);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        audit('logout', 'users', (int) session('user_id'));
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Kamu sudah keluar.');
    }
}
