<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class DashboardController extends BaseController
{
    // -- PERBAIKAN: Hapus __construct() --
    // Helper sudah dimuat secara otomatis oleh BaseController.

    public function admin()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/home');
        }
        return view('dashboard-admin'); 
    }

    public function user()
    {
        if (session()->get('role') !== 'user') {
            return redirect()->to('/admin');
        }

        $productModel = new ProductModel();
        $products = $productModel->findAll();

        // Ambil diskon aktif dari properti $this->data
        $activeDiscount = $this->data['activeDiscount'];

        if ($activeDiscount) {
            foreach ($products as &$product) {
                $product['original_harga'] = $product['harga'];
                $product['harga'] = $product['harga'] - $activeDiscount['jumlah_diskon'];
                if ($product['harga'] < 0) {
                    $product['harga'] = 0;
                }
            }
        }

        $pageData = [
            'product'  => $products,
        ];

        // Gabungkan data global dengan data lokal
        return view('v_home', array_merge($this->data, $pageData));
    }
}