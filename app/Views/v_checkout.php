<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-7">
        <?= form_open('buy', 'class="row g-3"') ?>
        <?= form_hidden('username', session()->get('username')) ?>
        <?= form_input(['type' => 'hidden', 'name' => 'total_harga', 'id' => 'total_harga', 'value' => '']) ?>
        
        <!-- Input ini tidak terlihat, tapi nilainya (angka murni) akan dikirim ke server -->
        <?= form_input(['type' => 'hidden', 'name' => 'ongkir', 'id' => 'ongkir_hidden', 'value' => '0']) ?>

        <div class="col-12">
            <label for="nama" class="form-label">Nama Penerima</label>
            <input type="text" class="form-control" id="nama" value="<?= session()->get('username'); ?>" readonly>
        </div>
        <div class="col-12">
            <label for="alamat" class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap (Jalan, No Rumah, RT/RW)" required></textarea>
        </div>
        <div class="col-12">
            <label for="kelurahan" class="form-label">Cari Kelurahan/Kecamatan Tujuan</label>
            <select class="form-control" name="kelurahan" id="kelurahan" required></select>
        </div>
        <div class="col-12">
            <label for="layanan" class="form-label">Layanan Pengiriman</label>
            <select class="form-control" name="layanan" id="layanan" required></select>
        </div>
        <div class="col-12">
            <label for="ongkir_display" class="form-label">Ongkos Kirim</label>
            <input type="text" class="form-control" id="ongkir_display" readonly>
        </div>
        </div>
    <div class="col-lg-5">
        <h5>Rincian Pesanan</h5>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)) : ?>
                    <?php foreach ($items as $item) : ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td class="text-end"><?= number_to_currency($item['price'] * $item['qty'], 'IDR', 'id_ID', 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr class="fw-bold">
                    <td colspan="2">Subtotal Produk</td>
                    <td class="text-end"><?= number_to_currency($total, 'IDR', 'id_ID', 2) ?></td>
                </tr>

                <!-- Tampilkan baris diskon hanya jika ada diskon -->
                <?php if (isset($total_diskon) && $total_diskon > 0) : ?>
                    <tr class="fw-bold text-success">
                        <td colspan="2">Total Diskon</td>
                        <td class="text-end">- <?= number_to_currency($total_diskon, 'IDR', 'id_ID', 2) ?></td>
                    </tr>
                <?php endif; ?>

                <tr class="fw-bold">
                    <td colspan="2">Ongkos Kirim</td>
                    <td class="text-end"><span id="ongkir_text">Rp 0</span></td>
                </tr>
                <tr class="fs-5 fw-bold table-group-divider">
                    <td colspan="2">Total Pembayaran</td>
                    <td class="text-end"><span id="total_text"><?= number_to_currency($total, 'IDR', 'id_ID', 2) ?></span></td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <button type="submit" class="btn btn-primary w-100">Buat Pesanan</button>
        </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let subtotal = <?= $total ?>;
    let totalBerat = <?= $total_berat ?? 0 ?>;
    let ongkir = 0;

    function formatRupiah(angka, prefix) {
        return prefix + new Intl.NumberFormat('id-ID').format(angka);
    }

    function hitungTotal() {
        // --- PERBAIKAN UTAMA DI SINI ---
        // Total akhir adalah subtotal (yang sudah didiskon) + ongkir.
        // Jangan kurangi dengan diskon lagi.
        let totalAkhir = subtotal + ongkir;
        
        $("#ongkir_display").val(formatRupiah(ongkir, 'Rp '));
        $("#ongkir_hidden").val(ongkir);
        $("#ongkir_text").html(formatRupiah(ongkir, 'Rp '));
        $("#total_text").html(formatRupiah(totalAkhir, 'Rp '));
        $("#total_harga").val(totalAkhir);
    }

    hitungTotal();

    $('#kelurahan').select2({
        placeholder: 'Ketik nama kelurahan/kecamatan...',
        ajax: {
            url: '<?= base_url('get-location') ?>',
            dataType: 'json',
            delay: 500,
            data: function(params) {
                return { search: params.term };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: `${item.subdistrict_name}, ${item.district_name}, ${item.city_name}`
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    $("#kelurahan").on('change', function() {
        let id_kelurahan = $(this).val();
        $("#layanan").empty();
        ongkir = 0;
        hitungTotal();

        if (!id_kelurahan || totalBerat <= 0) {
            $("#layanan").append('<option value="">' + (totalBerat <= 0 ? 'Berat produk tidak valid' : 'Pilih tujuan dahulu') + '</option>');
            return;
        }

        $("#layanan").append('<option value="">Memuat layanan...</option>');

        $.ajax({
            url: "<?= site_url('get-cost') ?>",
            type: 'GET',
            data: {
                'destination': id_kelurahan,
                'weight': totalBerat
            },
            dataType: 'json',
            success: function(data) {
                $("#layanan").empty().append('<option value="" selected disabled>-- Pilih Layanan Pengiriman --</option>');
                if (data && !data.error && data.length > 0) {
                    data.forEach(function(item) {
                        let text = `${item.description} (${item.service}) - ${formatRupiah(item.cost, 'Rp ')} (Estimasi ${item.etd} hari)`;
                        $("#layanan").append($('<option>', { value: item.cost, text: text }));
                    });
                } else {
                    $("#layanan").append(`<option value="">Layanan tidak tersedia</option>`);
                }
            },
            error: function(jqXHR, textStatus) {
                $("#layanan").empty().append(`<option value="">Gagal memuat: ${textStatus}</option>`);
            }
        });
    });

    $("#layanan").on('change', function() {
        let selectedOngkir = parseInt($(this).val());
        ongkir = !isNaN(selectedOngkir) ? selectedOngkir : 0;
        hitungTotal();
    });
});
</script>
<?= $this->endSection() ?>