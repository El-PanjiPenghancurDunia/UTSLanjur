<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


// PENTING: Pastikan TransaksiController juga extends BaseController
class TransaksiController extends BaseController 
{
    protected $cart;
    protected $client;
    protected $apikey;
    protected $transaction;
    protected $transaction_detail;
    protected $productModel;
    protected $userModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // PENTING: Panggil initController dari parent untuk menjalankan logika global
        parent::initController($request, $response, $logger);

        // Inisialisasi semua properti di sini
        $this->cart = \Config\Services::cart();
        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = env('COST_KEY');
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
        $this->productModel = new ProductModel();
        $this->userModel = new UserModel();
    }
    public function index()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $productId = $this->request->getPost('id');
        $product = $this->productModel->find($productId);

        if (!$product) {
            return redirect()->back()->with('failed', 'Produk tidak ditemukan.');
        }

        $hargaFinal = $this->request->getPost('harga');

        $this->cart->insert([
            'id'      => $productId,
            'qty'     => 1,
            'price'   => $hargaFinal,
            'name'    => $product['nama'],
            'options' => ['foto' => $product['foto']]
        ]);

        session()->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang. (<a href="' . base_url('keranjang') . '">Lihat</a>)');
        return redirect()->to(base_url('home'));
    }
    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setflashdata('success', 'Keranjang Berhasil Dikosongkan');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_edit()
    {
        $all_qty = $this->request->getPost('qty');
        $update_data = [];
        if ($all_qty) {
            foreach ($all_qty as $rowid => $qty) {
                $update_data[] = [
                    'rowid' => $rowid,
                    'qty'   => $qty,
                ];
            }
            $this->cart->update($update_data);
        }
        session()->setFlashdata('success', 'Keranjang Berhasil Diperbarui');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setflashdata('success', 'Keranjang Berhasil Dihapus');
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        
        // Hitung total diskon HANYA untuk ditampilkan di view
        $total_diskon = 0;
        if ($this->data['activeDiscount']) {
            $diskon_per_item = $this->data['activeDiscount']['jumlah_diskon'];
            $total_diskon = $diskon_per_item * $this->cart->totalItems();
        }
        $data['total_diskon'] = $total_diskon;
        
        $total_berat = 0;
        foreach ($this->cart->contents() as $item) {
            $produk = $this->productModel->find($item['id']);
            if ($produk && isset($produk['berat'])) {
                $total_berat += $produk['berat'] * $item['qty'];
            }
        }
        $data['total_berat'] = $total_berat;

        if (empty($data['items'])) {
            session()->setFlashdata('failed', 'Keranjang Anda kosong, silakan berbelanja terlebih dahulu.');
            return redirect()->to(base_url('home'));
        }

        return view('v_checkout', array_merge($this->data, $data));
    }

    public function buy()
    {
        if (!$this->request->getPost()) {
            return redirect()->to('/');
        }

        $invoice_id = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());
        $ongkir_numeric = (int) preg_replace('/[^0-9]/', '', $this->request->getPost('ongkir'));

        // Hitung lagi total diskon untuk disimpan ke database
        $total_diskon = 0;
        if ($this->data['activeDiscount']) {
            $total_diskon = $this->data['activeDiscount']['jumlah_diskon'] * $this->cart->totalItems();
        }

        $dataForm = [
            'invoice_id'     => $invoice_id,
            'username'       => session()->get('username'),
            'total_harga'    => $this->request->getPost('total_harga'), // Ini sudah total akhir
            'total_diskon'   => $total_diskon, // Simpan jumlah diskonnya
            'alamat'         => $this->request->getPost('alamat'),
            'ongkir'         => $ongkir_numeric,
            'status'         => 'pending',
        ];

        $this->transaction->insert($dataForm);
        $transaction_id_numeric = $this->transaction->getInsertID();

        foreach ($this->cart->contents() as $value) {
            $this->transaction_detail->insert([
                'transaction_id' => $transaction_id_numeric,
                'product_id'     => $value['id'],
                'jumlah'         => $value['qty'],
                'subtotal_harga' => $value['qty'] * $value['price'],
            ]);
        }
        
        $this->cart->destroy();

        session()->setFlashdata('success', 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran.');
        return redirect()->to(base_url('payment/choose/' . $invoice_id));
    }
    
    // ... (sisa fungsi pembayaran dan API tidak perlu diubah) ...
    public function choosePayment($invoice_id)
    {
        $transaction = $this->transaction->where('invoice_id', $invoice_id)->first();
        if (!$transaction || $transaction['status'] != 'pending') {
            session()->setFlashdata('failed', 'Transaksi tidak ditemukan atau sudah diproses.');
            return redirect()->to('home');
        }
        $data['transaction'] = $transaction;
        return view('v_pilih_pembayaran', $data);
    }
    
    public function generatePaymentCode()
    {
        $invoice_id = $this->request->getPost('invoice_id');
        $metode = $this->request->getPost('metode_pembayaran');

        $kode_pembayaran = '';
        if ($metode == 'VA_BNI') {
            $kode_pembayaran = '8808' . rand(1000000000, 9999999999);
        } else if ($metode == 'VA_BCA') {
            $kode_pembayaran = '39108' . rand(100000000, 999999999);
        } else {
            session()->setFlashdata('failed', 'Metode pembayaran tidak valid.');
            return redirect()->back();
        }

        $this->transaction->where('invoice_id', $invoice_id)->set([
            'metode_pembayaran' => $metode,
            'kode_pembayaran'   => $kode_pembayaran,
        ])->update();

        return redirect()->to(base_url('payment/instruction/' . $invoice_id));
    }

    public function instruction($invoice_id)
    {
        $transaction = $this->transaction->where('invoice_id', $invoice_id)->first();
        if (!$transaction) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $data['transaction'] = $transaction;
        return view('v_instruksi_pembayaran', $data);
    }

    public function confirmPayment()
    {
        $invoice_id = $this->request->getPost('invoice_id');
        
        $transaction = $this->transaction->where('invoice_id', $invoice_id)->first();

        if (!$transaction) {
            session()->setFlashdata('failed', 'Transaksi tidak valid.');
            return redirect()->to(base_url('riwayat'));
        }

        $details = $this->transaction_detail->where('transaction_id', $transaction['id'])->findAll();

        foreach ($details as $item) {
            $this->productModel->where('id', $item['product_id'])->decrement('jumlah', (int) $item['jumlah']);
        }

        $this->transaction->where('invoice_id', $invoice_id)->set(['status' => 'paid'])->update();

        $this->sendInvoiceEmail($invoice_id);

        session()->setFlashdata('success', 'Pembayaran untuk pesanan ' . $invoice_id . ' telah berhasil dikonfirmasi!');
        return redirect()->to(base_url('home'));
    }

    private function sendInvoiceEmail($invoice_id)
    {
        $sendgridApiKey = env('SENDGRID_API_KEY');
        if (empty($sendgridApiKey)) {
            log_message('error', 'SendGrid API Key is not set.');
            return false;
        }

        $transaction = $this->transaction->where('invoice_id', $invoice_id)->first();
        if (!$transaction) {
            log_message('error', "Transaction not found for email sending: {$invoice_id}");
            return false;
        }

        $details = $this->transaction_detail
                        ->select('transaction_detail.*, product.nama as nama_produk')
                        ->join('product', 'product.id = transaction_detail.product_id')
                        ->where('transaction_id', $transaction['id'])
                        ->findAll();

        $user = $this->userModel->where('username', $transaction['username'])->first();
        if (!$user || empty($user['email'])) {
            log_message('error', "User email not found for username: {$transaction['username']}");
            return false;
        }

        $subtotal_produk = 0;
        foreach ($details as $item) {
            $subtotal_produk += $item['subtotal_harga'];
        }

        $emailData = [
            'transaction' => $transaction,
            'details' => $details,
            'subtotal_produk' => $subtotal_produk
        ];

        $emailContent = view('emails/v_invoice_email', $emailData);
        
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("panjikusumo89@gmail.com", "WarungSayur");
        $email->setSubject("Invoice Pesanan Anda " . $invoice_id);
        $email->addTo($user['email'], $user['username']);
        $email->addContent("text/html", $emailContent);

        $sendgrid = new \SendGrid($sendgridApiKey);
        try {
            $response = $sendgrid->send($email);
            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                log_message('info', "Email sent successfully to {$user['email']} for order {$invoice_id}");
                return true;
            } else {
                log_message('error', 'SendGrid Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            log_message('error', 'SendGrid Exception: ' . $e->getMessage());
            return false;
        }
        return false;
    }

    public function history()
    {
        $username = session()->get('username');
        
        $data['transactions'] = $this->transaction
                                    ->where('username', $username)
                                    ->orderBy('created_at', 'DESC')
                                    ->findAll();

        return view('v_riwayat_pemesanan', $data);
    }
    
    public function getLocation()
    {
        $search = $this->request->getGet('search');
        $response = $this->client->request(
            'GET', 
            'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=50', [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]
        );
        $body = json_decode($response->getBody(), true); 
        if (isset($body['data'])) {
            return $this->response->setJSON($body['data']);
        } else {
            return $this->response->setJSON([]);
        }
    }

    public function getCost()
    { 
        $destination = $this->request->getGet('destination');
        $weight = $this->request->getGet('weight');

        if (empty($destination) || empty($weight) || $weight <= 0) {
            return $this->response->setJSON(['error' => 'Parameter tidak lengkap atau berat tidak valid.']);
        }

        $response = $this->client->request(
            'POST', 
            'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'multipart' => [
                    ['name' => 'origin','contents' => '64999'],
                    ['name' => 'destination','contents' => $destination],
                    ['name' => 'weight','contents' => $weight],
                    ['name' => 'courier','contents' => 'jne']
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]
        );

        $body = json_decode($response->getBody(), true); 
        if (isset($body['data'])) {
            return $this->response->setJSON($body['data']);
        } else {
            return $this->response->setJSON([]);
        }
    }
}
