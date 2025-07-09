<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ChatbotController extends BaseController
{
    protected $productModel;
    protected $client;
    protected $geminiApiKey;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->client = \Config\Services::curlrequest(); // Menggunakan HTTP Client bawaan CodeIgniter
        $this->geminiApiKey = env('GEMINI_API_KEY');
    }

    /**
     * Menerima pertanyaan dari user, memanggil Gemini API, dan mengembalikan jawaban.
     */
    public function ask()
    {
        // Hanya izinkan request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $userQuestion = $this->request->getPost('question');

        if (empty($userQuestion) || empty($this->geminiApiKey)) {
            return $this->response->setJSON(['error' => 'Pertanyaan atau API Key tidak boleh kosong.']);
        }

        // 1. Ambil semua data produk dari database
        $products = $this->productModel->findAll();

        // 2. Format data produk menjadi teks sederhana sebagai konteks untuk AI
        $productContext = "Berikut adalah daftar produk yang tersedia:\n";
        foreach ($products as $product) {
            $productContext .= "- Nama: " . $product['nama'] . ", Harga: Rp " . number_format($product['harga']) . ", Stok: " . $product['jumlah'] . ", Berat: " . $product['berat'] . " gram.\n";
        }

        // 3. Buat "Prompt" yang akan dikirim ke AI
        $prompt = "Anda adalah asisten virtual dari sebuah warung Sayuran. Tugas Anda adalah menjawab pertanyaan pelanggan dengan ramah dan informatif. Jawab pertanyaan hanya berdasarkan konteks data produk yang saya berikan. Jika pertanyaan di luar konteks produk, jawab dengan sopan bahwa Anda hanya bisa membantu seputar produk yang dijual.\n\n"
                . "Konteks Data Produk:\n" . $productContext . "\n"
                . "Pertanyaan Pelanggan: " . $userQuestion;

        // 4. Panggil Gemini API
        try {
            $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $this->geminiApiKey;

            $response = $this->client->post($apiUrl, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]
            ]);

            $body = json_decode($response->getBody(), true);

            // 5. Ambil dan kirim jawaban dari AI
            if (isset($body['candidates'][0]['content']['parts'][0]['text'])) {
                $aiAnswer = $body['candidates'][0]['content']['parts'][0]['text'];
                return $this->response->setJSON(['answer' => $aiAnswer]);
            } else {
                // Tangani jika format respons tidak seperti yang diharapkan
                log_message('error', 'Gemini API response format unexpected: ' . $response->getBody());
                return $this->response->setJSON(['answer' => 'Maaf, saya sedang mengalami sedikit gangguan. Coba beberapa saat lagi.']);
            }

        } catch (\Exception $e) {
            log_message('error', '[Gemini API] ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Gagal menghubungi layanan AI.']);
        }
    }
}
