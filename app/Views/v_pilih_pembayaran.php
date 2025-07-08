<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">
                    <h4>Pilih Metode Pembayaran</h4>
                    <p>Untuk Pesanan: <strong><?= $transaction['invoice_id'] ?></strong></p>
                </div>
                <div class="card-body">
                    <?= form_open('payment/generate') ?>
                        <?= form_hidden('invoice_id', $transaction['invoice_id']) ?>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="metode_pembayaran" value="VA_BNI" class="btn btn-outline-primary btn-lg">
                                Transfer Virtual Account BNI
                            </button>
                            <button type="submit" name="metode_pembayaran" value="VA_BCA" class="btn btn-outline-primary btn-lg">
                                Transfer Virtual Account BCA
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>