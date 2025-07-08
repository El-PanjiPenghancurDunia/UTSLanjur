<?php
// app/Controllers/BaseController.php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\DiskonModel;

abstract class BaseController extends Controller
{
    protected $request;
    
    /**
     * Daftarkan helper di sini agar otomatis dimuat untuk semua controller.
     */
    protected $helpers = ['form', 'number'];
    
    /**
     * Properti untuk menampung data global.
     */
    protected $data = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Logika Pengecekan Diskon Global
        $this->loadActiveDiscount();
    }

    private function loadActiveDiscount()
    {
        $diskonModel = new DiskonModel();
        $today = date('Y-m-d');

        $activeDiscount = $diskonModel
            ->where('tanggal_mulai <=', $today)
            ->where('tanggal_selesai >=', $today)
            ->first();

        $this->data['activeDiscount'] = $activeDiscount;
    }
}
