<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah Diskon
</button>

<table class="table datatable">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Promo</th>
            <th>Jumlah Potongan</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($diskon as $index => $item) : ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $item['nama_diskon'] ?></td>
                <td><?= number_to_currency($item['jumlah_diskon'], 'IDR', 'id_ID', 2) ?></td>
                <td><?= $item['tanggal_mulai'] ?></td>
                <td><?= $item['tanggal_selesai'] ?></td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-<?= $item['id'] ?>">
                        Ubah
                    </button>
                    <a href="<?= base_url('diskon/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">
                        Hapus
                    </a>
                </td>
            </tr>

            <div class="modal fade" id="editModal-<?= $item['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Diskon</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('diskon/edit/' . $item['id']) ?>" method="post">
                            <?= csrf_field(); ?>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label for="nama_diskon" class="form-label">Nama Promo</label>
                                    <input type="text" name="nama_diskon" class="form-control" value="<?= $item['nama_diskon'] ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label for="jumlah_diskon" class="form-label">Jumlah Potongan (Rp)</label>
                                    <input type="number" name="jumlah_diskon" class="form-control" value="<?= $item['jumlah_diskon'] ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control" value="<?= $item['tanggal_mulai'] ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control" value="<?= $item['tanggal_selesai'] ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Diskon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('diskon/create') ?>" method="post">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="nama_diskon" class="form-label">Nama Promo</label>
                        <input type="text" name="nama_diskon" class="form-control" placeholder="Contoh: Diskon Gajian" required>
                    </div>
                    <div class="mb-2">
                        <label for="jumlah_diskon" class="form-label">Jumlah Potongan (Rp)</label>
                        <input type="number" name="jumlah_diskon" class="form-control" placeholder="Contoh: 50000" required>
                    </div>
                    <div class="mb-2">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>