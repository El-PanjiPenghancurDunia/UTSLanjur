<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Laporan Penjualan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Laporan Penjualan</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="row">

        <!-- Kolom Kiri -->
        <div class="col-lg-12">
            <div class="row">

                <!-- Filter Tanggal -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filter Laporan</h5>
                            <form class="row g-3">
                                <div class="col-md-5">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
                                </div>
                                <div class="col-md-5">
                                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Kartu Sales -->
                <div class="col-md-4">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Penjualan <span>| Sesuai Filter</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $total_sales ?></h6>
                                    <span class="text-muted small pt-2 ps-1">Transaksi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Revenue -->
                <div class="col-md-4">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">Pendapatan <span>| Sesuai Filter</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= number_to_currency($total_revenue, 'IDR', 'id_ID', 2) ?></h6>
                                    <span class="text-muted small pt-2 ps-1">Total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Customers -->
                <div class="col-md-4">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Pelanggan <span>| Sesuai Filter</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $total_customers ?></h6>
                                    <span class="text-muted small pt-2 ps-1">Orang</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Chart -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Laporan Penjualan <span>/ Sesuai Filter</span></h5>
                            <div id="reportsChart"></div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Terbaru -->
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="card-body">
                            <h5 class="card-title">Transaksi Terbaru <span>| 5 Terakhir</span></h5>
                            <table class="table table-borderless datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#ID</th>
                                        <th scope="col">Pelanggan</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_sales as $sale) : ?>
                                        <tr>
                                            <th scope="row"><a href="#">#<?= $sale['id'] ?></a></th>
                                            <td><?= $sale['username'] ?></td>
                                            <td><?= number_to_currency($sale['total_harga'], 'IDR', 'id_ID', 2) ?></td>
                                            <td><span class="badge bg-success">Lunas</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                 <!-- Produk Terlaris -->
                 <div class="col-12">
                    <div class="card top-selling overflow-auto">
                        <div class="card-body pb-0">
                            <h5 class="card-title">Produk Terlaris <span>| 5 Teratas</span></h5>
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Preview</th>
                                        <th scope="col">Produk</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Terjual</th>
                                        <th scope="col">Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($top_selling as $product): ?>
                                    <tr>
                                        <th scope="row"><a href="#"><img src="<?= base_url('img/' . $product['foto']) ?>" alt=""></a></th>
                                        <td><a href="#" class="text-primary fw-bold"><?= $product['nama'] ?></a></td>
                                        <td><?= number_to_currency($product['harga'], 'IDR', 'id_ID', 2) ?></td>
                                        <td class="fw-bold"><?= $product['total_sold'] ?></td>
                                        <td><?= number_to_currency($product['harga'] * $product['total_sold'], 'IDR', 'id_ID', 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#reportsChart"), {
            series: [{
                name: 'Jumlah Penjualan',
                data: <?= $chart_values ?>,
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
            },
            markers: {
                size: 4
            },
            colors: ['#4154f1'],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.4,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                type: 'category',
                categories: <?= $chart_labels ?>
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy'
                },
            }
        }).render();
    });
</script>
<?= $this->endSection() ?>
