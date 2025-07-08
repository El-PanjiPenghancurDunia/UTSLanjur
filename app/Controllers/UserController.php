<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan halaman daftar pengguna.
     */
    public function index()
    {
        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $this->userModel->findAll()
        ];
        return view('v_user', $data);
    }

    /**
     * Memproses pembuatan pengguna baru.
     */
    public function create()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        $this->userModel->insert($data);
        return redirect()->to('user')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Memproses pembaruan data pengguna.
     */
    public function edit($id)
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
        ];

        // Cek apakah admin ingin mengubah password
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        return redirect()->to('user')->with('success', 'Data pengguna berhasil diubah.');
    }

    /**
     * Menghapus pengguna.
     */
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        // Hapus foto profil jika bukan default
        if ($user && $user['foto_profil'] != 'default.jpg') {
            $path = FCPATH . 'NiceAdmin/assets/img/profiles/' . $user['foto_profil'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->userModel->delete($id);
        return redirect()->to('user')->with('success', 'Pengguna berhasil dihapus.');
    }
}
