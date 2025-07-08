<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <?php
        if (session()->get('role') == 'admin') {
        ?>
        <!-- Menu untuk Admin -->
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'produk') ? "" : "collapsed" ?>" href="<?= base_url('produk') ?>">
                <i class="bi bi-receipt"></i>
                <span>Produk</span>
            </a>
        </li><!-- End Produk Nav --> 

        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'diskon') ? "" : "collapsed" ?>" href="<?= base_url('diskon') ?>">
                <i class="bi bi-tag-fill"></i>
                <span>Diskon</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'user') ? "" : "collapsed" ?>" href="<?= base_url('user') ?>">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
        </li><!-- End User Nav --> 
        
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'penjualan') ? "" : "collapsed" ?>" href="<?= base_url('penjualan') ?>">
                <i class="bi bi-graph-up"></i>
                <span>Laporan Penjualan</span>
            </a>
        </li><!-- End Laporan Penjualan Nav -->

        <?php
        }
        ?>
        <?php
        if (session()->get('role') == 'user') {
        ?>
        <!-- Menu untuk User -->
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'home') ? "" : "collapsed" ?>" href="<?= base_url('home') ?>">
                <i class="bi bi-grid"></i>
                <span>Home</span>
            </a>
        </li><!-- End Home Nav --> 
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'keranjang') ? "" : "collapsed" ?>" href="<?= base_url('keranjang') ?>">
                <i class="bi bi-cart-check"></i>
                <span>Keranjang</span>
            </a>
        </li><!-- End Keranjang Nav --> 

        <!-- == MENU BARU DITAMBAHKAN DI SINI == -->
        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'riwayat') ? "" : "collapsed" ?>" href="<?= base_url('riwayat') ?>">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat Pesanan</span>
            </a>
        </li><!-- End Riwayat Pesanan Nav -->
        
        <?php
        }
        ?>

    </ul>

</aside><!-- End Sidebar-->
