<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/** Pemakaian: 'filter' => 'role:admin,kasir' */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! in_array(session('role'), $arguments ?? [], true)) {
            if (str_starts_with(ltrim($request->getUri()->getPath(), '/'), 'api/')) {
                return service('response')->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
            }

            return redirect()->to('/dashboard')->with('error', 'Kamu tidak punya akses ke halaman itu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
