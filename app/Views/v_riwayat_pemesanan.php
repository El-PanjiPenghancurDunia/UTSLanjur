<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Riwayat Pemesanan Anda</h5>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">ID Pesanan</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Total Pembayaran</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)) : ?>
                    <?php foreach ($transactions as $transaction) : ?>
                        <tr>
                            <!-- PERBAIKAN: Tampilkan invoice_id -->
                            <th scope="row"><?= esc($transaction['invoice_id']) ?></th>
                            <td><?= date('d M Y, H:i', strtotime($transaction['created_at'])) ?></td>
                            <td><?= number_to_currency($transaction['total_harga'], 'IDR') ?></td>
                            <td>
                                <?php 
                                    $status = $transaction['status'];
                                    $badge_class = 'bg-secondary'; // Default
                                    if ($status == 'pending') {
                                        $badge_class = 'bg-warning text-dark';
                                    } elseif ($status == 'paid') {
                                        $badge_class = 'bg-success';
                                    } elseif ($status == 'failed' || $status == 'expired' || $status == 'canceled') {
                                        $badge_class = 'bg-danger';
                                    }
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= ucfirst($status) ?></span>
                            </td>
                            <td>
                                <!-- PERBAIKAN: Gunakan invoice_id di URL -->
                                <?php if ($status == 'pending') : ?>
                                    <a href="<?= base_url('payment/instruction/' . $transaction['invoice_id']) ?>" class="btn btn-primary btn-sm">
                                        Bayar Sekarang
                                    </a>
                                <?php else : ?>
                                    <a href="#" class="btn btn-info btn-sm">Lihat Detail</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">Anda belum memiliki riwayat pemesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- End Table with stripped rows -->

    </div>
</div>

<?= $this->endSection() ?>
