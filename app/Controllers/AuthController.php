<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $user;

    public function __construct()
    {
        helper('form');
        $this->user = new UserModel();
    }

    public function login()
    {
        if ($this->request->getPost()) {
            // Validasi form input
            $rules = [
                'username' => 'required|min_length[6]',
                'password' => 'required|min_length[7]|numeric',
            ];

            if ($this->validate($rules)) {
                $username = $this->request->getVar('username');
                $password = $this->request->getVar('password');

                // Ambil data user berdasarkan username
                $dataUser = $this->user->where(['username' => $username])->first();

                if ($dataUser) {
                    if (password_verify($password, $dataUser['password'])) {
                        // Set session
                        session()->set([
                            'username' => $dataUser['username'],
                            'role' => $dataUser['role'],
                            'isLoggedIn' => true,
                        ]);

                        // Redirect berdasarkan role
                        if ($dataUser['role'] == 'admin') {
                            return redirect()->to('admin');
                        } else {
                            return redirect()->to('home');
                        }
                        // Atau jika mau redirect ke homepage:
                        // return redirect()->to(base_url('/'));
                    } else {
                        session()->setFlashdata('failed', 'Password salah.');
                        return redirect()->back();
                    }
                } else {
                    session()->setFlashdata('failed', 'Username tidak ditemukan.');
                    return redirect()->back();
                }
            } else {
                // Validasi gagal
                session()->setFlashdata('failed', $this->validator->listErrors());
                return redirect()->back();
            }
        }

        return view('v_login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
