<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Tampilkan banner diskon jika ada -->
<?php if (isset($activeDiscount) && $activeDiscount) : ?>
<div class="alert alert-info text-center" role="alert">
    <strong>PROMO SPESIAL: <?= esc($activeDiscount['nama_diskon']) ?>!</strong> Potongan harga sebesar <?= number_to_currency($activeDiscount['jumlah_diskon'], 'IDR') ?> untuk semua produk!
</div>
<?php endif; ?>


<div class="row">
    <?php foreach ($product as $key => $item) : ?>
        <div class="col-lg-6">
            
            <!-- PERBAIKAN: Action form diubah ke 'keranjang/add' -->
            <?= form_open('keranjang/add') ?>
                <?= form_hidden('id', (string) $item['id']); ?>
                <?= form_hidden('harga', (string) $item['harga']); ?>
            
            <div class="card">
                <div class="card-body">
                    <img src="<?php echo base_url("img/" . $item['foto']) ?>" alt="..." width="300px">
                    <h5 class="card-title"><?php echo $item['nama'] ?>
                        <br>
                        
                        <?php if (isset($item['original_harga'])) : ?>
                            <s class="text-muted" style="font-size: 0.9rem;"><?= number_to_currency($item['original_harga'], 'IDR') ?></s>
                            <br>
                            <span class="text-danger fw-bold fs-5"><?= number_to_currency($item['harga'], 'IDR') ?></span>
                        <?php else : ?>
                            <span class="fs-5"><?php echo number_to_currency($item['harga'], 'IDR') ?></span>
                        <?php endif; ?>

                    </h5>
                    <button type="submit" class="btn btn-info rounded-pill">Beli</button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>