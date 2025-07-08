<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5 text-center">
                <div class="card-header">
                    <h4>Selesaikan Pembayaran</h4>
                    <p>Pesanan: <strong><?= $transaction['invoice_id'] ?></strong></p>
                </div>
                <div class="card-body">
                    <p>Silakan lakukan pembayaran sebesar:</p>
                    <h2 class="card-title text-primary">
                        <?= number_to_currency($transaction['total_harga'], 'IDR') ?>
                    </h2>
                    
                    <p class="mt-4">Ke Nomor Virtual Account:</p>
                    <h3 class="card-title bg-light p-3 rounded">
                        <?= $transaction['kode_pembayaran'] ?>
                    </h3>
                    <small>Metode: <?= $transaction['metode_pembayaran'] ?></small>
                    
                    <hr>
                    <p>Ini adalah simulasi. Untuk melanjutkan, klik tombol di bawah ini:</p>
                    
                    <?= form_open('payment/confirm') ?>
                        <?= form_hidden('invoice_id', $transaction['invoice_id']) ?>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                Saya Sudah Bayar
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
                <div class="card-footer text-muted">
                    Selesaikan pembayaran sebelum waktu habis.
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
