<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<title>Daftar Produk Sayuran</title>
<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<?php
if (session()->getFlashData('failed')) {
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('failed') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah Data
</button>
<a type="button" class="btn btn-success" href="<?= base_url() ?>produk/download">
    Download Data
</a>
<!-- Table with stripped rows -->
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Foto</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($product as $index => $produk) : ?>
            <tr>
                <th scope="row"><?php echo $index + 1 ?></th>
                <td><?php echo $produk['nama'] ?></td>
                <td><?php echo $produk['harga'] ?></td>
                <td><?php echo $produk['jumlah'] ?></td>
                <td>
                    <?php if ($produk['foto'] != '' and file_exists("img/" . $produk['foto'] . "")) : ?>
                        <img src="<?php echo base_url() . "img/" . $produk['foto'] ?>" width="100px">
                    <?php endif; ?>
                </td>
                <td>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal-<?= $produk['id'] ?>">
                        Ubah
                    </button>
                    <a href="<?= base_url('produk/delete/' . $produk['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini ?')">
                        Hapus
                    </a>
                </td>
            </tr>
            <!-- Edit Modal Begin -->
<div class="modal fade" id="editModal-<?= $produk['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('produk/edit/' . $produk['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="nama" class="form-control" id="nama" value="<?= $produk['nama'] ?>" placeholder="Nama Barang" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Harga</label>
                        <input type="text" name="harga" class="form-control" id="harga" value="<?= $produk['harga'] ?>" placeholder="Harga Barang" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Jumlah</label>
                        <input type="text" name="jumlah" class="form-control" id="jumlah" value="<?= $produk['jumlah'] ?>" placeholder="Jumlah Barang" required>
                    </div>
                    <img src="<?php echo base_url() . "img/" . $produk['foto'] ?>" width="100px">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check" name="check" value="1">
                        <label class="form-check-label" for="check">
                            Ceklis jika ingin mengganti foto
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="name">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal End -->
        <?php endforeach ?>
    </tbody>
</table>
<!-- End Table with stripped rows --> 
 <!-- Add Modal Begin -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('produk') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Barang" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Harga</label>
                        <input type="text" name="harga" class="form-control" id="harga" placeholder="Harga Barang" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Jumlah</label>
                        <input type="text" name="jumlah" class="form-control" id="jumlah" placeholder="Jumlah Barang" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Modal End -->
<!--   
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background-color: #f5f5f5;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }
    .product-card {
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.2s;
    }
    .product-card:hover {
      transform: scale(1.02);
    }
    .product-image img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
    }
    .product-info {
      padding: 16px;
    }
    .product-name {
      font-size: 20px;
      font-weight: bold;
      margin: 0 0 8px;
    }
    .product-price {
      color: #4CAF50;
      font-size: 18px;
      font-weight: bold;
    }
    .buy-button {
      display: block;
      width: 100%;
      padding: 10px 0;
      background-color: #4CAF50;
      color: #ffffff;
      text-align: center;
      border: none;
      border-radius: 0 0 12px 12px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .buy-button:hover {
      background-color: #45a049;
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .product-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }
    @media (max-width: 768px) {
      .product-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media (max-width: 480px) {
      .product-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>

<h1>Daftar Sayuran Segar</h1>
<br>
<div class="product-grid">
  <div class="product-card">
    <div class="product-image">
      <img src="<?= base_url()?>NiceAdmin/assets/img/bayam.jpg" alt="Bayam">
    </div>
    <div class="product-info">
      <h3 class="product-name">Bayam Segar</h3>
      <p class="product-price">Rp5.000 / ikat</p>
      <button class="buy-button">Beli</button>
    </div>
  </div>

  <div class="product-card">
    <div class="product-image">
      <img src="<?= base_url()?>NiceAdmin/assets/img/sawi.jpg" alt="Kangkung">
    </div>
    <div class="product-info">
      <h3 class="product-name">Sawi</h3>
      <p class="product-price">Rp4.500 / ikat</p>
      <button class="buy-button">Beli</button>  
    </div>
  </div>

  <div class="product-card">
    <div class="product-image">
      <img src="<?= base_url()?>NiceAdmin/assets/img/kangkung.jpg" alt="Wortel">
    </div>
    <div class="product-info">
      <h3 class="product-name">Wortel</h3>
      <p class="product-price">Rp8.000 / 500g</p>
      <button class="buy-button">Beli</button>
    </div>
  </div>

  <div class="product-card">
    <div class="product-image">
      <img src="<?= base_url()?>NiceAdmin/assets/img/brokoli.jpg" alt="Brokoli">
    </div>
    <div class="product-info">
      <h3 class="product-name">Brokoli</h3>
      <p class="product-price">Rp10.000 / bonggol</p>
      <button class="buy-button">Beli</button>  
    </div>
  </div>

</div> -->

<?= $this->endSection() ?>