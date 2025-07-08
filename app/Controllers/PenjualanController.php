<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\UserModel;

// Nama class diubah menjadi PenjualanController
class PenjualanController extends BaseController
{
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $userModel;

    public function __construct()
    {
        // ======================================================= //
        //                PERBAIKAN UTAMA DI SINI                  //
        // ======================================================= //
        // Memuat Number Helper agar fungsi number_to_currency() bisa digunakan.
        helper('number');

        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Fitur Filter Tanggal
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-6 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        
        // Menambahkan satu hari ke end_date untuk query WHERE
        $endDateQuery = date('Y-m-d', strtotime($endDate . ' +1 day'));

        // 1. Data untuk Kartu Statistik (dengan filter tanggal)
        $db = \Config\Database::connect();
        $salesData = $db->table('transaction')
                        ->select('COUNT(id) as total_sales, SUM(total_harga) as total_revenue, COUNT(DISTINCT username) as total_customers')
                        ->where('created_at >=', $startDate)
                        ->where('created_at <', $endDateQuery)
                        ->get()->getRow();

        // 2. Data untuk Chart (dengan filter tanggal)
        $chartDataQuery = $db->table('transaction')
                             ->select('DATE(created_at) as date, COUNT(id) as sales_count')
                             ->where('created_at >=', $startDate)
                             ->where('created_at <', $endDateQuery)
                             ->groupBy('DATE(created_at)')
                             ->orderBy('date', 'ASC')
                             ->get()->getResultArray();

        // Olah data untuk format yang dibutuhkan ApexCharts
        $chartLabels = [];
        $chartValues = [];
        foreach ($chartDataQuery as $row) {
            $chartLabels[] = date('d M', strtotime($row['date']));
            $chartValues[] = $row['sales_count'];
        }

        // 3. Data untuk Transaksi Terbaru
        $recentSales = $this->transactionModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
        
        // 4. Data untuk Produk Terlaris
        // Diperbaiki: Menspesifikasikan kolom 'jumlah' menjadi 'transaction_detail.jumlah'
        $topSellingProducts = $this->transactionDetailModel
                                ->select('product_id, SUM(transaction_detail.jumlah) as total_sold')
                                ->join('product', 'product.id = transaction_detail.product_id')
                                ->select('product.nama, product.foto, product.harga')
                                ->groupBy('product_id')
                                ->orderBy('total_sold', 'DESC')
                                ->limit(5)
                                ->findAll();

        $data = [
            'title' => 'Laporan Penjualan',
            'total_sales' => $salesData->total_sales ?? 0,
            'total_revenue' => $salesData->total_revenue ?? 0,
            'total_customers' => $salesData->total_customers ?? 0,
            'chart_labels' => json_encode($chartLabels),
            'chart_values' => json_encode($chartValues),
            'recent_sales' => $recentSales,
            'top_selling' => $topSellingProducts,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        // Nama view diubah agar sesuai dengan controller baru
        // PERBAIKAN: Menambahkan tanda '$' pada variabel data
        return view('v_penjualan', $data);
    }
}
