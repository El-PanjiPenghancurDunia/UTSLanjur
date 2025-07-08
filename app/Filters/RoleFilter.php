<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');

        // Cek apakah role yang diizinkan ada di arguments
        if (!in_array($role, $arguments)) {
            // Jika role TIDAK sesuai, tampilkan error atau redirect ke halaman sesuai role
            if ($role == 'admin') {
                return redirect()->to('admin');
            } elseif ($role == 'user') {
                return redirect()->to('user');
            } else {
                return redirect()->to('login');
            }
        }

        // Jika role cocok, izinkan akses (tidak perlu return apa-apa)
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak dipakai
    }
}
