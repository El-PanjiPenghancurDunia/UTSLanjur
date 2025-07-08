<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    protected $product;

    function __construct()
    {
        helper('form');
        helper('number');
        $this->product = new ProductModel();
    }
    public function index(): string
    {

        return view('dashboard-admin',$data);

    }

    public function index1()
    {
        $product = $this->product->findAll();
        $data['product'] = $product;
        
        return view('v_home', $data); // Kirim $product ke view
    }

    public function index2()
    {
        $product = $this->product->findAll();
        $data['product'] = $product;

        return view('v_produk', $data);
    }



    


}
