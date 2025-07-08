<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pesanan Anda</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background-color: #0d6efd; color: #ffffff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; }
        .content { padding: 30px; }
        .content h2 { color: #333; }
        .content p { line-height: 1.6; color: #555; }
        .order-details { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-details th, .order-details td { border: 1px solid #dddddd; text-align: left; padding: 12px; }
        .order-details th { background-color: #f8f9fa; }
        .total-row { font-weight: bold; }
        .footer { text-align: center; padding: 20px; color: #777; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Terima Kasih Atas Pesanan Anda!</h1>
        </div>
        <div class="content">
            <h2>Halo, <?= esc($transaction['username']) ?>!</h2>
            
            <!-- PERBAIKAN DI SINI: Menggunakan invoice_id -->
            <p>Pembayaran Anda untuk pesanan dengan nomor <strong><?= esc($transaction['invoice_id']) ?></strong> telah berhasil kami konfirmasi. Berikut adalah rincian pesanan Anda:</p>
            
            <table class="order-details">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($details as $item): ?>
                    <tr>
                        <td><?= esc($item['nama_produk']) ?></td>
                        <td><?= esc($item['jumlah']) ?></td>
                        <td><?= number_to_currency($item['subtotal_harga'], 'IDR') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="2">Subtotal Produk</td>
                        <td><?= number_to_currency($subtotal_produk, 'IDR') ?></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="2">Ongkos Kirim</td>
                        <td><?= number_to_currency($transaction['ongkir'], 'IDR') ?></td>
                    </tr>
                    <tr class="total-row" style="font-size: 1.2em;">
                        <td colspan="2">Total Pembayaran</td>
                        <td><?= number_to_currency($transaction['total_harga'], 'IDR') ?></td>
                    </tr>
                </tbody>
            </table>

            <p style="margin-top: 30px;"><b>Alamat Pengiriman:</b><br><?= esc($transaction['alamat']) ?></p>
            <p>Kami akan segera memproses pesanan Anda. Anda akan menerima notifikasi selanjutnya ketika pesanan Anda telah dikirim.</p>
            <p>Terima kasih telah berbelanja di toko kami!</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> WarungSayur. Semua Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>
