<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<main id="main" class="main">

    <!-- ======================================================= -->
    <!--                  PERUBAIKAN DI BAGIAN INI                -->
    <!-- ======================================================= -->
    <!-- Ditambahkan class d-flex dan style untuk memastikan section bisa di-tengah-kan secara vertikal -->
   <section class="section profile d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-xl-4 col-lg-5 col-md-8">
        <div class="card shadow">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center justify-content-center">

                <img src="<?= base_url('NiceAdmin/assets/img/profiles/' . ($user['foto_profil'] ?? 'default.jpg')) ?>"
                    alt="Profile"
                    class="rounded-circle shadow"
                    style="width: 120px; height: 120px; object-fit: cover;">

                <h2 class="mt-3 mb-1 text-center"><?= $user['username'] ?? 'Guest' ?></h2>
                <h5 class="text-muted text-center mb-3"><?= $user['role'] ?? 'User' ?></h5>

                <a href="<?= base_url('profile') ?>" class="btn btn-primary mt-2">Edit Profile</a>

            </div>
        </div>
    </div>
</section>


</main><!-- End #main -->

<?= $this->endSection() ?>
