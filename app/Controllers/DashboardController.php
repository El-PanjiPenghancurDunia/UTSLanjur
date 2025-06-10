<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{

    function __construct()
    {
        helper('form');
        helper('number');
    }
    public function admin()
    {
        // Cek role, jika bukan admin redirect ke user
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/user');
        }

        // Kirim data username dan role ke view
        $data = [
            'username' => session()->get('username'),
            'role' => session()->get('role')
        ];

        // Tampilkan dashboard admin
        return view('dashboard-admin', $data); 
    }

    public function user()
    {
        // Cek role, jika bukan user redirect ke admin
        if (session()->get('role') !== 'user') {
            return redirect()->to('/admin');
        }

            $productModel = new \App\Models\ProductModel();
            $product = $productModel->findAll();

        $data = [
            'username' => session()->get('username'),
            'role' => session()->get('role'),
            'product' => $product
        ];

        // Tampilkan dashboard user
        return view('v_home', $data); 
    }
}
